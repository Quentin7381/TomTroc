<?php

namespace Variables;

abstract class Structure
{
    protected static self $instance;
    protected array $data = [];
    protected array $structure = [];

    protected function __construct()
    {
        $this->data = [];
        $this->structure = [];
    }

    public static function getInstance(): static
    {
        if (!isset(static::$instance)) {
            static::$instance = new static();
        }
        return static::$instance;
    }

    public static function I(): static
    {
        return static::getInstance();
    }

    protected function addStructure(array $keys)
    {
        $path = implode('.', $keys);

        $structure = &$this->structure;
        while ($keys) {
            $k = array_shift($keys);
            if (
                isset($structure[$k])
                && is_string($structure[$k])
                && $structure[$k] !== $path
            ) {
                throw new Exception(Exception::STRUCTURE_PATH_CONFLICT, ['new' => $path, 'existing' => $structure[$k]]);
            }

            if (
                empty($keys)
                && isset($structure[$k])
                && is_array($structure[$k])
            ) {
                throw new Exception(Exception::STRUCTURE_PATH_CONFLICT, ['new' => $path, 'existing' => $structure[$k]]);
            }

            $structure[$k] = empty($keys) ? $path : [];
            $structure = &$structure[$k];
        }
    }

    protected function removeStructure(array $keys)
    {
        $structure = &$this->structure;

        // Get to the end
        $remove = [];
        while ($keys) {
            $k = array_shift($keys);
            if (!isset($structure[$k])) {
                $path = implode('.', $keys);
                throw new Exception(Exception::STRUCTURE_PATH_NOT_FOUND, ['key' => $path]);
            }

            $remove[] = $structure;
            $structure = &$structure[$k];
        }

        while (empty($r) || is_string($r)) {
            $r = array_pop($remove);
            unset($k);
        }
    }

    public function set(array $keys, mixed $value)
    {
        $key = implode('.', $keys);
        $this->data[$key] = $value;
        $this->addStructure($keys);
    }

    public function get(array $keys): mixed
    {
        $key = implode('.', $keys);

        if (!isset($this->data[$key])) {
            throw new Exception(Exception::STRUCTURE_PATH_NOT_FOUND, ['key' => $key]);
        }

        return $this->data[$key] ?? null;
    }

    public function has(array $key): bool
    {
        $key = implode('.', $key);
        return isset($this->data[$key]);
    }

    public function remove(array $keys)
    {
        $key = implode('.', $keys);
        unset($this->data[$key]);
        $this->removeStructure($keys);
    }

    public function getStructure(): array
    {
        return $this->structure;
    }

}
