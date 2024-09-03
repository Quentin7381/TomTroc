<?php

namespace Entity;

class LazyEntity
{

    protected string $_LAZY_TYPE;
    public int|string $id;
    protected $entity;

    public function __construct($type, $id)
    {
        $this->_LAZY_TYPE = $type;
        $this->id = $id;
    }

    public function getEntity()
    {
        if ($this->entity === null) {
            $manager = $this->_LAZY_TYPE::get_manager();
            $this->entity = $manager->getById($this->id);
        }
        return $this->entity;
    }

    public function __get($name)
    {
        return $this->getEntity()->$name;
    }

    public function __set($name, $value)
    {
        $this->getEntity()->$name = $value;
    }

    public function __call($method, $args)
    {
        return $this->getEntity()->$method(...$args);
    }

    public function __toString()
    {
        return $this->getEntity()->__toString();
    }
}
