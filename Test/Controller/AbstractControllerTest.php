<?php

namespace Test\Controller;

require_once __DIR__ . '/../../vendor/autoload.php';

use Controller\AbstractController;
use Controller\Exception;
use Test\Reflection;
use Test\ReflectionInstance;
use Test\TestInit;
use Mockery as m;

abstract class AbstractControllerTest extends TestInit{

    public function setUp(): void {
        parent::setUp();

        $this->AbstractController = Reflection::_GET_INSTANCE(AbstractController::class);
    }

    ## __construct
    ### construct loads the configuration through Config::getInstance
    ### construct initializes the routes through initRoutes
    ### construct sets the instance of the controller

    ## getInstance
    ### getInstance returns the instance of the controller
    ### getInstance creates a new instance of the controller if it does not exist

    ## initRoutes
    ### initRoutes initializes the router object with the base URL
    ### initRoutes throws an exception if the Router initialization fails

    ## init
    ### init loops through all existing controllers and initializes them
    ### init does not initialize the AbstractController

}
