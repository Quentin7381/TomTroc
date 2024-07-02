<?php

namespace Utils;

use Config\Config;

class PDO extends \PDO{
    protected static $instance;

    protected function __construct(){
        self::$instance = $this;
        $config = Config::getInstance();
        parent::__construct(
            'mysql:host=' . $config->DB_HOST . ';dbname=' . $config->DB_NAME,
            $config->DB_USER,
            $config->DB_PASSWORD
        );
    }

    public static function getInstance(){
        if(!self::$instance){
            self::$instance = new self();
        }
        return self::$instance;
    }

}
