<?php

namespace Variables;

class Provider extends Structure
{

    protected static self $instance;

    public function set(string $key, mixed $value)
    {
        if (!is_callable($value)) {
            throw new Exception(Exception::PROVIDER_NOT_CALLABLE, ['key' => $key]);
        }

        if (isset($this->data[$key])) {
            throw new Exception(Exception::PROVIDER_ALREADY_EXISTS, ['key' => $key]);
        }

        parent::set($key, $value);
    }

    public function get(string $key): mixed
    {
        try {
            return parent::get($key);
        } catch (Exception $e) {
            if ($e->getCode() === Exception::STRUCTURE_PATH_NOT_FOUND) {
                throw new Exception(Exception::PROVIDER_NOT_FOUND, ['key' => $key], $e);
            }

            throw $e;
        }
    }

    public function call(string $key, array $args = [])
    {
        $provider = $this->get($key);
        return $provider(...$args);
    }

}
