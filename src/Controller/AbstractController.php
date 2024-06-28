<?php

namespace Controller;
use Config\Config;
use Router\Router;
use Variables\Provider;

/**
 * AbstractController class
 *
* This class is the base class for all controllers.
*/

abstract class AbstractController
{
    /**
     * @var Config $config
     * This object holds the configuration values.
     */
    protected $config;

    /**
     * @var AbstractController $instance
     * This object holds the instance of the controller.
     */
    protected static $instance;

    /**
     * @var string $baseUrl
     * This string holds the base URL used for the router.
     * Two controllers cannot share the same base URL to avoid conflicts.
     */
    protected $baseUrl = '';

    /**
     * @var Router $router
     * This object holds the router object.
     */
    protected $router;

    protected Provider $provider;
    protected $controllerName;

    /**
     * Singleton constructor.
     * Calls the initRoutes method to initialize the routes.
     */
    protected function __construct()
    {
        $this->config = Config::getInstance();
        $this->router = new Router($this->baseUrl, $this);
        $this->provider = Provider::getInstance();
        $this->controllerName = $this->getControllerName();
        $this->initRoutes();
        $this->initProviders();
        self::$instance = $this;
    }

    /**
     * Initialize the routes for the controller.
     */
    abstract protected function initRoutes();

    /**
     * Get the instance of the controller.
     */
    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new static();
        }
        return self::$instance;
    }

    /**
     * Initialize the controller and its child classes.
     */
    public static function init($name)
    {
        foreach (glob("src/Controller/*.php") as $filename) {
            $controller = basename($filename, '.php');
            if ($controller == 'AbstractController') {
                continue;
            }
            $controller = 'Controller\\' . $controller;
            $controller::getInstance();
        }
    }

    public function getControllerName()
    {
        $name = get_class($this);
        $name = str_replace('Controller\\', '', $name);
        $name = str_replace('Controller', '', $name);
        return strtolower($name);
    }

    protected function initProviders(){
        $classMethods = get_class_methods($this);
        foreach ($classMethods as $method) {
            if (strpos($method, 'provider_') === 0) {
                $key = str_replace('provider_', '', $method);
                $key = str_replace('_', '.', $key);
                $key = $this->controllerName . '.' . $key;
                $this->provider->set($key, [$this, $method]);
            }
        }
    }
}
