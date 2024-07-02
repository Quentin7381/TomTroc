<?php

require_once 'src/initialize.php';

use Variables\Provider;
use Config\Config;
use Utils\View;
use Entity\Image;
use Manager\ImageManager;

$provider = Provider::I();

$config = Config::getInstance();
$config->load(__DIR__);


$image = new Image();
$image->name = 'test';
$image->extension = 'png';
$image->content = file_get_contents(__DIR__ . '/assets/figma/Mask group-1.png');

$manager = ImageManager::getInstance();
$manager->insert($image);

exit;

$view = View::getInstance();
$view->buildPage();
echo $view->html;
