<?php

namespace Utils;

class Bem {

    protected static $instances = [];
    protected static $currentInstance = null;
    protected $template;

    public static function getInstance($template){
        if(!isset(self::$instances[$template])){
            self::$instances[$template] = new self($template);
        }

        self::$currentInstance = $template;
        return self::$instances[$template];
    }

    public static function I($template){
        return self::getInstance($template);
    }

    public function __construct($template){
        $this->template = $template;
    }

    public function elementClass($element){
        return 'tpl-' . $this->template . '__' . $element;
    }

    public function modifierClass($modifier){
        return 'tpl-' . $this->template . '--' . $modifier;
    }
    
    public static function __callStatic($method, $args){
        if(method_exists(self::class, $method)){
            return call_user_func_array([self::class, $method], $args);
        }

        $class = $method;
        $type = $args[0] ?? 'element';
        if(!in_array($type, ['element', 'modifier'])){
            throw new Exception('Invalid type');
        }
        $instance = self::$instances[self::$currentInstance];
        return $instance->{$type . 'Class'}($class);
    }

}
