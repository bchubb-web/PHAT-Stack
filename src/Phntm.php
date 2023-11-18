<?php

namespace Bchubb;

class Phntm
{
    protected \Bchubb\Phntm\Api $api_handler;

    protected \Bchubb\Phntm\Router $route_handler;



    public function __construct()
    {
    }

    /**
     * Instatiates a route handler and registers the pages directory
     *
     * @param string $pages_dir - uri to the desired directory
     *
     * @return \Bchubb\Phntm\Router
     */
    public function registerPagesDirectory(string $pages_dir): \Bchubb\Phntm\Router
    {

        if (isset($this->api_handler)) {
            $this->route_handler = new \Bchubb\Phntm\Router($pages_dir, $this->api_handler);
        } else {
            $this->route_handler = new \Bchubb\Phntm\Router($pages_dir);
        }

        return $this->route_handler;
    }
}
