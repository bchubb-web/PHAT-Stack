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

    protected function setRoute(string $route): void
    {
        $this->route = str_replace('//', '/', $route);
    }

    public function nameSpace(): string
    {
        $route = "Pages" . $this->route;

        $route = str_replace("//", "/", $route);
        $route = rtrim($route, "/");
        $route = str_replace("/", "\\", $route);


        return $route;
    }

    public function parentNamespace(): string
    {
        $thisNamespace = $this->nameSpace();
        $parts = explode("\\", $thisNamespace);
        array_pop($parts);
        return implode("\\", $parts);
    }

    public function page(): string
    {
        return $this->nameSpace() . "\\Page";
    }

    public function hasLayout(): bool
    {
        return class_exists($this->nameSpace() . "\\Layout");
    }

    public function hasDynamic(): bool
    {
        return class_exists($this->nameSpace() . "\\DynamicRoute");
    }

    public function dynamicPage(): string
    {
        return $this->parentNamespace() . "\\DynamicRoute";
    }

    public function __toString(): string
    {
        return $this->route;
    } 
}
