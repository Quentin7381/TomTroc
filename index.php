<?php

require_once 'src/initialize.php';

use Variables\Provider;
use Config\Config;
use View\View;
use Entity\Image;
use Manager\ImageManager;

$view = View::getInstance();
$view->buildPage();
echo $view->html;
