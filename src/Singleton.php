<?php

namespace Utils;

class Singleton {

    protected static $instance = null;

    protected function __construct(){
        // This is a protected constructor
    }

    public static function getInstance(){
        if(static::$instance === null){
            static::$instance = new static();
        }
        return static::$instance;
    }

    public static function I(){
        return static::getInstance();
    }

    public static function __callStatic($name, $arguments){
        $instance = static::getInstance();
        return $instance->$name(...$arguments);
    }
}