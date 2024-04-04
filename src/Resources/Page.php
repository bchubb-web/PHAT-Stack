<?php

namespace bchubbweb\phntm\Resources;

use bchubbweb\phntm\Profiling\Profiler;
use bchubbweb\phntm\Routing\Route;
use bchubbweb\phntm\Phntm;
use bchubbweb\phntm\Resources\Assets\Asset;

/**
 * Page class
 *
 * Represents a page in the application
 */
class Page extends Html implements ContentRenderable { 

    public array $headers = [];

    public array $assets = [];

    public ?Layout $layout;

    public function __construct()
    {
        Phntm::Profile()->flag(__NAMESPACE__ . '\Page::__construct()');
        parent::__construct();
    }

    /**
     * Set the content of the page
     *
     * @param string $content
     * @return void
     */
    public function setContent($content): void
    {
        $this->content = $content;
    }

    /**
     * Render the page content via echo
     *
     * @return void
     */
    public function render(): void
    {
        Phntm::Profile()->flag("Rendering page content");

        echo $this->getContent();
    }

    /**
     * Register an asset to the page
     *
     * @param Asset|string $asset_url
     * @param string|null $type
     * @return void
     */
    public function registerAsset(Asset | string $asset_url, ?string $type=null): void
    {
        if (is_string($asset_url)) {
            $asset_url = new Asset($asset_url);
        }
        $this->assets[] = $asset_url;
    }

    /**
     * Register multiple assets to the page
     *
     * @param array<Asset|string> $assets
     * @return void
     */
    public function registerAssets(array $assets): void
    {
        foreach ($assets as $asset) {
            $this->registerAsset($asset);
        }
    }

    /**
     * Inject the profiler script into the page
     *
     * @param Profiler $profiler
     * @return void
     */
    public function registerProfiler(Profiler $profiler): void
    {
        $this->content = str_replace('<!-- profiler /-->', $profiler->getScript() . '<!-- profiler-insert -->', $this->content);
    }

    /**
     * Get the assets for the page as DOM elements
     *
     * @return string
     */
    public function getAssets(): string
    {
        $assets = '';
        foreach ($this->assets as $asset) {
            $assets .= $asset . "\n";
        }
        return $assets . '<!-- head /-->';
    }

    /**
     * Wrap the page content with a layout, registering it's assets to the page
     *
     * @param Route $layoutRoute
     * @return Page
     */
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
