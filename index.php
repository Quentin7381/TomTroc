<?php

require_once 'src/initialize.php';

use Config\Config;
use View\View;
use Router\Router;
use Router\Exception as RouterException;

// use Variables\Provider;
// use Variables\Variables;
// use Variables\Data;
// $provider = Provider::I();
// $variables = Variables::I();
// var_dump($provider->getStructure());

$config = Config::I();

$router = Router::getInstance();

try {
    $router->route();
} catch (RouterException $e) {
    if($e->getCode() == RouterException::ROUTE_NOT_FOUND){
        $router->route('error/404');
    }
}

// $view = View::getInstance();
// $view->buildPage();
// echo $view->html;
