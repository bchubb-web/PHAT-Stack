<?php

/**
 * @file Router.php
 * Provides dynamic route handing
 */

namespace bchubbweb\phntm\Routing;

use ReflectionClass;

/**
 * Handles routing and pages
 *
 * gathers autoloaded classes from composer and checks route matches against 
 * existing namespaces
 */
class Router
{
    /**
     * Stores the possible namespaces
     */
    protected array $pages = [];

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
        exec('composer dumpautoload --optimize');
        $res = get_declared_classes();
        $autoloaderClassName = '';
        foreach ( $res as $className) {
            if (strpos($className, 'ComposerAutoloaderInit') === 0) {
                $autoloaderClassName = $className; // ComposerAutoloaderInit323a579f2019d15e328dd7fec58d8284 for me
                break;
            }
        }
        $classLoader = $autoloaderClassName::getLoader();
        $classes = $classLoader->getClassMap();

        $classes = array_filter($classes, function($key) {
            return (strpos($key, "Pages\\") === 0);
        }, ARRAY_FILTER_USE_KEY);

        $this->pages = array_keys($classes);
    }

    /**
     * Handles the matching process, static routes, then dynamic and 404 if not
     *
     * @param Route $route the given route
     * @returns void
     */
    public function determine(Route $route): void
    {
        if (!$this->matches($route)) {
            $this->dynamicMatches($route);
        }

        $this->match($this->bestMatch, $this->params);
    }

    /**
     * Return page list
     *
     * @returns string[]
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
        if (str_contains($route->route, '/_')) {
            echo 'nope';
            return false;
        }
        if (!in_array($route->page(), $this->pages)) {
            return false;
        }
        $this->bestMatch = $route->page();
        $this->params = [];

        echo 'best match: ' . $this->bestMatch;
        return true;
    }

    /**
     * Determines if the route is a dynamic match with a variable namespace
     *
     * @returns bool
     */
    public function dynamicMatches(Route $route): bool
    {
        //$parts = explode('/', $route);
        $parts = explode('\\', $route->nameSpace());
        array_shift($parts);
        $routeDepth = count($parts);

        for ( $i=$routeDepth-1; $i>=0; $i-- ) {
            //remove and store the current namespace part
            $this->lastCheckedRoutePart = array_pop($parts);


            // filter possible namespaces from the pages array
            $filtered = array_filter($this->pages, function($page) use ($parts, $route) {

                // current namespace without dynamic parameter
                $currentNs = "Pages\\" . implode("\\", $parts);

                // current number of parts in the namespace matches the current page
                $filteredPageDepth = substr_count($page, '\\');
                // add 2, one for the Pages\ and one for the Page
                $thisDepth = count($parts) + 2;

                return ($filteredPageDepth === $thisDepth && substr($page, 0, strlen($currentNs)) === $currentNs );
            });

            // reset indexes and select first
            $filtered = array_values($filtered)[0];

            if (!class_exists( $filtered )) {
                continue;
            }

            $refelctedClass = new ReflectionClass( $filtered );
            $param = $refelctedClass->getConstructor()->getParameters()[0];

            $dynamicParam = new DynamicParameter($this->lastCheckedRoutePart, $param->getType());
            $this->params[] = $dynamicParam->value;
            $this->bestMatch = $filtered;
            return true;
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
    public function match(string $bestMatch, array $params=[]): void
    {
        $pageClass = new $bestMatch(...$params);
        $pageClass->render();
    }

    public static function getRequestedRoute(): Route 
    {
        $uri = explode('?', $_SERVER['REQUEST_URI'])[0];
        $uriParts = explode('/', $uri);

        $uriFormatted = '/' . implode('/', 
            array_map(function ($part) {
                return ucfirst($part);
            }, $uriParts));

        $route = new Route($uriFormatted);

        return $route;
    }

    /*public function notFound(): void
    {
        $notFound = "Pages\\NotFound";
        $pageClass = new $notFound;

        $pageClass->render();
    }*/
}
