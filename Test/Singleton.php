<?php

namespace Test;

abstract class Singleton{

    public static $I;

    protected function __construct()
    {
        static::$I = $this;
    }

    public static function I()
    {
        return empty(static::$I) ? new static() : static::$I;
    }

    protected static function setInstance($instance)
    {
        if(!$instance instanceof static){
            throw new \Exception('Instance must be of type ' . static::class);
        }
        static::$I = $instance;
    }

    protected function reset()
    {
        new static();
    }

    public function __set($name, $value)
    {
        if($name === 'I'){
            static::setInstance($value);
            return;
        }
        $this->$name = $value;
    }

    public function __get($name)
    {
        if($name === 'I'){
            return static::I();
        }
        return $this->$name ?? null;
    }

    public static function __callStatic($method, $args){
        $instance = static::I();
        return $instance->__call($method, $args);
    }

    public function __call($method, $args){
        if(method_exists($this, $method)){
            return $this->$method(...$args);
        }
        throw new \Exception('Method ' . $method . ' does not exist in ' . static::class);
    }

}