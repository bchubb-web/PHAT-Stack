<?php

namespace Pages\Api\Profiler;

use bchubbweb\phntm\Resources\Page as PageTemplate;
use bchubbweb\phntm\Phntm;
use bchubbweb\phntm\Routing\Param;
use GuzzleHttp\Psr7\Response;

class Page extends PageTemplate
{
    public function get(Param $param): void
    {
        $this->setContent(Phntm::Redis()->get('phntm_profiling'));
    }
}
