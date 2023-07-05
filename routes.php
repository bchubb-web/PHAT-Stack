<?php
//get('/', 'pages/home.php');
$dir = scandir(__DIR__.'/pages');


function get_params(string $fullPath): bool|array {
    echo 'PATH:'.$fullPath.'<br/>';
    $rawFile = fopen($fullPath, 'r');
    if ($rawFile) {
        $readVars = false;
        $varNames = [];
        if (!feof($rawFile && (trim(fgets($rawFile)) == "<!-- start vars"))){
            while(!feof($rawFile) && !$readVars){
                $line = fgets($rawFile);

                array_push($varNames, trim($line));
                //echo htmlentities($line).'<br/>';

                if (trim($line) == 'end vars -->'){
                    array_pop($varNames);
                    $readVars = true;
                }
            }
        }
        var_dump($varNames);
    }
    
    return false;
}


if ($dir){
    $pages = array_slice($dir, 2);
    foreach ($pages as $page) {
        $name = substr($page, 0, strpos($page, '.php'));
        echo $name.'<br/>';
        $path = 'pages/'.$page;
        //get('/'.$name, $path);
        $params = get_params(__DIR__.'/'.$path);
    }
}

get('/', 'pages/home.php');
/*get('/404', 'pages/404.php');

get('/404/$id', 'pages/404.php');

get('/user', 'pages/user.php');

get('/user/$id', 'pages/user.php');

get('/user/$id/$time', 'pages/user.php');

get('/todo', 'pages/todo.php');

get('/home', 'pages/home.php');
*/
