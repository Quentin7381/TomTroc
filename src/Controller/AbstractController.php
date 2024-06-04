<?php

namespace Controller;
use Config\Config;
use Router\Router;

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

    /**
     * Singleton constructor.
     * Calls the initRoutes method to initialize the routes.
     */
    protected function __construct()
    {
        $this->config = Config::getInstance();
        $this->initRoutes();
        self::$instance = $this;
    }

    /**
     * Initialize the routes for the controller.
     */
    protected function initRoutes(){
        $this->router = new Router($this->baseUrl, $this);
    }

    /**
     * Get the instance of the controller.
     */
    public function getInstance()
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
}
