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

    /**
     * set the markup of the component
     *
     * @param string $markup
     * @return void
     */
    public function setContent($markup): void
    {
        $this->content = $markup;
    }

    /**
     * render the component via echo
     *
     * @return string
     */
    public function __toString(): string
    {
       return $this->content;
    }

    /**
     * render the component directly
     *
     * @return void
     */
    public function render(): void
    {
        echo $this->content;
    }
}
