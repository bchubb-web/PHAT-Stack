<?php

namespace bchubbweb\phntm\Resources;

use bchubbweb\phntm\Profiling\Profiler;
use bchubbweb\phntm\Routing\Route;
use bchubbweb\phntm\Phntm;
use bchubbweb\phntm\Resources\Assets\Asset;

class Page extends Html implements ContentRenderable { 

    public array $headers = [];

    public array $assets = [];

    public ?Layout $layout;

    public function __construct()
    {
        Phntm::Profile()->flag(__NAMESPACE__ . '\Page::__construct()');
        parent::__construct();
    }

    public function setContent( $content): void
    {
        $this->content = $content;
    }
    public function render(): void
    {
        Phntm::Profile()->flag("Rendering page content");

        echo $this->getContent();
    }

    public function registerAsset(Asset | string $asset_url, ?string $type=null): void
    {
        if (is_string($asset_url)) {
            $asset_url = new Asset($asset_url);
        }
        $this->assets[] = $asset_url;
    }

    public function registerAssets(array $assets): void
    {
        foreach ($assets as $asset) {
            $this->registerAsset($asset);
        }
    }

    public function registerProfiler(Profiler $profiler): void
    {
        $profilerAssets = new Asset($profiler->getScript());
        $this->content = str_replace('<!-- profiler /-->', $profilerAssets . '<!-- profiler-insert -->', $this->content);
    }

    public function getAssets(): string
    {
        $assets = '';
        foreach ($this->assets as $asset) {
            $assets .= $asset . "\n";
        }
        return $assets . '<!-- head /-->';
    }

    public function layout(Route $layoutRoute): Page
    {
        $layoutClass = $layoutRoute->layout();

        $this->layout = new $layoutClass();

        $this->layout->registerAssets($this->assets);

        $this->wrapContent($this->layout->getWrap());

        return $this;
    }


    /**
     * Wrap the content with the layout before and after content
     *
     * @param array<string> $parts
     * @return void
    */
    public function wrapContent(array $parts): void
    {
        $content = $parts[0];
        $content .= $this->getContent();
        $content .= $parts[1];
        $this->setContent($content);
    }
}
