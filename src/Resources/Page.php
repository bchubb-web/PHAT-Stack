<?php

namespace bchubbweb\phntm\Resources;

class Page extends Html {

    public array $headers = [];

    public array $assets = [];

    public function __construct()
    {
        parent::__construct();
    }
}
