<?php

namespace View;

class Renderable {

    /**
     * Render the entity.
     *
     * @param array $variables
     * @param string $style
     *
     * @return string
     */
    public function render(array $variables = [], ?string $style = null): string
    {
        return View::getInstance()->render($this, $variables, $style);
    }
    

    public function __toString(): string
    {
        return $this->render();
    }

    public function toArray(): array
    {
        $array = [];
        $fields = static::getFields();
        foreach ($fields as $name => $type) {
            $value = $this->get($name);
            $array[$name] = $value;
        }

        return $array;
    }

    public function fromArray($array): void
    {
        foreach ($array as $name => $value) {
            $this->set($name, $value);
        }
    }

    public function get(string $name): mixed
    {
        // Check if a custom getter method exists for the property
        if (method_exists($this, 'get_' . $name)) {
            return $this->{'get_' . $name}();
        }

        // Check if the property exists
        if (property_exists($this, $name)) {
            return $this->$name;
        }

        throw new Exception("Property \"$name\" does not exist in " . static::class . ".");
    }

    public function __get(string $name): mixed
    {
        return $this->get($name);
    }

    public function __set(string $name, mixed $value): void
    {
        $this->set($name, $value);
    }

    /**
     * Set a property value.
     *
     * @param string $name
     * @param mixed $value
     *
     * @throws Exception if the property does not exist
     * @throws Exception if the property validation fails (@see validate_* methods)
     */
    public function set(string $name, mixed $value) : self
    {
        // Check if a validation method exists for the property
        if (method_exists($this, 'validate_' . $name)) {
            try {
                $this->{'validate_' . $name}($value);
            } catch (Exception $e) {
                throw new Exception("Property $name validation failed", 0, $e);
            }
        }

        // Check if a custom setter method exists for the property
        if (method_exists($this, 'set_' . $name)) {
            $this->{'set_' . $name}($value);
            return $this;
        }

        // Check if the property exists
        if (property_exists($this, $name)) {
            $this->$name = $value;
            return $this;
        }

        // Throw an exception if the property does not exist
        throw new Exception("Property \"$name\" does not exist in " . static::class . ".");
    }
}
