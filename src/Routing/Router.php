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
class Router
{
    /**
     * Directory routes are stored at
     */
    protected string $root_uri = '';

    /**
     * Stores the possible routes
     */
    public array $routes = [];

    /**
     * The client's url path, split into an array
     */
    public array $client_url_parts = [];

    /**
     * Iterate over the pages directory and store all route options
     *
     * @param string $pages_directory - the base directory to be used
     * @param Api   $api             - api route handler
     */
    public function __construct(string $absolute_path_to_pages_directory)
    {
        
        $this->register_root($absolute_path_to_pages_directory);

        $this->make_client_params();
    }

    /**
     * Define the directory to base routing out of
     *
     * @return bool
     */
    protected function register_root(string $absolute_path_to_directory): bool
    {
        return $this->set_root_uri($absolute_path_to_directory);
    }


    /**
     * Iterate over the pages directory and store all route options
     *
     * @return void
     */
    public function register_routes(string $routes_root_directory): void
    {

        // register the index route
        self::$routes[] = [];

        $itr = new \RecursiveDirectoryIterator($routes_root_directory);
        $files = new \RecursiveIteratorIterator($itr, \RecursiveIteratorIterator::SELF_FIRST);

        foreach ($files as $name => $_) {
            $server_path = explode('inc/../pages', $name)[1];
            $server_parts = explode('/', $server_path);
            array_shift($server_parts);
            // if has the same number of parts
            if (count($server_parts) != count(self::$client_url_parts)) {
                continue;
            }
            // if is an file
            if (!is_dir($name)) {
                continue;
            }
            // if not /. or /..
            if (preg_match('/[\.]$/', $name)) {
                continue;
            }
            // if has a page.php
            if (!file_exists($name . '/page.php')) {
                continue;
            }

            self::register_route($server_path);
        }

        self::$routes = $this->_filter_routes();


        if (in_array(self::$client_url_parts, self::$routes)) {
            self::_route(implode('/', self::$client_url_parts));
        }
        self::$routes = self::_filter_dynamic_routes();

        \Bchubb\Phntm\dump(self::$routes);


    }

    /**
     * Analyse the client url parts
     *
     * @return void
     */
    protected function make_client_params(): void
    {
        // formatting
        $client_url = filter_var($_SERVER['REQUEST_URI'], FILTER_SANITIZE_URL);
        $client_url = rtrim($client_url, '/');
        $client_url = strtok($client_url, '?');
        $client_url_parts = explode('/', $client_url);

        array_shift($client_url_parts);

        $count = count($client_url_parts);
        for ($i = 0; $i < $count; $i++) {
            if (array_key_exists($i, $client_url_parts)) {
                self::$client_url_parts[] = $client_url_parts[$i];
            }
        }
    }

    /**
     * Store the given path into possible routes
     *
     * @param string $server_path - the path to the file on the server
     *
     * @return void
     */
    public static function register_route(string $server_path): void
    {
        $route_parts = explode('/', $server_path);
        array_shift($route_parts);
        self::$routes[] = $route_parts;
    }

    /**
     * Filter out any paths with incorrect path bases
     * excluding the base route
     *
     * @return array - array of filtered possible routes
     */
    private function _filter_routes(): array
    {
        return array_values(
            array_filter(
                $this->routes,
                function ($r) {
                    if (!array_key_exists(0, $r)) {
                        return true;
                    }
                    if ($r[0] == self::$client_url_parts[0]) {
                        return true;
                    }
                    return false;
                }
            )
        );
    }

    /**
     * Filter out any paths without variable parameters
     *
     * @return array - list of possible variable routes
     */
    private static function _filter_dynamic_routes(): array
    {
        return array_values(
            array_filter(
                self::$routes,
                function ($r) {
                    $num_dyn_parts = count(
                        array_filter(
                            $r,
                            function ($r) {
                                return $r[0] == '[' && substr($r, -1) == ']';
                            }
                        )
                    );
                    if ($num_dyn_parts > 0) {
                        return true;
                    }
                    return false;
                }
            )
        );
    }

    /**
     * @returns string
     */
    public function get_root_uri(): string
    {
        return $this->root_uri;
    }

    /**
     * @param $root_uri string
     *
     * @returns bool
     */
    public function set_root_uri(string $root_uri): bool
    {
        if (!is_dir($root_uri)) return false;

        $this->root_uri = $root_uri;
    }

    public function get_routes(): array
    {
        return $this->routes;
    }

    /**
     * @param bchubbweb\phntm\Routing\Route $route
     */
    public function add_route(Route $route): void
    {
        $this->routes[] = $route;
    }

    /**
     * @param array $routes
     */
    public function set_routes(array $routes): void
    {
        $this->routes = $routes;
    }


    /**
     * Filter out any paths without variable parameters
    private static function match_dynamic_routes(): array {
         array_filter(self::$routes, function($r) {
            $num_dyn_parts = count(array_filter($r, function ($r) {
                return $r[0] == '[' && substr($r, -1) == ']';
            }));
            if ($num_dyn_parts > 0) return true;
            return false;
        });
        foreach(self::$routes as $route) {
            $num_dyn_parts = count(array_filter($route, function ($r) {
                return $r[0] == '[' && substr($r, -1) == ']';
            }));

        }

    }
     */

    /**
     * Performs the action of including the desired page
     *
     * @param string $path - path to the server file to include
     *
     * @return void
     */
    private static function _route(string $path): void
    {

        // if doesnt have .php extension
        if (!strpos($path, '/page.php')) {
            $path .= '/page.php';
        }

        //self::$page = end(explode('/',$path_to_include));

        include_once __DIR__ . "/../pages/$path";
        exit();
    }
}
