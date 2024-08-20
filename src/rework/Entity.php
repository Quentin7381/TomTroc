<?php

namespace Entity;

/**
 * Entite est resposable du stockage et de la validite des donnees pour une utilisation front-end
 * Il comprends
 * - Les proprietes de l'entite
 * - Les proprietes par defaut via default_*
 * - Les validateurs via validate_*
 * - Les setters via set_*
 * - Les getters via get_*
 */
class Entity
{
    public function __construct(){
        $this->default();
        $this->validate();
    }

    public function set(string $name, $value)
    {
        if (method_exists($this, 'set_' . $name)) {
            $method = 'set_' . $name;
            $this->$method($value);
            return;
        }
        
        if (property_exists($this, $name)) {
            $this->$name = $value;
            return;
        }

        throw new \Exception('Property ' . $name . ' does not exist');
    }

    public function __set($name, $value)
    {
        $this->set($name, $value);
    }

    public function get(string $name)
    {
        if (method_exists($this, 'get_' . $name)) {
            $method = 'get_' . $name;
            return $this->$method();
        }

        if (property_exists($this, $name)) {
            return $this->$name;
        }
        
        throw new \Exception('Property ' . $name . ' does not exist');
    }

    public function __get($name)
    {
        return $this->get($name);
    }

    public function validate(string ...$names): bool
    {
        if(empty($names)) {
            $names = array_keys(get_object_vars($this));
        }

        foreach ($names as $name) {
            $validator = 'validate_' . $name;
            if (
                method_exists($this, $validator) &&
                !$this->$validator()
            ) {
                return false;
            }
        }

        return true;
    }

    public function default(string ...$names): void
    {
        if(empty($names)) {
            $names = array_keys(get_object_vars($this));
        }

        foreach ($names as $name) {
            $default = 'default_' . $name;
            if (
                method_exists($this, $default)
            ) {
                $this->$name = $this->$default();
            }
        }
    }
}
