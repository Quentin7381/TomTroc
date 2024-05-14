<?php

namespace Test;

use Mockery as m;

class Reflection
{

    protected static $instances = [];
    protected $method;
    protected $property;
    protected $target;
    protected $class;

    private function __construct($target)
    {
        $this->target = $target;
        self::$instances[$target] = $this;
        $this->setupClass();
        $this->setupMethods();
        $this->setupProperties();
    }

    public static function _GET_INSTANCE($target)
    {
        return self::$instances[$target] ?? new Reflection($target);
    }

    private function setupMethods()
    {
        $methods = $this->class->getMethods();
        foreach ($methods as $method) {
            $method->setAccessible(true);
            $this->method[$method->name] = $method;
        }
    }

    private function setupProperties()
    {
        $properties = $this->class->getProperties();
        foreach ($properties as $property) {
            $property->setAccessible(true);
            $this->property[$property->name] = $property;
        }
    }

    private function setupClass()
    {
        $this->class = new \ReflectionClass($this->target);
    }

    public function __call($method, $args)
    {
        $lastI = count($args) - 1;
        if ($args[$lastI] instanceof ReflectionInstance) {
            $instance = array_shift($args);
        }

        return $this->_CALL($method, $args, $instance ?? null);
    }

    public function __get($name)
    {
        $this->_GET($name);
    }

    public function __set($name, $value)
    {
        $this->_SET($name, $value);
    }

    public function _GET($name, $instance = null)
    {
        if (isset($this->property[$name])) {
            return $this->property[$name]->getValue($instance);
        }
        
        return $instance->$name;
    }

    public function _SET($name, $value, $instance = null)
    {
        if(isset($this->property[$name])){
            $this->property[$name]->setValue($instance, $value);
            return;
        }

        $instance->$name = $value;
    }

    public function _NEW(...$args)
    {
        $instance = $this->class->newInstanceArgs($args);
        return new ReflectionInstance($this, $instance);
    }

    public function _NEW_FROM_INSTANCE($instance)
    {
        return new ReflectionInstance($this, $instance);
    }

    public function _NEW_MOCK(...$args){
        $mock = m::mock($this->target);
        return $this->_NEW_FROM_INSTANCE($mock);
    }

    public function _CALL($method, $args, $instance = null)
    {
        if (!empty($this->method[$method])) {
            return $this->method[$method]->invokeArgs($instance, $args);
        }
        
        return $instance->$method(...$args);
    }

    public function _ACCESS($type, $name, $accessible = true)
    {
        $this->$type[$name]->setAccessible($accessible);

    }
}