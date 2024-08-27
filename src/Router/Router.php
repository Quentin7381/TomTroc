<?php

namespace Router;

use Controller\AbstractController;

/**
 * Class Router
 */
class Router
{
    /**
     * Holds all the routes of all the routers.
     * Key is the route, values are the controller and method.
     *
     * @var array
     */
    private $routes = [];

    /**
     * Holds all the routers.
     * Key is the root url, value is the router.
     *
     * @var array
     */
    protected static $instance;

    protected function __construct()
    {
        // Prevents instantiation
    }

    public static function getInstance()
    {
        if (!self::$instance) {
            self::$instance = new Router();
        }
        return self::$instance;
    }

    public function getRoutes()
    {
        return $this->routes;
    }

    public function getFullRoutes($routes = null, $prefix = '')
    {
        $routes = $routes ?? $this->routes;

        $fullRoutes = [];
        foreach ($routes as $route => $value) {
            if ($route === '.') {
                $fullRoutes[] = $prefix;
            } else {
                $fullRoutes = array_merge($fullRoutes, $this->getFullRoutes($value, $prefix . '/' . $route));
            }
        }

        return $fullRoutes;
    }

    public function addRoute($url, $method)
    {
        $route = $this->getRouteArray($url);
        $method = \Closure::fromCallable($method);

        $routes = &$this->routes;
        foreach ($route as $key => $r) {
            if ($key === array_key_last($route)) {
                if (isset($routes[$r]['.'])) {
                    throw new Exception(Exception::ROUTE_ALREADY_EXISTS, ['route' => $url]);
                }

                $routes[$r]['.'] = $method;
                break;
            }

            if (!isset($routes[$r])) {
                $routes[$r] = [];
            }
            $routes = &$routes[$r];
        }
    }

    public function getRouteMethod($url)
    {
        $needle = $this->getRouteArray($url);
        $haysac = &$this->routes;

        $args = [];
        foreach ($needle as $n) {
            // Debug
            // var_dump('--- ITERATION ---');
            // var_dump($n);
            // var_dump(array_keys($haysac));
            if (isset($haysac[$n])) {
                $haysac = &$haysac[$n];
            } elseif (isset($haysac['$'])) {
                $haysac = &$haysac['$'];
                $args[] = $n;
            } else {
                throw new Exception(Exception::ROUTE_NOT_FOUND, ['route' => $url]);
            }
        }

        if (!isset($haysac['.'])) {
            throw new Exception(Exception::ROUTE_NOT_FOUND, ['route' => $url]);
        }

        return [$haysac['.'], $args];
    }

    public function route(?string $url = null)
    {
        if (!$url) {
            $url = $this->getCalledRoute();
        }
        [$method, $args] = $this->getRouteMethod($url);
        call_user_func_array($method, $args);

    }

    protected function getRouteArray($route)
    {
        $route = trim($route, '/');
        $route = explode('/', $route);
        return $route;
    }

    public function getCalledRoute()
    {
        $uri = $_SERVER['REQUEST_URI'];
        $uri = explode('?', $uri);
        $uri = $uri[0];
        $uri = trim($uri, '/');
        return $uri;
    }
}
