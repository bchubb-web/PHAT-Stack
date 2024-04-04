<?php 

namespace bchubbweb\phntm;

use bchubbweb\phntm\Resources\Page;
use bchubbweb\phntm\Routing\Router;
use bchubbweb\phntm\Profiling\Profiler;
use Predis\Client;

final class Phntm
{
    private static ?Phntm $instance = null;
    private static ?Router $routerInstance = null;
    private static ?Profiler $profilerInstance = null;
    private static ?Client $predisInstance = null;

    private static ?Page $page = null;

    private function __construct()
    {

    }

    /**
     * Initialize the Phntm application
     *
     * @param boolean $profile
     *
     * @return void
     */
    public static function init(bool $profile=false): void
    {
        if ($profile) {
            self::Profile();
        }
        $router = self::Router();

        $route = $router::getRequestedRoute();

        $page = $router->determine($route);

        if ($profile) {
            $page->registerProfiler(self::Profile());
        }

        echo $page->render();
        self::Profile()->stop();
    }

    /**
     * Get the router instance
     *
     * @return Router
     */
    public static function Router(): Router
    {
        if (null === self::$routerInstance) {
            self::$routerInstance = new Router();
        }
        return self::$routerInstance;
    }
    /**
     * Start the profiler, or return the existing instance
     *
     * @return Profiler
     */
    public static function Profile(): Profiler
    {
        if (null === self::$profilerInstance) {
            self::$profilerInstance = new Profiler();
            self::$profilerInstance->start();
        }
        return self::$profilerInstance;
    }

    /**
     * return the Predis singleton instance
     *
     * @return Client
     */
    public static function Redis(): Client
    {
        if (null === self::$predisInstance) {
            self::$predisInstance = new Client();
        }
        return self::$predisInstance;
    }

    /**
     * Set the page instance -- unused 
     *
     * @param Page $page
     *
     * @return void
     */
    public static function Page(Page $page): void
    {
        self::$page = $page;
    }
}
