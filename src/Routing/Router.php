<?php

/**
 * @file Router.php
 * Provides dynamic route handing
 */

namespace bchubbweb\phntm\Routing;

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
        if ($this->matches($route)) {
            $this->match($route);
        } else if ($this->matchesVar($route)) {
            
        } else {
            $this->notFound();
        }
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
        return in_array($route->page(), $this->pages);
    }

    /**
     * Determines if the route is a dynamic match with a variable namespace
     *
     * @returns bool
     */
    public function matchesVar(Route $route): bool
    {
        return false;
    }

    /**
     * Select the given route
     *
     * @returns bool
     */
    public function match(Route $route): void
    {
        $class = $route->page();
        $pageClass = new $class;

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

    public function notFound(): void
    {
        $notFound = "Pages\\NotFound";
        $pageClass = new $notFound;

        $pageClass->render();
    }
}
