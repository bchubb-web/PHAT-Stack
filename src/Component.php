<?php

namespace bchubbweb\phntm;

use Stringable;

use bchubbweb\phntm\Resources\ContentRenderable;

class Component implements Stringable, ContentRenderable
{
    private string $content = "";

    public function __construct()
    {

    }

    public function setContent($content): void
    {
        $this->content = $content;
    }

    public function __toString(): string
    {
       return $this->content;
    }

    public function render(): void
    {
        echo $this->content;
    }
}
