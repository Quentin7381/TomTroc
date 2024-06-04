<?php

namespace Utils;

use Config\Config;

class PDO extends \PDO{
    protected static $instance;

    protected function __construct(){
        self::$instance = $this;
        $config = Config::getInstance();
        parent::__construct(
            'mysql:host=' . $config->db['host'] . ';port=' . $config->db['port'] . ';dbname=' . $config->db['name'],
            $config->db['user'],
            $config->db['password']
        );
    }

    public static function getInstance(){
        if(!self::$instance){
            self::$instance = new self();
        }
        return self::$instance;
    }

}
