<?php

namespace Test;

class ReflectionInstance
{

    protected $reflection;
    protected $instance;

    public function __construct($reflection, $instance)
    {
        $this->reflection = $reflection;
        $this->instance = $instance;
    }

    public function __call($method, $args)
    {
        return $this->reflection->_CALL($method, $args, $this->instance);
    }

    public function __get($name)
    {
        return $this->reflection->_GET($name, $this->instance);
    }

    public function __set($name, $value)
    {
        return $this->reflection->_SET($name, $value, $this->instance);
    }
}