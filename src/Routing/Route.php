<?php

namespace bchubbweb\phntm\Routing;

use Stringable;

class Route implements Stringable
{
    public string $route = '';

    public function __construct(string $requestRoute)
    {
        $this->setRoute($requestRoute);
    }

    /**
     * Create a Route object from a namespace
     *
     * @param string $namespace
     *
     * @return Route
     */
    public static function fromNamespace(string $namespace): Route
    {
        $route = str_replace("Pages", "", $namespace);
        $route = str_replace("\\", "/", $route);
        return new Route($route);
    }

    /**
     * Set the Route from a uri
     *
     * @param string $route
     *
     * @return void
     */
    protected function setRoute(string $route): void
    {
        $this->route = str_replace('//', '/', $route);
    }

    /**
     * Get the namespace of the route
     *
     * @return string
     */
    public function namespace(): string
    {
        $route = "Pages" . $this->route;

        $route = str_replace("//", "/", $route);
        $route = rtrim($route, "/");
        $route = str_replace("/", "\\", $route);

        if (str_ends_with($route, "\\Page")) {
            $route = substr($route, 0, -5);
        }


        return $route;
    }

    /**
     * Get th namespace of the route's parent
     *
     * @return string
     */
    public function parentNamespace(): string
    {
        $thisNamespace = $this->namespace();
        $parts = explode("\\", $thisNamespace);
        array_pop($parts);
        return implode("\\", $parts);
    }

    /**
     * Get the parent Route
     *
     * @return Route
     */
    public function parent(): Route
    {
        return Route::fromNamespace($this->parentNamespace());
    }

    /**
     * Check if the route is the site root
     *
     * @return bool
     */
    public function isRoot(): bool
    {
        return $this->namespace() === "Pages";
    }

    /**
     * Get the Page class
     *
     * @return string
     */
    public function page(): string
    {
        return $this->namespace() . "\\Page";
    }

    /**
     * Get the Layout class
     *
     * @return bool
     */
    public function layout(): string
    {
        return $this->namespace() . "\\Layout";
    }

    /**
     * Check if the route has a direct layout
     *
     * @return bool
     */
    public function hasLayout(): bool
    {
        return class_exists($this->layout());
    }

    public function __toString(): string
    {
        return $this->route;
    } 
}
