<?php

namespace Utils;

class Bem {

    protected static $instances = [];
    protected static $currentInstance = null;
    protected $template;

    public static function getInstance($template, $style = null){
        if(!empty($style)){
            $template .= '-' . $style;
        }
        if(!isset(self::$instances[$template])){
            self::$instances[$template] = new self($template);
        }

        self::$currentInstance = $template;
        return self::$instances[$template];
    }

    public static function I($template, $style = null){
        return self::getInstance($template, $style);
    }

    protected function __construct($template){
        $this->template = $template;
    }

    public function elementClass($element){
        return 'tpl-' . $this->template . '__' . $element;
    }

    public function modifierClass($modifier){
        return 'tpl-' . $this->template . '--' . $modifier;
    }
    
    public function e($element){
        return $this->elementClass($element);
    }

    public function m($modifier){
        return $this->modifierClass($modifier);
    }

}
