<?php

namespace Utils;

use Config\Config;

class PDO extends \PDO{
    protected static $instance;

    protected function __construct(){
        self::$instance = $this;
        $config = Config::getInstance();

        try {
            parent::__construct(
                'mysql:host=' . $config->DB_HOST . ';dbname=' . $config->DB_NAME,
                $config->DB_USER,
                $config->DB_PASSWORD
            );
        } catch (\PDOException $e) {
            if ($e->getCode() === 1049) {
                self::resetDatabase();
            }

            parent::__construct(
                'mysql:host=' . $config->DB_HOST . ';dbname=' . $config->DB_NAME,
                $config->DB_USER,
                $config->DB_PASSWORD
            );
        }
    }

    public static function getInstance(){
        if(empty(self::$instance)){
            self::$instance = new self();
        }
        return self::$instance;
    }

    public static function resetDatabase(){
        $config = Config::getInstance();
        $database = $config->DB_NAME;
        $sql = "DROP DATABASE IF EXISTS $database;";
        $sql .= "CREATE DATABASE $database;";
        
        $host = $config->DB_HOST;
        $user = $config->DB_USER;
        $password = $config->DB_PASSWORD;
        $pdo = new \PDO("mysql:host=$host", $user, $password);
        $pdo->exec($sql);

        $pdo = self::getInstance();
    }

}
