<?php

use Config\Config;

$config = Config::getInstance();

$config->load([
    'db' => [
        'host' => 'mariadb',
        'port' => '3306',
        'name' => getenv('DB_NAME'),
        'user' => getenv('DB_USER'),
        'password' => getenv('DB_PASSWORD')
    ]
]);
