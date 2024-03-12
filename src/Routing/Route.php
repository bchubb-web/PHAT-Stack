<?php

namespace bchubbweb\phntm\Routing;

class Route 
{
    protected string $route = '';

    public function __construct(string $requestRoute)
    {
        $this->setRoute($requestRoute);
    }

    protected function setRoute(string $route): void
    {
        $this->route = $route;
    }

    public function nameSpace(): string
    {
        $route = "Pages" . $this->route;

        $route = str_replace("//", "/", $route);
        $route = rtrim($route, "/");
        $route = str_replace("/", "\\", $route);


        return $route;
    }

    public function page(): string
    {
        return $this->nameSpace() . "\\Page";
    }

    public function hasLayout(): bool
    {
        return class_exists($this->nameSpace() . "\\Layout");
    }
}
