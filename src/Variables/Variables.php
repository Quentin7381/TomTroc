<?php

namespace Variables;

class Variables
{
    protected static $instance;
    protected Data $data;
    protected Provider $provider;
    protected self $previous;
    protected array $keys = [];

    protected function __construct($keys = [])
    {
        $this->data = Data::I();
        $this->provider = Provider::I();
        $this->keys = $keys;
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

    public function get(...$keys)
    {
        if ($this->data->has($keys)) {
            return $this->data->get($keys);
        }

        if ($this->provider->has($keys)) {
            $this->data->set($keys, $this->provider->call($keys));
            return $this->data->get($keys);
        }

        throw new Exception(Exception::VARIABLE_NOT_FOUND, ['key' => implode('.', $keys)]);
    }
}
