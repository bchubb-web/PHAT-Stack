<?php

namespace bchubbweb\phntm\Routing;

class Router
{
    protected array $pages = [];

    public function __construct()
    {
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

    public function determine(Route $route): void
    {
        if ($this->matches($route)) {
            $this->match($route);
        } else if ($this->matchesVar($route)) {
            
        } else {
            $this->notFound();
        }
    }

    public function getPages(): array
    {
        return $this->pages;
    }

    public function matches(Route $route): bool
    {
        return in_array($route->page(), $this->pages);
    }

    public function matchesVar(Route $route): bool
    {
        return false;
    }

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
