<?php

namespace Entity;

class Component extends AbstractEntity {

    protected static $instances = [];
    protected $data = [];
    protected $template;

    public function __construct($template = null) {
        // parent::__construct();
        $this->template = $template;
        self::$instances[] = $this;
    }

    public function get($key) {
        if ($key === 'instances') {
            return static::$instances;
        }
        return $this->data[$key] ?? null;
    }

    public function set($key, $value) {
        $this->data[$key] = $value;
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
