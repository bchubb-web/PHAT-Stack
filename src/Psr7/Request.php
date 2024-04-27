<?php

namespace bchubbweb\phntm\Psr7;

use GuzzleHttp\Psr7\Request as Psr7Request;
use bchubbweb\phntm\Routing\Route;

class Request extends Psr7Request
{
    protected ?Route $route = null;

    public function getRoute(): Route
    {
        return $this->route;
    }

    public function setRoute(Route $route): Request
    {
        $this->route = $route;

        return $this;
    }

}
