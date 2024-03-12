<?php

namespace bchubbweb\phntm\Resources;

class Page extends Html {

    public array $headers = [];

    public array $assets = [];

    public function __construct()
    {
        parent::__construct();
    }

    public function setContent( $content): void
    {
        $this->content = $content;
    }
    public function render(): void
    {
        // todo echo wrapper, then head, then body, then layout, which inherits 
        // from parent routes
        echo $this->getContent();
    }

}
