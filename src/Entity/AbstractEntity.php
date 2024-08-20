<?php

namespace Entity;

use ReflectionClass;
use ReflectionProperty;
use Manager\AbstractManager;
use View\Attributes;
use View\Renderable;

/**
 * AbstractEntity class
 *
 * This class is the base class for all entities.
 */
abstract class AbstractEntity extends Renderable
{
    protected ?int $id = null;
    protected Attributes $attributes;
    protected static array $_LOCAL_FIELDS = ['attributes'];

    public function get_attributes()
    {
        if (empty($this->attributes)) {
            $this->attributes = new Attributes();
        }
        return $this->attributes;
    }

    public function addAttributes($type, ...$values): self
    {
        $this->get_attributes()->add($type, ...$values);
        return $this;
    }

    public function removeAttributes($type, ...$values): self
    {
        $this->get_attributes()->remove($type, ...$values);
        return $this;
    }

    public function setAttributes($type, ...$values): self
    {
        $this->get_attributes()->set($type, ...$values);
        return $this;
    }

    public function get(string $key): mixed
    {
        $value = parent::get($key);
        if ($value === null && method_exists($this, 'default_' . $key)) {
            $value = $this->{'default_' . $key}();
        }

        return $value;
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
     * Update the entity in the database.
     * Shortcut for the manager update method.
     *
     * @return int The id of the updated entity.
     * @see AbstractManager::update
     */
    public function hydrate(): AbstractEntity
    {
        $manager = static::getManager();
        return $manager->hydrate($this);
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
                }

                $value = $value->get('id');
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

    public static function typeof_id(): string
    {
        return 'int(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY';
    }
}
