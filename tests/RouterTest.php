<?php declare(strict_types=1);

namespace Bchubb\Phntm\Tests;

use PHPUnit\Framework\TestCase;


//require_once __DIR__ . '/../inc/Router.php';
use \Bchubb\Phntm\Router;

class RouterTest extends TestCase{
    
    /**
     * @dataProvider makeClientParamsDataProvider
     */
    public function testMakeClientParams($url, $client_params) {

        \Router::make_client_params($url);
        $params = \Router::$client_url_parts;

        $this->assertEquals($client_params, $params);
        
    }

    public static function makeClientParamsDataProvider() {
        return [ 
            ['localhost:8000/test/case/wow?q=string', ['test', 'case', 'wow']],
            ['https://localhost:8000/test/case/wow?q=string', ['test', 'case', 'wow']],
            ['https://localhost:8000/test/case/wow/?q=string', ['test', 'case', 'wow']],
        ];
    }
}

