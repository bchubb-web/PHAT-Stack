<?php

namespace Pages\Api\Profiler;

use bchubbweb\phntm\Resources\Page as PageTemplate;
use bchubbweb\phntm\Phntm;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Psr7\Request;


class Page extends PageTemplate
{
    public function get(Request $request, Response $response, array $params): Response
    {
        $this->setContent(Phntm::Redis()->get('phntm_profiling'));

        return $response;
    }
}
