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
class Router
{
    /**
     * Stores the possible routes
     */
    public string $pages_directory = '';

    /**
     * Stores the possible routes
     */
    public static array $routes = [];

    /**
     * The client's url path, split into an array
     */
    public static array $client_url_parts = [];

    /**
     * The deepest folder name in the path
     */
    public static string $page = "";

    /**
     * Iterate over the pages directory and store all route options
     *
     * @param string $pages_directory - the base directory to be used
     * @param Api | null  $api - api route handler
     */
    public function __construct(string $pages_directory, Api | null $api = null)
    {
        $this->pages_directory = $_SERVER["DOCUMENT_ROOT"] . $pages_directory;

        $this->makeClientParams();

        if ($api !== null && in_array('phntm-api', self::$client_url_parts) === 0) {
            header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
            header("Cache-Control: post-check=0, pre-check=0", false);
            header("Pragma: no-cache");

            $api::getApiRoutes();
        }

        self::registerRoutes($this->pages_directory);
    }

    /**
     * Iterate over the pages directory and store all possible routes
     *
     * @return void
     */
    public function registerRoutes(string $pages_directory): void
    {

        self::$routes[] = [];

        $itr = new \RecursiveDirectoryIterator($pages_directory);
        $files = new \RecursiveIteratorIterator($itr, \RecursiveIteratorIterator::SELF_FIRST);

        foreach ($files as $name => $_) {
            $server_path = explode($pages_directory, $name)[1];
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

            self::registerRoute($server_path);
        }

        self::$routes = $this->filterRoutes();


        if (in_array(self::$client_url_parts, self::$routes)) {
            self::route(implode('/', self::$client_url_parts));
        }
        self::$routes = self::filterDynamicRoutes();
    }

    /**
     * Return list of all Routes
     *
     * @return array
     */
    public static function routes(): array
    {
        return self::$routes;

    }

    /**
     * Analyse the client url parts
     *
     * @return void
     */
    public function makeClientParams(): void
    {
        // formatting
        $client_url = filter_var($_SERVER['REQUEST_URI'], FILTER_SANITIZE_URL);
        $client_url = ltrim($client_url, 'https://');
        $client_url = rtrim($client_url, '/');
        $client_url = strtok($client_url, '?');
        $client_url_parts = explode('/', $client_url);

        //remove hostname from path
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
    public static function registerRoute(string $server_path): void
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
    private function filterRoutes(): array
    {
        return array_values(
            array_filter(
                self::$routes,
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
    private static function filterDynamicRoutes(): array
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
    private static function route(string $path): void
    {

        // if doesnt have .php extension
        if (!strpos($path, '/page.php')) {
            $path .= '/page.php';
        }

        //self::$page = end(explode('/',$path_to_include));

        include_once __DIR__ . "/../pages$path";
        exit();
    }
}
