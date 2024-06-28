<?php

require_once 'vendor/autoload.php';

// use Config\Config;
// use Utils\View;

// $config = Config::getInstance();
// $config->load(__DIR__);

// $view = View::getInstance();
// $view->buildPage();
// echo $view->html;

use Variables\Variables;
use Variables\Provider;
use Variables\Data;

$variables = Variables::I();
$provider = Provider::I();
$data = Data::I();

$provider->set('test', function(){
    return 'Hello world';
});

$provider->set('test2.test3', function(){
    return ['key' => 'Hello world 2'];
});

var_dump($data->getStructure());
var_dump($variables->get('test'));
var_dump($variables->get('test2.test3'));
var_dump($data->getStructure());