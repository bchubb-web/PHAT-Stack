<?php

namespace bchubbweb\phntm\Resources;

use bchubbweb\phntm\Resources\Page;

/**
 * Layout class
 *
 * Represents a layout to wrap pages
 */
class Layout extends Page
{
    public string $layoutContent = '<!-- content /-->';
    protected string $before = '';
    protected string $after = '';

    /**
     * Set the content of the layout
     *
     * @param string $content
     * @return void
     * @throws \Exception
     */
    public function setContent( $content ): void
    {
        if (strpos($content, '<!-- content /-->') === false) {
            throw new \Exception('Layout does not contain <!-- content /--> tag.');
        }

        $this->layoutContent = $content;

        $halves = explode('<!-- content /-->', $content);
        $this->before = $halves[0];
        $this->after = $halves[1];
    }

    /**
     * Get the layout before the content
     *
     * @return string
     */
    public function before(): string
    {
        $this->before = str_replace('<!-- head /-->', $this->getAssets(), $this->before);
        return $this->before;
    }

    /**
     * Get the layout after the content
     *
     * @return string
     */
    public function after(): string
    {
        return $this->after;
    }

    /**
     * Get the content parts before and after the <!-- content /--> tag
     *
     * @return array<string>
     */ 
    public function getWrap(): array
    {
        return [$this->before(), $this->after()];
    }




}
