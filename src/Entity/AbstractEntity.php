<?php

namespace Entity;

use ReflectionClass;
use ReflectionProperty;
use Utils\View;
use Manager\AbstractManager;

/**
 * AbstractEntity class
 *
 * This class is the base class for all entities.
 */
abstract class AbstractEntity
{
    protected ?int $id = null;
    protected array $attributes = [];
    protected static array $_LOCAL_FIELDS = ['attributes'];

    /**
     * Set a property value.
     *
     * @param string $name
     * @param mixed $value
     *
     * @throws Exception if the property does not exist
     * @throws Exception if the property validation fails (@see validate_* methods)
     */
    public function set(string $name, mixed $value)
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
            return;
        }

        // Check if the property exists
        if (property_exists($this, $name)) {
            $this->$name = $value;
            return;
        }

        // Throw an exception if the property does not exist
        throw new Exception("Property \"$name\" does not exist in " . static::class . ".");
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
    public function __get(string $name): mixed
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
    public function __set(string $name, mixed $value): void
    {
        $this->set($name, $value);
    }

    /**
     * Add a value to an attribute.
     * Attributes are html attributes that will be rendered in the view.
     *
     * @param string $name
     * @param mixed $values
     */
    public function addAttribute(string $name, mixed ...$values): void
    {
        if (!isset($this->attributes[$name])) {
            $this->attributes[$name] = [];
        }

        foreach ($values as $value) {
            $this->attributes[$name][] = $value;
        }
    }

    /**
     * Remove a value from an attribute.
     * Attributes are html attributes that will be rendered in the view.
     *
     * @param string $name
     * @param mixed $value
     */
    public function removeAttribute(string $name, mixed $value): void
    {
        if (isset($this->attributes[$name])) {
            $key = array_search($value, $this->attributes[$name]);
            if ($key !== false) {
                unset($this->attributes[$name][$key]);
            }
        }
    }

    /**
     * Merge several attributes arrays.
     *
     * @param array ...$attributes
     *
     * @return array
     */
    public function mergeAttributes(array ...$attributes): array
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

    /**
     * Get the fields of the entity.
     * Keys are the field names and values are the database types.
     *
     * @return array
     */
    public static function getFields(): array
    {
        $reflectionClass = new ReflectionClass(static::class);
        $properties = $reflectionClass->getProperties(ReflectionProperty::IS_PROTECTED);

        $protectedProperties = [];
        foreach ($properties as $property) {
            $name = $property->getName();

            // Skip properties starting with an underscore
            if (strpos($name, '_') === 0) {
                continue;
            }

            if (method_exists(static::class, 'typeof_' . $name)) {
                $type = static::{'typeof_' . $name}();
            } else {
                $type = $property->getType();
                $type = static::getDbType($type);
            }

            $protectedProperties[$name] = $type;
        }

        foreach (static::$_LOCAL_FIELDS as $field) {
            unset($protectedProperties[$field]);
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
    public static function getDbType(string $type): string
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

    /**
     * Get the manager for the entity.
     *
     * @return AbstractManager
     */
    public static function getManager(): AbstractManager
    {
        $className = static::class;
        $managerName = str_replace('Entity', 'Manager', $className);
        $managerName = $managerName . 'Manager';
        return $managerName::getInstance();
    }

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
        $variables['attributes'] = $this->mergeAttributes($this->attributes, $variables['attributes'] ?? []);
        return View::getInstance()->render($this, $variables, $style);
    }

    public function __toString(): string
    {
        return $this->render();
    }

    /**
     * Insert the entity in the database.
     * Shortcut for the manager insert method.
     *
     * @return int The id of the inserted entity.
     * @see AbstractManager::insert
     */
    public function persist(): AbstractEntity
    {
        $manager = static::getManager();
        return $manager->persist($this);
    }

    /**
     * Fill the entity with an array of values.
     */
    public function fromDb(array $array): void
    {
        foreach ($array as $name => $value) {
            $this->set($name, $value);
        }
    }

    /**
     * Get the entity as an array for the database.
     * Entities relations are replaced by their id.
     * Entities relations are inserted if they do not have an id yet.
     * Local fields are removed.
     */
    public function toDb(): array
    {
        $array = [];
        $fields = static::getFields();
        foreach ($fields as $name => $type) {
            $value = $this->get($name);
            // Entities become their id
            if (
                $value instanceof AbstractEntity
            ) {
                if (empty($value->get('id'))) {
                    $value = $value->persist();
                } else {
                    $value = $value->get('id');
                }
            }

            if ($value instanceof LazyEntity) {
                $value = $value->id;
            }

            $array[$name] = $value;
        }
        foreach (self::$_LOCAL_FIELDS as $field) {
            unset($array[$field]);
        }

        return $array;
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

    public static function typeof_id(): string
    {
        return 'int(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY';
    }
}
