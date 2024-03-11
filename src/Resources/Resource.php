<?php

namespace bchubbweb\phntm\Resources;

use Stringable;

class Resource implements Stringable {

    use ContentTypeTrait;

    public array $headers = [];

    public string $content = '';

    public function __construct(){
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function setContent(string|array|object $content): void
    {
        if (is_array($content) || is_object($content)) {
            $content = json_encode($content);
        } else if (is_string($content) && !json_validate($content)) {
            throw new \Exception('Invalid Json');
        }

        $this->content = $content;
    }

    public function __toString(): string
    {
        $this->get_content();
    }
}
