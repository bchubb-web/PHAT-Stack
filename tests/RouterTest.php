<?php

namespace bchubbweb\phntm\tests;

use bchubbweb\phntm\Routing\Router;
use PHPUnit\Framework\TestCase;

class RouterTest extends TestCase{
    public function testRegister() {

        $directoryPath = '/Users/billychubb/bchubbweb/phntm/testRoutingDirectory/';
        mkdir($directoryPath);


        $router = new Router($directoryPath);

        $this->assertSame('/Users/billychubb/bchubbweb/phntm/testRoutingDirectory/', $router->pages_directory);
        rmdir($directoryPath);
    }
}
