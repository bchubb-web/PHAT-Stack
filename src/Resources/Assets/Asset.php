<?php

namespace bchubbweb\phntm\Resources\Assets;

use bchubbweb\phntm\Resources\ContentTypeTrait;

class Asset {

    use ContentTypeTrait;

    public string $uri = '';

    public string $content = '';

    public function get_content(): string
    {
        return $this->content;
    }

    public function set_content(string|array|object $content): void
    {
        if (is_array($content) || is_object($content)) {
            $content = json_encode($content);
        } else if (is_string($content) && !json_validate($content)) {
            throw new \Exception('Invalid Json');
        }

        $this->content = $content;
    }
}
