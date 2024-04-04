<?php

/**
 * @file Router.php
 * Provides dynamic route handing
 */

namespace bchubbweb\phntm\Routing;

use ReflectionClass;
use bchubbweb\phntm\Routing\ParameterTypeException;
use bchubbweb\phntm\Phntm;
use bchubbweb\phntm\Resources\Page;

/**
 * Handles routing and pages
 *
 * gathers autoloaded classes from composer and checks route matches against 
 * existing namespaces
 */
class Router
{
    protected const AUTOLOAD_CLASS_CACHE_KEY = 'phntm_autoloaded_classes';

    /**
     * Stores the possible namespaces
     */
    protected array $pages = [];

    /**
     * Stores the static pages
     */
    protected array $staticPages = [];

    /**
     * Stores the dynamic pages
     */
    protected array $dynamicPages = [];

    /**
     * Stores the last popped route part when determining a dynamic route
     */
    protected string $lastCheckedRoutePart = '';

    /**
     * best suited namespace for requested route
     */
    protected string $bestMatch = '';

    /**
     * Parameters to pass to the page
     */
    protected array $params = [];

    /**
     * Determine the composer autoloader, then filter out anything other than 
     * the Pages\\ namespace
     */
    public function __construct()
    {
        Phntm::Profile()->flag("Start Autoload");

        Phntm::Redis();

        $classes = $this->autoload();

        if (empty($classes)) {
            Phntm::Profile()->flag("exec(\"composer dumpautoload --optimize\")");
            exec('composer dumpautoload --optimize');
            $classes = $this->autoload();
        }
        Phntm::Profile()->flag("End Autoload");

        $this->pages = array_keys($classes);


        $this->staticPages = array_filter($this->pages, function($page) {
            return !str_contains($page, "\\_");
        });

        $this->dynamicPages = array_filter($this->pages, function($page) {
            return str_contains($page, "\\_");
        });
    }

    /**
     * Autoloads the classes from composer or cache
     *
     * @returns array<string>
     */
    protected function autoload(): array
    {
        $cachedPages = Phntm::Redis()->get(static::AUTOLOAD_CLASS_CACHE_KEY);
        if ( null === $cachedPages) {
            $res = get_declared_classes();
            $autoloaderClassName = '';
            foreach ( $res as $className) {
                if (strpos($className, 'ComposerAutoloaderInit') === 0) {
                    $autoloaderClassName = $className;
                    break;
                }
            }
            $classLoader = $autoloaderClassName::getLoader();
            $classes = $classLoader->getClassMap();

            $classes = array_filter($classes, function($key) {
                return (strpos($key, "Pages\\") === 0);
            }, ARRAY_FILTER_USE_KEY);

            Phntm::Redis()->set(static::AUTOLOAD_CLASS_CACHE_KEY, serialize($classes), 'EX', 30);
            Phntm::Profile()->flag("Autoloaded classes from composer");
        } else {
            $classes = unserialize($cachedPages);
            Phntm::Profile()->flag("Autoloaded classes from Redis cache");
        }

        return $classes;
    }

    /**
     * Handles the matching process, static routes, then dynamic and 404 if not
     *
     * @param Route $route the given route
     * @returns void
     */
    public function determine(): Page
    {
        $route = Route::fromRequest(Phntm::$request);

        Phntm::Profile()->flag("Start determination");

        if (!$this->matches($route)) {
            Phntm::Profile()->flag("No static route matches found, matching against dynamic routes");
            $this->dynamicMatches($route);
        }

        $matchedPage = $this->match($this->bestMatch, $this->params);

        Phntm::Profile()->flag("End determination");

        return $matchedPage;
    }

    /**
     * Return page list
     *
     * @returns array<string>
     */
    public function getPages(): array
    {
        return $this->pages;
    }

    /**
     * Determines if the route is an exact match with a namespace
     *
     * @returns bool
     */
    public function matches(Route $route): bool
    {
        if (!in_array($route->page(), $this->staticPages)) {
            return false;
        }
        $this->bestMatch = $route->page();
        $this->params = [];

        return true;
    }

    /**
     * Determines if the route is a dynamic match with a variable namespace
     *
     * @returns bool
     */
    public function dynamicMatches(Route $route): bool
    {

        $requestParts = explode('\\', $route->namespace());
        array_shift($requestParts);

        $correctDepthNamespaces = array_values(array_filter($this->dynamicPages, function($page) use ($requestParts) {
            return substr_count($page, '\\') === count($requestParts) + 1;
        }));



        $applicableRoutes = array_map(function($page) {
            $route = [];
            $route['namespace'] = $page;
            $parts = explode('\\', $page);
            array_shift($parts);
            array_pop($parts);

            $route['static_parts'] = array_filter($parts, function($part) {
                return $part[0] !== '_';
            });

            $route['dynamic_parts'] = array_filter($parts, function($part) {
                return $part[0] === '_';
            });

            return $route;
        }, $correctDepthNamespaces);


        usort($applicableRoutes, function($a, $b) {
            return count($a['dynamic_parts']) <=> count($b['dynamic_parts']);
        });

       
        foreach ($applicableRoutes as $possibleRoute) {
            $checkedParts = [];
            foreach ($possibleRoute['static_parts'] as $i => $part) {
                if ($part === $requestParts[$i]) {
                    $checkedParts[$i] = $part;
                }
            }
            foreach ($possibleRoute['dynamic_parts'] as $i => $part) {
                if (!isset($checkedParts[$i])) {
                    $checkedParts[$i] = $requestParts[$i];
                }
            }
            ksort($checkedParts);
            if ($checkedParts === $requestParts) {

                // build the parameters
                $reflectedParams = (new ReflectionClass($possibleRoute['namespace']))->getConstructor()->getParameters();

                $constructorParamIndex = 0;

                $typeSafe = true;
                $safeParams = [];
                foreach ($possibleRoute['dynamic_parts'] as $i => $_) {
                    try {
                        $dynamicParam = new DynamicParameter($requestParts[$i], $reflectedParams[$constructorParamIndex]->getType());
                        $safeParams[] = $dynamicParam->value;
                        $constructorParamIndex++;
                    } catch ( ParameterTypeException $e) {
                        break 2;
                    }
                }

                $this->params = $safeParams;
                $this->bestMatch = $possibleRoute['namespace'];

                return true;
            }
        }

        $this->params = [];
        $this->bestMatch = "Pages\\NotFound";
        return false;
    }

    /**
     * Select the given route
     *
     * @param Route $route the given route
     * @param array $params the parameters to pass to the page
     * @returns bool
     */
    public function match(string $bestMatch, array $params=[]): Page
    {
        if (!empty($params)) {
            Phntm::Profile()->flag("Matched route: $bestMatch, with params: " . implode(', ', $params));
        } else {
            Phntm::Profile()->flag("Matched route: $bestMatch");
        }

        return new $bestMatch(...$params);
    }
}
