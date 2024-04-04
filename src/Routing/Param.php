<?php

namespace bchubbweb\phntm\Routing;

use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use bchubbweb\phntm\Routing\Route;

/**
 * Param Class
 *
 * Passed to the appropriate method of a page
 */
class Param 
{

    public function __construct(public Request $request, public Response $response, public Route $route)
    {

    }

}


