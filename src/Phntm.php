<?php 

namespace bchubbweb\phntm;

use Laminas\HttpHandlerRunner\Emitter\SapiEmitter;
use bchubbweb\phntm\Resources\Page;
use bchubbweb\phntm\Routing\Router;
use bchubbweb\phntm\Profiling\Profiler;
use Predis\Client;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use League\Container\Container;

final class Phntm
{
    private static ?Router $routerInstance = null;
    private static ?Profiler $profilerInstance = null;
    private static ?Client $predisInstance = null;
    private static ?Container $containerInstance = null;

    public static Request $request;
    public static Response $response;

    public static string $phntm_root;

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

        self::$phntm_root = realpath(__DIR__ . '/../');

        include self::$phntm_root . '/config/Container.php';

        self::$request = new Request($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI'], getallheaders());

        $page = self::Router()->determine();

        $response = $page->callRequestedMethod();

        $page->inject('content', $page->getContent());

        if ($profile) {
            $page->registerProfiler(self::Profile());
        }

        self::Profile()->flag('Building response body');

        $response->getBody()->write($page->getBody());


        (new SapiEmitter())->emit($response);

        self::Profile()->stop();
        //$emitter->emit($response);
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

    public static function Container(): Container
    {
        if (null === self::$containerInstance) {
            self::$containerInstance = new Container();
        }
        return self::$containerInstance;
    }
}
