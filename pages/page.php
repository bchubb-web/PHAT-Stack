<?php
echo 'page';
$routes = \Bchubb\Phntm\Router::routes();

foreach ($routes as $route) {
    var_dump($route);
}
