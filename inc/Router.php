<?php
class Router{
    public static function generatePages() {

        self::get('/', 'pages/home.php');

        $dir = scandir('/pages');

        if ($dir){
            $pages = array_slice($dir, 2);
            foreach ($pages as $page) {
                $path = 'pages/'.$page;
                $name = substr($page, 0, strpos($page, '.php'));
                self::get('/'.$name, $path);
                $params = self::get_params(__DIR__.'/'.$path);
                for($i=0; $i<count($params); $i++){
                    $semiRoute = '/'.join('/', array_slice($params, 0, $i+1));
                    self::get('/'.$name.$semiRoute, $path);
                }
            }
        }
        self::any('/404','404.php');
    }
    private static function get_params($fullPath) {
        $rawFile = fopen($fullPath, 'r');
        $varNames = [];
        if ($rawFile) {
            $readVars = false;
            if (!feof($rawFile) && (trim(fgets($rawFile)) == "<!-- start vars")){
                while(!feof($rawFile) && !$readVars){

                    $line = fgets($rawFile);

                    if (trim($line) != 'end vars -->'){
                        array_push($varNames, trim($line));
                    } else {
                        $readVars = true;
                    }
                }
            }
            fclose($rawFile);
        }
        return $varNames;
    }
    public static function get($route, $path_to_include) {
        if ($_SERVER['REQUEST_METHOD'] == 'GET') {
            self::route($route, $path_to_include);
        }
    }
    public static function post($route, $path_to_include) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            self::route($route, $path_to_include);
        }
    }

    public static function put($route, $path_to_include) {
        if ($_SERVER['REQUEST_METHOD'] == 'PUT') {
            self::route($route, $path_to_include);
        }
    }

    public static function patch($route, $path_to_include) {
        if ($_SERVER['REQUEST_METHOD'] == 'PATCH') {
            self::route($route, $path_to_include);
        }
    }

    public static function delete($route, $path_to_include) {
        if ($_SERVER['REQUEST_METHOD'] == 'DELETE') {
            self::route($route, $path_to_include);
        }
    }

    public static function any($route, $path_to_include) {
        self::route($route, $path_to_include);
    }

    private static function route($route, $path_to_include) {
        $callback = $path_to_include;
        if (!is_callable($callback)) {
            if (!strpos($path_to_include, '.php')) {
                $path_to_include .= '.php';
            }
        }
        if ($route == "/404") {
            include_once __DIR__ . "/$path_to_include";
            exit();
        }
        $request_url = filter_var($_SERVER['REQUEST_URI'], FILTER_SANITIZE_URL);
        $request_url = rtrim($request_url, '/');
        $request_url = strtok($request_url, '?');
        $route_parts = explode('/', $route);
        $request_url_parts = explode('/', $request_url);
        echo '<hr/>';
        var_dump($route_parts);
        echo '<br/>';
        var_dump($request_url_parts);
        echo '<hr/>';
        array_shift($route_parts);
        array_shift($request_url_parts);

        // if is just basic include file
        if ($route_parts[0] == '' && count($request_url_parts) == 0) {
            // Callback function
            include_once __DIR__ . "/../$path_to_include";
            exit();
        }


        echo '<hr/>';
        var_dump($route_parts);
        echo '<br/>';
        var_dump($request_url_parts);
        echo '<hr/>';
        $params = [];

        for ($i = 0; $i < count($route_parts); $i++) {
            $route_part = $route_parts[$i];
            //if route part starts with $
            if (preg_match("/^[$]/", $route_part)) {
                $route_part = ltrim($route_part, '$');
                array_push($params, $request_url_parts[$i]);
                $$route_part = $request_url_parts[$i];
            }/* else if ($route_parts[$i] != $request_url_parts[$i]) {
                return;
            }*/
        }
        include_once __DIR__ . "/../$path_to_include";
        exit();
    }
}

