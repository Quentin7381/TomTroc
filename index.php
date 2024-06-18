<?php

require_once 'vendor/autoload.php';

use Entity\Component;
use Config\Config;

$config = Config::getInstance();
$config->load(__DIR__);

echo Component::page();
