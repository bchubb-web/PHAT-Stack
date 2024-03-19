<?php

namespace bchubbweb\phntm\Resources;

class Page extends Html {

    public array $headers = [];

    public array $assets = [];

    public function __construct()
    {
        parent::__construct();
    }

    public function setContent( $content): void
    {
        $this->content = $content;
    }
    public function render(): void
    {
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

    public function registerAsset(string $asset_url, ?string $type=null): bool
    {
        $tag = '';

        if (!$type) {
            $type = end(explode('.', $asset_url));
        }

        switch ($type) {
            case 'js':
                $tag = "<script src='{$asset_url}'></script>";
                break;
            case 'css':
                $tag = "<link href='{$asset_url}'>";
                break;
        }

        $this->assets[] = $tag;
    }

    public function getAssets(): string 
    {
        return implode('\n', $this->assets);
    }

}
