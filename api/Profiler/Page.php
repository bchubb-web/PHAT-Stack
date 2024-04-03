<?php

namespace Pages\Api\Profiler;

use bchubbweb\phntm\Resources\Page as PageTemplate;

class Page extends PageTemplate
{
    public function __construct()
    {
        $this->setContent('{"error":"Page not found"}');
    }
}
