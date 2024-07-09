<?php

namespace Variables;

class Variables
{
    protected static $instance;
    protected Data $data;
    protected Provider $provider;

    protected function __construct()
    {
        $this->data = Data::I();
        $this->provider = Provider::I();
    }

    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new Variables();
        }
        return self::$instance;
    }

    public static function I()
    {
        return self::getInstance();
    }

    // ----- ----- ARRAY ACCESS ----- ----- //

    public function offsetExists($offset): bool
    {
        return true;
    }

    public function get($key)
    {
        if ($this->data->has($key)) {
            return $this->data->get($key);
        }

        if ($this->provider->has($key)) {
            $this->data->set($key, $this->provider->call($key));
            return $this->data->get($key);
        }

        throw new Exception(Exception::VARIABLE_NOT_FOUND, ['key' => $key]);
    }

    public function __get($key)
    {
        $key = str_replace('_', '.', $key);
        return $this->get($key);
    }

    public function __call($key, $args)
    {
        $key = str_replace('_', '.', $key);
        
        if(is_callable($this->get($key))) {
            return $this->get($key)(...$args);
        }

        else {
            throw new Exception(Exception::VARIABLE_NOT_CALLABLE, ['key' => $key]);
        }
    }
}
