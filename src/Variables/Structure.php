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

    public function set(string $key, mixed $value)
    {
        $this->data[$key] = $value;
    }

    public function get(string $key): mixed
    {
        if (!isset($this->data[$key])) {
            throw new Exception(Exception::STRUCTURE_PATH_NOT_FOUND, ['key' => $key]);
        }

        return $this->data[$key] ?? null;
    }

    public function has(string $key): bool
    {
        return isset($this->data[$key]);
    }

    public function remove(string $key)
    {
        unset($this->data[$key]);
    }

    public function getStructure(): array
    {
        $structure = [];
        foreach ($this->data as $fullKey => $value) {
            $keys = explode('.', $fullKey);
            $cursor = &$structure;

            while($keys) {
                $key = array_shift($keys);
                if (!isset($cursor[$key])) {
                    $cursor[$key] = [];
                }

                if (empty($keys)) {
                    $cursor[$key] = $fullKey;
                }

                $cursor = &$cursor[$key];
            }
        }

        return $structure;
    }

}
