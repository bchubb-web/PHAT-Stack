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

    public function asNameSpace(): string
    {
        $route = "Pages" . $this->route . "/Page";

        $route = str_replace("//", "/", $route);
        $route = str_replace("/", "\\", $route);

        return $route;
    }
}
