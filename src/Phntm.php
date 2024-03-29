<?php 

namespace bchubbweb\phntm;

use bchubbweb\phntm\Routing\Router;
use bchubbweb\phntm\Profiling\Profiler;

final class Phntm
{
    private static ?Phntm $instance = null;
    private static ?Router $routerInstance = null;
    private static ?Profiler $profilerInstance = null;
    private $config = [];

    private function __construct()
    {

    }

    /**
     * Get the instance of the Phntm class
     *
     * @return Phntm
     */
    public static function getInstance(): Phntm
    {
        if (null === self::$instance) {
            self::$instance = new Phntm();
        }
        return self::$instance;
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
     * Start the profiler, returns false if the profiler is already running
     *
     * @return bool
     */
    public static function Profile(): bool
    {
        if (null === self::$profilerInstance) {
            self::$profilerInstance = new Profiler();
            self::$profilerInstance->start();
            return true;
        }
        return false;
    }
}
