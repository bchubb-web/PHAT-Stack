<?php

/**
 * @file
 * Provides dynamic route handing
 */

namespace bchubbweb\phntm\Routing;

/**
 * Handles routing and pages
 *
 * Iterates over the pages directory, registers all defined routes, sort and
 * prioritise, before selecting and routing the client
 */
interface RouterInterface
{
    /**
     * Define the directory to base routing out of
     *
     * @return bool
     */
    public function register_root(string $absolute_path_to_pages_directory): bool;


    /**
     * Iterate over the pages directory and store all route options
     *
     * @return void
     */
    public function register_routes(string $routes_root_directory): void;

    /**
     * Store the given path into possible routes
     *
     * @param string $server_path - the path to the file on the server
     *
     * @return void
     */
    public static function register_route(string $server_path): void;

}
