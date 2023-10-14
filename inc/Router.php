<?php
/**
 * Handles routing and pages directory
 *
 * Iterates over the pages directory, register all defined routes, sort and 
 * prioritise, before selecting and routing the client
 *
 */
class Router{

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
    */
    public static function register_routes() {
        
        // register the home route
        self::$routes[] = [];

        $dir_itr = new RecursiveDirectoryIterator(__DIR__."/../pages/");
        $entries = new RecursiveIteratorIterator($dir_itr, RecursiveIteratorIterator::SELF_FIRST);

        foreach($entries as $name => $_){
            $server_path = explode('inc/../pages', $name)[1];
            $server_parts = explode('/', $server_path);
            array_shift($server_parts);
            // if has the same number of parts
            if (count($server_parts) != count(self::$client_url_parts)) continue;
            // if is an file
            if (!is_dir($name)) continue;
            // if not /. or /..
            if (preg_match('/[\.]$/', $name)) continue;
            // if has a page.php
            if (!file_exists($name.'/page.php')) continue;

            self::register_route($server_path);
        }

        self::$routes = self::filter_routes();
        
        
        if (in_array(self::$client_url_parts, self::$routes)) {
            self::route(implode('/',self::$client_url_parts));
        }
        self::$routes = self::filter_dynamic_routes();

        dump(self::$routes);
        
        
        /*include_once(__DIR__.'/../header.php');
        dump(self::$client_url_parts);
        include_once(__DIR__.'/../footer.php');*/

    }

    public static function make_client_params() {
        // formatting
        $client_url = filter_var($_SERVER['REQUEST_URI'], FILTER_SANITIZE_URL);
        $client_url = rtrim($client_url, '/');
        $client_url = strtok($client_url, '?');
        $client_url_parts = explode('/', $client_url);

        array_shift($client_url_parts);

        $count = count($client_url_parts);
        for($i=0;$i<$count;$i++) {
            if (array_key_exists($i, $client_url_parts)) {
                self::$client_url_parts[] = $client_url_parts[$i];
            }
        }
    }

    /**
    * Store the given path into possible routes
    */
    public static function register_route(string $server_path): void {
        $route_parts = explode('/', $server_path);
        array_shift($route_parts);
        self::$routes[] = $route_parts;
    }

    /**
    * Filter out any paths with incorrect path bases
    * excluding the base route
    */
    private static function filter_routes(): array {
        return array_values(array_filter(self::$routes, function($r) {
            if (!array_key_exists(0, $r)) return true;
            if ($r[0] == self::$client_url_parts[0]) return true;
            return false;
        }));
    }

    /**
    * Filter out any paths without variable parameters
    */
    private static function filter_dynamic_routes(): array {
        return array_values(array_filter(self::$routes, function($r) {
            $num_dyn_parts = count(array_filter($r, function ($r) {
                return $r[0] == '[' && substr($r, -1) == ']';
            }));
            if ($num_dyn_parts > 0) return true;
            return false;
        }));
    }

    /**
    * Performs the action of including the desired page
    */
    private static function route(string $path_to_include): void {

        // if doesnt have .php extension
        if (!strpos($path_to_include, '/page.php')) {
            $path_to_include .= '/page.php';
        }

        //self::$page = end(explode('/',$path_to_include));
            
        include_once(__DIR__.'/../header.php');
        require_once(__DIR__ . "/../pages/$path_to_include");
        include_once(__DIR__.'/../footer.php');
        exit();
    }
}
