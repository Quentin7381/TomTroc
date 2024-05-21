<?php

namespace Utils;

/**
 * Singleton class
 *
 * Manage the singleton instance creation
 * Allow to access the singleton instance methods statically
 */
class Singleton {

    /**
     * Singleton instance
     *
     * @var Singleton
     */
    protected static $instance = null;

    /**
     * Constructor
     */
    protected function __construct(){
        // This is a protected constructor
    }

    /**
     * Get the singleton instance
     *
     * @return Singleton
     */
    public static function getInstance(){
        if(static::$instance === null){
            static::$instance = new static();
        }
        return static::$instance;
    }

    /**
     * shortcut to getInstance
     *
     * @param string $name
     * @param array $arguments
     * @return mixed
     */
    public static function I(){
        return static::getInstance();
    }

    /**
     * Transofrm the static call to an instance call
     *
     * @param string $name
     * @param array $arguments
     * @return mixed
     */
    public static function __callStatic(string $name, array $arguments){
        $instance = static::getInstance();
        return $instance->$name(...$arguments);
    }
}
