<?php

require_once 'src/initialize.php';

use Config\Config;
use View\View;
use Router\Router;

// use Variables\Provider;
// use Variables\Variables;
// $provider = Provider::I();
// $variables = Variables::I();
// var_dump($provider->getStructure());
// var_dump($variables->image_get);

$config = Config::I();

$router = Router::getInstance();

$router->route();

// $view = View::getInstance();
// $view->buildPage();
// echo $view->html;
