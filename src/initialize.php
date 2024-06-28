<?php

require_once __DIR__ . '/../vendor/autoload.php';

// We fetch every controller in the src/Controller directory.
foreach (glob("src/Controller/*.php") as $filename) {
    $controller = basename($filename, '.php');
    if ($controller == 'AbstractController' || $controller == 'Exception') {
        continue;
    }
    $controller = 'Controller\\' . $controller;
    $controller::getInstance();
}
