<?php

namespace bchubbweb\phntm\Resources;

trait ComponentTrait
{
    private string $content = "";

    public function __construct()
    {

    }

    public function setContent($content)
    {
        $this->content = $content;
    }

    public function __toString(): string
    {
       return $this->content;
    }
}
