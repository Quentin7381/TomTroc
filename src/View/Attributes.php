<?php

namespace View;

class Attributes extends Renderable
{
    protected array $data;

    public function __construct(array $attributes = [])
    {
        $this->data = $attributes;
    }

    public function add(string $name, mixed ...$values): Attributes
    {
        if (!isset($this->data[$name])) {
            $this->data[$name] = [];
        }

        foreach ($values as $value) {
            $this->data[$name][] = $value;
        }

        return $this;
    }

    public function remove(string $name, mixed ...$values): Attributes
    {
        if (isset($this->data[$name])) {
            foreach ($values as $value) {
                $key = array_search($value, $this->data[$name]);
                if ($key !== false) {
                    unset($this->data[$name][$key]);
                }
            }
        }

        return $this;
    }

    public function set(string $name, mixed $values): Attributes
    {
        if (!is_array($values)) {
            $values = [$values];
        }
        $this->data[$name] = $values;

        return $this;
    }

    public function addMultiple(array $attributes): Attributes
    {
        foreach ($attributes as $name => $values) {
            $this->add($name, ...$values);
        }

        return $this;
    }

    public function getAttribute(string $name): array
    {
        return $this->data[$name] ?? [];
    }
}
