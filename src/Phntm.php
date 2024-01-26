<?php

/**
 * @Router
 * Provides dynamic route handing
 */

namespace Bchubb\Phntm;

/**
 * Handles routing and pages
 *
 * Iterates over the pages directory, registers all defined routes, sort and
 * prioritise, before selecting and routing the client
 */
class Phntm
{
    protected \Bchubb\Phntm\Api | null $api_handler = null;

    protected \Bchubb\Phntm\Router $route_handler;



    public function __construct(string | bool $api_directory = false)
    {

        if ($api_directory) {
            $this->api_handler = new Api($api_directory);
        }
    }

    /**
     * Instatiates a route handler and registers the pages directory
     *
     * @param string $pages_base_directory - uri to the desired directory
     *
     * @return \Bchubb\Phntm\Router
     */
    public function registerPagesDirectory(string $pages_base_directory): \Bchubb\Phntm\Router
    {

        $this->route_handler = new \Bchubb\Phntm\Router($pages_base_directory, $this->api_handler);

        return $this->route_handler;
    }
}
