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

    public static function fromNamespace(string $namespace): Route
    {
        $route = str_replace("Pages", "", $namespace);
        $route = str_replace("\\", "/", $route);
        return new Route($route);
    }

    protected function setRoute(string $route): void
    {
        $this->route = str_replace('//', '/', $route);
    }

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

    public function parentNamespace(): string
    {
        $thisNamespace = $this->namespace();
        $parts = explode("\\", $thisNamespace);
        array_pop($parts);
        return implode("\\", $parts);
    }

    public function parent(): Route
    {
        return Route::fromNamespace($this->parentNamespace());
    }

    public function isRoot(): bool
    {
        return $this->namespace() === "Pages";
    }

    public function page(): string
    {
        return $this->namespace() . "\\Page";
    }

    public function layout(): string
    {
        return $this->namespace() . "\\Layout";
    }

    public function hasLayout(): bool
    {
        return class_exists($this->layout());
    }

    public function __toString(): string
    {
        return $this->route;
    } 
}
