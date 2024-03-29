<?php

namespace bchubbweb\phntm\Resources;

use bchubbweb\phntm\Profiling\Profiler;

class Page extends Html {

    public array $headers = [];

    public array $assets = [];

    public function __construct()
    {
        Profiler::flag(__NAMESPACE__ . '\Page::__construct()');
        parent::__construct();
    }

    public function setContent( $content): void
    {
        $this->content = $content;
    }
    public function render(): void
    {
        Profiler::flag(__NAMESPACE__ . '\Page::render()');
        $assets = $this->getAssets();
        $body = $this->getContent();
        echo <<<HTML
        <html>
            <head>
                $assets
            </head>
            <body>
                $body
            </body>
        </html>
        HTML;
    }

    public function registerAsset(string $asset_url, ?string $type=null): void
    {
        $tag = '';

        if (!$type) {
            $parts = explode('.', $asset_url);
            $type = end($parts);
        }

        switch ($type) {
            case 'js':
                $tag = "<script src='{$asset_url}'></script>";
                break;
            case 'css':
                $tag = "<link href='{$asset_url}' rel='stylesheet'>";
                break;
        }

        $this->assets[] = $tag;
    }

    public function getAssets(): string 
    {
        return implode('\n', $this->assets);
    }

}
