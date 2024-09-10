<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Config\Config;
use Variables\Provider;
use Utils\PDO;

// Start the session
session_start();

// Load the configuration
$config = Config::getInstance();
$config->load(__DIR__ . '/../');

// Optionaly reset the database
if (isset($RESET_DB) && $RESET_DB) {
    $pdo = PDO::getInstance();
    $pdo->resetDatabase();
}

// Initialize the provider
$provider = Provider::I();

// We fetch every controller in the src/Controller directory.
foreach (glob("src/Controller/*.php") as $filename) {
    $controller = basename($filename, '.php');
    if ($controller === 'AbstractController' || $controller === 'Exception') {
        continue;
    }
    $controller = 'Controller\\' . $controller;
    $c = $controller::getInstance();
}
