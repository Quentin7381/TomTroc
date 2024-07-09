<?php

namespace View;

class Component extends Renderable
{

    protected static $instances = [];
    protected $variables = [];
    protected $template = null;

    public function __construct($template = null)
    {
        // parent::__construct();
        $this->template = $template;
        self::$instances[] = $this;
    }

    public function get($key): mixed
    {
        if ($key === 'instances') {
            return static::$instances;
        }
        
        return parent::get($key);
    }

    public function render($variables = [], $style = null): string
    {
        $view = \View\View::getInstance();
        return $view->include($this->template, $variables, $style);
    }

    public static function __callStatic($name, $arguments)
    {
        $component = new static($name);
        return $component->render($arguments ?? []);
    }
}
