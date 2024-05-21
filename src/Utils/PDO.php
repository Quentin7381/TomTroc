<?php

namespace Utils;

use Config\Config;

class PDO extends \PDO{

    public function __construct(){
        $config = Config::getInstance();
        parent::__construct(
            'mysql:host=' . $config->db['host'] . ';port=' . $config->db['port'] . ';dbname=' . $config->db['name'],
            $config->db['user'],
            $config->db['password']
        );
    }

}
