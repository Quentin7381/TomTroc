<?php

namespace Entity;

class Component extends AbstractEntity {

    protected static $instances = [];
    protected $variables = [];
    protected $template = null;

    public function __construct($template = null) {
        // parent::__construct();
        $this->template = $template;
        self::$instances[] = $this;
    }

    public function get($key) {
        if ($key === 'instances') {
            return static::$instances;
        }
        if (property_exists($this, $key)) {
            return $this->$key;
        }
        return $this->variables[$key] ?? null;
    }

    public function set($key, $value) {
        if (property_exists($this, $key)) {
            $this->$key = $value;
            return $this;
        }
        $this->variables[$key] = $value;
    }

    public function render($variables = [], $style = null) {
        $view = \Utils\View::getInstance();
        return $view->include($this->template, $variables, $style);
    }

    public static function __callStatic($name, $arguments) {
        $component = new static($name);
        return $component->render($arguments ?? []);
    }
}
