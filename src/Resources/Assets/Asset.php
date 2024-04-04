<?php

namespace bchubbweb\phntm\Resources\Assets;

use Stringable;
use bchubbweb\phntm\Resources\ContentTypeTrait;

/**
 * Asset class
 *
 * Represents an asset to be included in a page
 */
class Asset implements Stringable {

    use ContentTypeTrait;

    public string $tag = ''; 

    public string $uri = '';

    public string $content = '';

    public function __construct(string $uri)
    {
        $this->tag = $this->generateTag($uri);
    }

    /**
     * Generate the tag for the asset
     *
     * @param string $uri
     * @return string
     */
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
