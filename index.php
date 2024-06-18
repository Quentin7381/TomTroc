<?php

require_once 'vendor/autoload.php';

use Config\Config;
use Utils\View;

$config = Config::getInstance();
$config->load(__DIR__);

$view = View::getInstance();
$view->buildPage();
echo $view->html;
