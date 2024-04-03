<?php

namespace Pages\Api\Profiler;

use bchubbweb\phntm\Resources\Page as PageTemplate;
use bchubbweb\phntm\Phntm;

class Page extends PageTemplate
{
    public function __construct()
    {
        $this->setContent(Phntm::Redis()->get('phntm_profiling'));
    }
}
