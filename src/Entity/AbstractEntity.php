<?php

namespace Entity;
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
    protected static array $_LOCAL_FIELDS = ['attributes', 'manager'];

    public static function get_local_fields(): array
    {
        return static::$_LOCAL_FIELDS;
    }

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
     * Get the manager for the entity.
     *
     * @return AbstractManager
     */
    public static function get_manager(): AbstractManager
    {
        $className = static::class;
        $managerName = str_replace('Entity', 'Manager', $className);
        $managerName = $managerName . 'Manager';

        return $managerName::getInstance();
    }

    public function toArray(): array
    {
        $manager = static::get_manager();
        $fields = $manager->getEntityFields();

        $array = [];
        foreach ($fields as $name => $type) {
            $value = $this->get($name);
            $array[$name] = $value;
        }

        return $array;
    }

    // ----- SHORTCUTS ----- //

    public function __call(string $name, array $arguments): mixed
    {
        if (
            !in_array($name, [
                'persist',
                'delete',
                'merge',
                'exists',
                'fromDb',
                'toDb',
                'insert',
                'update',
                'hydrate',
            ])
        ) {
            throw new \Exception("Method $name does not exist in " . static::class);
        }

        $manager = static::get_manager();

        if (!method_exists($manager, $name)) {
            throw new \Exception("Method $name does not exist in " . get_class($manager) . " nor in " . static::class);
        }

        try {
            return $manager->$name($this, ...$arguments);
        } catch (\InvalidArgumentException $e) {
            return $manager->$name(...$arguments);
        }
    }

}
