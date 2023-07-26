<?php
class Router{
    
    public static function search_dir($dirName) {
        $dir = new FilesystemIterator($dirName);
        while($dir->valid()){
            $page_name = $dir->current()->getFilename();
            if ($dir->current()->isDir()){
                self::search_dir($dir->current()->getPathname());
            } elseif ($dir->current()->getFilename()) {

            }
            
            $path = 'pages/'.$page_name;
            $name = substr($page_name, 0, strpos($page_name, '.php'));
            self::get('/'.$name, $path);
            $dir->next();
        }
    }

    public static function get_pages() {
        self::get('/', 'pages/home.php');

        //$pages = new GlobIterator(__DIR__."/../pages/*.php");
        $pagesDir = new FilesystemIterator(__DIR__."/../pages/");
        while($pagesDir->valid()){
            $page_name = $pages->current()->getFilename();
            if ($pages->current()->isDir()){
            }
            $path = 'pages/'.$page_name;
            $name = substr($page_name, 0, strpos($page_name, '.php'));
            self::get('/'.$name, $path);
            $pages->next();
        }

        self::any('/404','404.php');
        //$page_contents = ob_get_contents();
    }

    public static function get(string $route, string $path_to_include) {
        if ($_SERVER['REQUEST_METHOD'] == 'GET') {
            self::route($route, $path_to_include);
        }
    }

    public static function any($route, $path_to_include) {
        self::route($route, $path_to_include);
    }

    private static function route($route, $path_to_include) {
        // if doesnt have .php extension
        if (!strpos($path_to_include, '.php')) {
            $path_to_include .= '.php';
        }
        if ($route == "/404") {
            include_once __DIR__ . "/../$path_to_include";
            exit();
        }
        $request_url = filter_var($_SERVER['REQUEST_URI'], FILTER_SANITIZE_URL);
        $request_url = rtrim($request_url, '/');
        $request_url = strtok($request_url, '?');
        $route_parts = explode('/', $route);
        $request_url_parts = explode('/', $request_url);


        array_shift($route_parts);
        array_shift($request_url_parts);

        $toRoute = false;
        if ($route_parts[0] == '' && count($request_url_parts) == 0) {
            $toRoute = true;
        } else if ($route_parts[0] === $request_url_parts[0]){
            $toRoute = true;
            array_shift($request_url_parts);
        }
        if ($toRoute) {
            define('PAGE', $path_to_include);
            define('PARAMS', $request_url_parts);
            include_once(__DIR__.'/../header.php');
            include_once __DIR__ . "/../$path_to_include";
            include_once(__DIR__.'/../footer.php');
            exit();
        }
    }
}

