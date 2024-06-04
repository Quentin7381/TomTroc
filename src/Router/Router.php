<?php

namespace Router;

use Controller\AbstractController;

/**
 * Class Router
 */
class Router {
    /**
     * Holds all the routes of all the routers.
     * Key is the route, values are the controller and method.
     *
     * @var array
     */
    private static $routes = [];

    /**
     * Holds the root url of the router.
     * This root url is prepended to all routes.
     * The same root url cannot be shared by two routers to avoid conflicts.
     *
     * @var string
     */
    protected $root;

    /**
     * Holds the instance of the related controller.
     *
     * @var \Controller\AbstractController
     */
    protected $controller;

    /**
     * Holds all the routers.
     * Key is the root url, value is the router.
     *
     * @var array
     */
    protected static $routers = [];

    /**
     * Router constructor.
     *
     * @param string $root
     *
     * @throws Exception if a router with the same root url already exists
     */
    public function __construct(string $root, AbstractController $controller){
        // Check if a router with the same root url already exists
        if(isset(self::$routers[$root])){
            throw new Exception("Router with root $root already exists.");
        }

        // Setup the router
        $this->controller = $controller;
        $this->root = $root;
        self::$routers[$root] = $this;
    }

    /**
     * Add a route to the router.
     *
     * @param string $route
     * @param string $method
     *
     * @throws Exception if the route already exists
     * @throws Exception if the method does not exist in the controller
     */
    final public function addRoute($route, $method){
        // Prepend the root url to the route
        $route = $this->root . $route;

        // Check if the route already exists
        if(isset(self::$routes[$route])){
            throw new Exception("Route $route already exists.");
        }

        // Check if the method exists in the controller
        if(!method_exists($this->controller, $method)){
            $controller = get_class($this->controller);
            throw new Exception("Method $method does not exist in controller $controller.");
        }

        // Add the route
        self::$routes[$route] = [
            'controller' => $this->controller,
            'method' => $method
        ];
    }

    /**
     * Get a route.
     *
     * @param string $route
     *
     * @return array|bool the route or false if the route does not exist
     */
    final public function getRoute($route){
        if (isset($this->routes[$route])) {
            return $this->routes[$route];
        }
        
        return false;
    }

    /**
     * Call a route method.
     *
     * @param string $route
     *
     * @throws Exception if the route does not exist
     */
    public function route($route){
        // Get the route
        $route = $this->getRoute($route);

        // Check if the route exists
        if(!$route){
            throw new Exception("Route $route does not exist.");
        }

        // Call the route method
        $this->controller->{$route['method']}();

    }

    /**
     * Get all the routes of all the routers.
     *
     * @return array
     */
    final public function getRoutes(){
        return $this->routes;
    }

    /**
     * Get the called route.
     * Shortcut for url part of $_SERVER['REQUEST_URI'].
     *
     * @return string
     */
    final public static function getCalledRoute(){
        $route = $_SERVER['REQUEST_URI'];
        $route = explode('?', $route);
        $route = $route[0];
        return $route;
    }

    /**
     * Get the route parameters.
     * Shortcut for query part of $_SERVER['REQUEST_URI'].
     *
     * @return array
     */
    final public static function getRouteParams(){
        $route = $_SERVER['REQUEST_URI'];
        $route = explode('?', $route);
        $route = $route[1] ?? '';
        $params = [];
        if($route){
            $route = explode('&', $route);
            foreach($route as $param){
                $param = explode('=', $param);
                $params[$param[0]] = $param[1];
            }
        }
        return $params;
    }
}
