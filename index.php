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
$image->src = 'https://images.ctfassets.net/hrltx12pl8hq/28ECAQiPJZ78hxatLTa7Ts/2f695d869736ae3b0de3e56ceaca3958/free-nature-images.jpg?fit=fill&w=1200&h=630';

$manager = ImageManager::getInstance();
$manager->insert($image);

exit;

$view = View::getInstance();
$view->buildPage();
echo $view->html;
