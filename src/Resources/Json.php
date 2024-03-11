<?php

namespace bchubbweb\phntm\Resources;

class Json extends Resource {

    public array $headers = [];

    public string $content = '';

    public function __construct($content=null)
    {
        $this->setContentType('application/json');

        if (!is_null($content)) {
            $this->setContent($content);
        }
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
}
