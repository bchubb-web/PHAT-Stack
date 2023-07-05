<?php

function get_params(string $fullPath): array {
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

Router::get('/', 'pages/home.php');

$dir = scandir(__DIR__.'/pages');

if ($dir){
    $pages = array_slice($dir, 2);
    foreach ($pages as $page) {
        $path = 'pages/'.$page;
        $name = substr($page, 0, strpos($page, '.php'));
        Router::get('/'.$name, $path);
        $params = get_params(__DIR__.'/'.$path);
        for($i=0; $i<count($params); $i++){
            $semiRoute = '/'.join('/', array_slice($params, 0, $i+1));
            Router::get('/'.$name.$semiRoute, $path);
        }
    }
}

Router::any('/404','404.php');
