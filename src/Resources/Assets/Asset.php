<?php

namespace bchubbweb\phntm\Resources\Assets;

use Stringable;
use bchubbweb\phntm\Resources\ContentTypeTrait;

class Asset implements Stringable {

    use ContentTypeTrait;

    public string $tag = ''; 

    public string $uri = '';

    public string $content = '';

    public function __construct(string $uri)
    {
        $this->tag = $this->generateTag($uri);
    }

    public function generateTag(string $uri): string
    {
        $parts = explode('.', $uri);
        $type = end($parts);

        switch ($type) {
            case 'js':
                return "<script src='{$uri}'></script>";
            case 'css':
                return "<link href='{$uri}' rel='stylesheet'>";
            default:
                return '';
        }
    }

    public function __toString(): string
    {
        return $this->tag;
    }
}
