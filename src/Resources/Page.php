<?php

namespace bchubbweb\phntm\Resources;

use bchubbweb\phntm\Profiling\Profiler;
use bchubbweb\phntm\Routing\Route;
use bchubbweb\phntm\Phntm;
use bchubbweb\phntm\Resources\Assets\Asset;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;

/**
 * Page class
 *
 * Represents a page in the application
 */
class Page extends Html implements ContentRenderable { 

    public array $headers = [];

    public array $assets = [];

    public ?Layout $layout;

    public ?Route $route;

    protected array $parameters = [];

    protected string $body;

    public function __construct(array $params = [])
    {
        Phntm::Profile()->flag("Instantiating page");

        $this->route = Route::fromNamespace($this::class);

        $this->setPageParams($params);

        $route = Route::fromNamespace($this::class);
        Phntm::Profile()->flag("Start detecting layout");
        $layout = $this->findLayoutRoute($route);
        Phntm::Profile()->flag("Layout found at: " . $layout->layout());
        $this->layout($layout);

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

    public function getBody(): string
    {
        return $this->body;
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
        Phntm::Profile()->flag("Registering " . $asset_url->uri);
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
        $this->inject('profiler', $profiler->getScript() . '<!-- profiler-insert -->');
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
     * set the dynamic parameters for the page
     *
     * @param array $params
     * @returns void
     */
    protected function setPageParams(array $params): void
    {
        Phntm::Profile()->flag("Setting page parameters: " . json_encode($params));
        $this->parameters = $params;
    }

    /**
     * Get the dynamic parameters for the page
     *
     * @returns array
     */
    public function getPageParams(): array
    {
        return $this->parameters;
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

        $layout = new $layoutClass();


        $layout->registerAssets($this->assets);

        $this->body = $layout->getContent();

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

    public function callRequestedMethod(): Response
    {
        $response = new Response();

        $method = Phntm::$request->getMethod();

        Phntm::Profile()->flag("calling $method() on {$this->route->page()} with params: " . json_encode($this->getPageParams()));
        return $this->$method(Phntm::$request, $response, $this->getPageParams());
    }

    /**
     * HTTP methods
     *
     * @param Request $request
     * @param Response $response
     * @param array<mixed> $params
     * @return Response
     */
    public function get(Request $request, Response $response, array $params): Response { }
    public function post(Request $request, Response $response, array $params): Response { }
    public function put(Request $request, Response $response, array $params): Response { }
    public function patch(Request $request, Response $response, array $params): Response { }
    public function delete(Request $request, Response $response, array $params): Response { }

    
    public function injectParameters(array $parameters): void
    {

    }

    public function inject(string $target, string $content): bool
    {
        $count = 0;
        Phntm::Profile()->flag("Injecting $target");
        $this->body = str_replace("<!-- $target /-->", $content, $this->body, $count);
        
        return $count !== 0;
    }

    protected function findLayoutRoute(Route $pageRoute): Route
    {
        if ($pageRoute->hasLayout()) {
            return $pageRoute;
        }

        // negate the loop if the page is the root
        if ($pageRoute->isRoot()) {
            return false;
        }

        $found = false;
        while (!$pageRoute->isRoot() && !$found) {
            $pageRoute = $pageRoute->parent();
            if ($pageRoute->hasLayout()) {
                $found = true;
                break;
            }
        }

        if ($found) {
            return $pageRoute;
        }

        return false;
    }
}
