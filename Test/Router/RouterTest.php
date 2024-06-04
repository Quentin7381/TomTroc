<?php

namespace Test\Controller;

require_once __DIR__ . '/../../vendor/autoload.php';

use Controller\Router;
use Controller\Exception;
use Test\Reflection;
use Test\ReflectionInstance;
use Test\TestInit;
use Mockery as m;

abstract class RouterTest extends TestInit{

    public function setUp(): void {
        parent::setUp();

        $this->Router = Reflection::_GET_INSTANCE(Router::class);
    }

    ## __construct
    ### construct check if the root url is unique
    ### construct throws an exception if the root url is not unique
    ### construct links the router to the controller
    ### construct sets the root url
    ### construct adds the router to the list of routers

    ## addRoute
    ### addRoute adds a route to the list of routes
    ### addRoute throws an exception if a route with the same name already exists
    ### addRoute throws an exception if the route is not a string
    ### addRoute throws an exception if the method does not exist in the controller
    ### addRoute prepends the root url to the route and adds it to the list of routes

    ## getRoute
    ### getRoute returns the route if it exists
    ### getRoute returns false if the route does not exist

    ## route
    ### route calls the controller method with the route
    ### route throws an exception if the route does not exist

    ## getRoutes
    ### getRoutes returns the list of routes
    ### getRoutes returns an empty array if there are no routes

    ## getCalledRoute
    ### getCalledRoute returns the called route
    ### getCalledRoute returns false if no route has been called

    ## getRouteParams
    ### getRouteParams returns the route parameters
    ### getRouteParams returns an empty array if there are no parameters
}
