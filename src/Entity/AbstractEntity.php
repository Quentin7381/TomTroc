<?php

namespace Entity;

use ReflectionClass;
use ReflectionProperty;
use Utils\View;

/**
 * AbstractEntity class
 *
 * This class is the base class for all entities.
 */
abstract class AbstractEntity
{
    protected $id;
    protected $attributes = [];

    /**
     * Set a property value.
     *
     * @param string $name
     * @param mixed $value
     *
     * @return AbstractEntity
     *
     * @throws Exception if the property does not exist
     * @throws Exception if the property validation fails (@see validate_* methods)
     */
    public function set($name, $value)
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
            return $this->{'set_' . $name}($value);
        }

        // Check if the property exists
        if (property_exists($this, $name)) {
            $this->$name = $value;
            return $this;
        }

        // Throw an exception if the property does not exist
        throw new Exception("Property \"$name\" does not exist in " . static::class . ".");
    }

    public function addAttribute($name, ...$values)
    {
        if (!isset($this->attributes[$name])) {
            $this->attributes[$name] = [];
        }

        foreach ($values as $value) {
            $this->attributes[$name][] = $value;
        }
    }

    public function removeAttribute($name, $value)
    {
        if (isset($this->attributes[$name])) {
            $key = array_search($value, $this->attributes[$name]);
            if ($key !== false) {
                unset($this->attributes[$name][$key]);
            }
        }
    }

    /**
     * Get a property value.
     *
     * @param string $name
     *
     * @return mixed
     *
     * @throws Exception if the property does not exist
     */
    public function get($name)
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

    /**
     * Magic method to get a property value.
     * Calls the get method.
     * @see get
     *
     * @param string $name
     *
     * @return mixed
     *
     * @throws Exception if the property does not exist
     */
    public function __get($name)
    {
        return $this->get($name);
    }

    /**
     * Magic method to set a property value.
     * Calls the set method.
     * @see set
     *
     * @param string $name
     * @param mixed $value
     */
    public function __set($name, $value)
    {
        return $this->set($name, $value);
    }

    /**
     * Returns the entity properties as an array.
     *
     * @return array
     */
    public static function getFields()
    {
        $reflectionClass = new ReflectionClass(static::class);
        $properties = $reflectionClass->getProperties(ReflectionProperty::IS_PROTECTED);

        $protectedProperties = [];
        foreach ($properties as $property) {
            $name = $property->getName();

            if (method_exists(static::class, 'typeof_' . $name)) {
                $type = static::{'typeof_' . $name}();
            } else {
                $type = $property->getType();
                $type = static::getDbType($type);
            }

            $protectedProperties[$name] = $type;
        }

        return $protectedProperties;
    }



    /**
     * Get the database type for a given php type.
     *
     * @param string $type The type of the property.
     *
     * @return string The database type.
     */
    public static function getDbType($type)
    {
        switch ($type) {
            case 'int':
                return 'INT(6)';
            case 'string':
                return 'VARCHAR(255)';
            case 'bool':
                return 'TINYINT(1)';
            case 'float':
                return 'FLOAT';
            case 'DateTime':
                return 'DATETIME';
            default:
                return 'VARCHAR(255)';
        }
    }

    public static function getManager()
    {
        $className = static::class;
        $managerName = str_replace('Entity', 'Manager', $className);
        $managerName = $managerName . 'Manager';
        return new $managerName();
    }

    public function __toString()
    {
        return $this->render();
    }

    public function render($variables = [], $style = null)
    {
        $variables['attributes'] = $this->mergeAttributes($this->attributes, $variables['attributes'] ?? []);
        return View::getInstance()->render($this, $variables, $style);
    }

    public function mergeAttributes(...$attributes)
    {
        $return = [];
        foreach ($attributes as $attribute) {
            foreach ($attribute as $key => $values) {
                if (!is_array($values)) {
                    $values = [$values];
                }

                if (!isset($return[$key])) {
                    $return[$key] = [];
                }

                $return[$key] = array_merge($return[$key], $values);
            }
        }

        return $return;
    }
}
