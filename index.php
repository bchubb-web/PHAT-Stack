<?php

require_once 'vendor/autoload.php';

$phntm = new \Bchubb\Phntm\Phntm();

$router = $phntm->registerPagesDirectory('/pages');

$routes = $router->routes();

foreach ($routes as $route) {
    \Bchubb\Phntm\dump($route);
}
