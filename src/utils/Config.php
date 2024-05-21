<?php

namespace Utils;

class Config extends Singleton {
    protected static $instance = null;
    protected $config = [];

    protected function __construct(){
        $this->config = require __DIR__ . '/../config/config.local.php';
    }

    public static function getInstance(){
        if(static::$instance === null){
            static::$instance = new static();
        }
        return static::$instance;
    }

    public function __get($name){
        return $this->get($name);
    }

    public function get($name){
        if(!isset($this->config[$name])){
            throw new Exception('This property is not set!');
        }
        return $this->config[$name];
    }

    public function __set($name, $value){
        return $this->set($name, $value);
    }

    public function set($name, $value){
        if(isset($this->config[$name])){
            throw new Exception('This property is already set!');
        }
        $this->config[$name] = $value;
        return $this;
    }

    public function override($name, $value){
        $this->config[$name] = $value;
        return $this;
    }

    public function __invoke($key, $value = null){
        if($value === null){
            return $this->get($key);
        }
        return $this->set($key, $value);
    }

    public function load(array $config, bool $override = false){
        if($override){
            foreach($config as $key => $value){
                $this->override($key, $value);
            }
        } else {
            foreach($config as $key => $value){
                $this->set($key, $value);
            }
        }
    }
}