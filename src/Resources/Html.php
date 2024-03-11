<?php

namespace bchubbweb\phntm\Resources;

class Html extends Resource {

    public array $headers = [];

    public function __construct()
    {
        $this->setContentType('text/html');
    }
}
