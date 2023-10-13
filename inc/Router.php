<?php
/**
 * Handles routing and pages directory
 *
 * Iterates over the pages directory, register all defined routes, sort and 
 * prioritise, before selecting and routing the client
 *
 */
class Router{

    public static $routes = [];
    private static $tree = [];
    
    /**
    * Iterate over the pages directory and store all route options
    */
    public static function register_routes() {
        
        self::get('/', 'pages/page.php');

        $dir_itr = new RecursiveDirectoryIterator(__DIR__."/../pages/");
        $entries = new RecursiveIteratorIterator($dir_itr, RecursiveIteratorIterator::SELF_FIRST);

        foreach($entries as $name => $_){
            if (is_dir($name) && !preg_match('/[\.]$/', $name) && file_exists($name.'/page.php') ) {
                $page_path = explode('pages', $name)[1];
                self::get($page_path, 'pages'.$page_path.'/page.php');
            }
        }
        self::any('/404','404.php');


        self::build_tree();
        
        $path_to_include = 'page';
        $params = [];

        define('PAGE', $path_to_include);
        define('PARAMS', $params);
        include_once(__DIR__.'/../header.php');
        dump(self::$tree);
        include_once(__DIR__.'/../footer.php');

        self::prioritise_routes();

    }

    public static function make_params(array $server_path, array $client_path): array {
        
        $params = [];
        
        foreach ($server_path as $i => $server_part) {
            $client_part = $client_path[$i];
            if ($server_part == $client_part) {
                // server part is the same as client part
            } else if (substr($server_part, -1, 1) === '}' && substr($server_part, 0, 1) === '{') {
                $params[substr($server_part, 1, -1)] = $client_part;
            }
        }
        return $params;
    }

    public static function get(string $route, string $path_to_include) {
        if ($_SERVER['REQUEST_METHOD'] == 'GET') {
            self::register_route($route, $path_to_include);
        }
    }

    public static function any($route, $path_to_include) {
        self::register_route($route, $path_to_include);
    }

    public static function register_route($route, $path_to_include) {
        $route_parts = explode('/', $route);
        array_shift($route_parts);
        self::$routes[] = $route_parts;
    }

    /**
    * Select the most likely route from the given set of instantiated endpoints
    */
    private static function determine_route() {

        foreach(self::$routes as $i => $route) {
            
        }
    }

    /**
    * Re-order routes to prioritise static, unvariablised routes
    */
    private static function prioritise_routes() {

    }

    /**
    * Performs the action of including the desired page
    */
    private static function route($route, $path_to_include) {

        // if doesnt have .php extension
        if (!strpos($path_to_include, '.php')) {
            $path_to_include .= '.php';
        }

        // 404
        if ($route == "/404") {
            /*include_once __DIR__ . "/../$path_to_include";
            exit();*/
        }
        
        // formatting
        $request_url = filter_var($_SERVER['REQUEST_URI'], FILTER_SANITIZE_URL);
        $request_url = rtrim($request_url, '/');
        $request_url = strtok($request_url, '?');
        $route_parts = explode('/', $route);
        $request_url_parts = explode('/', $request_url);

        if (sizeof($route_parts) !== sizeof($request_url_parts)) {
            return false;
        }

        array_shift($route_parts);
        array_shift($request_url_parts);

        /*echo '<pre>'; var_dump($route_parts); echo '</pre><br><pre>'; var_dump($request_url_parts); echo '</pre><hr>';*/

        $params = self::make_params($route_parts, $request_url_parts);

        $toRoute = false;
        if ($route_parts[0] == '' && count($request_url_parts) == 0) {
            $toRoute = true;
        } else if ($route_parts[0] === $request_url_parts[0]){
            $toRoute = true;
            array_shift($request_url_parts);
        }


        if ($toRoute) {
            define('PAGE', $path_to_include);
            define('PARAMS', $params);
            /*include_once(__DIR__.'/../header.php');
            include_once __DIR__ . "/../$path_to_include";
            include_once(__DIR__.'/../footer.php');
            exit();*/
        }
    }

    private static function build_tree() {
        $temp_routes = self::$routes;
        $tree = [ ];
        foreach ($temp_routes as $parts) {
            // set current node to root of tree, work down each time
            $node = &$tree;
            foreach ( $parts as $level )   {
                // find if node exists
                $newNode = array_search ($level, array_column($node, "name")??[]);
                if ( $newNode === false ) {
                    // if not, create new node
                    $newNode = array_push( $node, [ "name" => $level, "children" => []]) -1;
                }
                // set new current node to the children of the current node
                $node = &$node[$newNode]["children"];
            }
        }
        self::$tree = $tree;
    }

    private static function sort_level($level) {
        dump($level);
    }
}

