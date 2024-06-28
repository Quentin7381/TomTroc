<?php

require_once 'src/initialize.php';

use Variables\Provider;
use Config\Config;
use Utils\View;

$provider = Provider::I();

$config = Config::getInstance();
$config->load(__DIR__);

$view = View::getInstance();
$view->buildPage();
echo $view->html;
