<?php

namespace Variables;

class Provider extends Structure
{

    protected static self $instance;

    public function set(array $keys, mixed $value)
    {
        if (!is_callable($value)) {
            throw new Exception(Exception::PROVIDER_NOT_CALLABLE, ['key' => implode('.', $keys)]);
        }

        $key = implode('.', $keys);

        if (isset($this->providers[$key])) {
            throw new Exception(Exception::PROVIDER_ALREADY_EXISTS, ['key' => $key]);
        }

        parent::set($keys, $value);
    }

    public function get(array $keys): mixed
    {
        try {
            return parent::get($keys);
        } catch (Exception $e) {
            if ($e->getCode() == Exception::STRUCTURE_PATH_NOT_FOUND) {
                throw new Exception(Exception::PROVIDER_NOT_FOUND, ['key' => implode('.', $keys)], $e);
            }

            throw $e;
        }
    }

    public function call(array $keys, array $args = [])
    {
        $provider = $this->get($keys);
        return $provider(...$args);
    }

}
