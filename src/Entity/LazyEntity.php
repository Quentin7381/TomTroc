<?php

namespace Entity;

class LazyEntity {

    protected string $_LAZY_TYPE;
    public int $id;
    protected $entity;

    public function __construct($type, $id){
        $this->_LAZY_TYPE = $type;
        $this->id = $id;
    }

    public function __get($name){
        if($this->entity === null){
            $this->entity = $this->_LAZY_TYPE::getById($this->id);
        }
        return $this->entity->$name;
    }

    public function __set($name, $value){
        if($this->entity === null){
            $this->entity = $this->_LAZY_TYPE::getById($this->id);
        }
        $this->entity->$name = $value;
    }


}
