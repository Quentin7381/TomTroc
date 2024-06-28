<?php

namespace Utils;

use Config\Config;
use Entity\Component;
use Variables\Variables;

class View
{
    protected static $instance;
    public $css = [];
    public $html;

    protected function __construct()
    {
        self::$instance = $this;
    }

    public static function getInstance()
    {
        if (empty(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function render($entity, $variables = [], $style = null)
    {
        $templateName = get_class($entity);
        $templateName = str_replace('Entity\\', '', $templateName);
        $templateName = lcfirst($templateName);
        $variables['entity'] = $entity;
        return $this->include($templateName, $variables, $style);
    }

    public function getRoot($extension)
    {
        switch ($extension) {
            case '.tpl':
                $root = Config::getInstance()->PATH_TEMPLATES;
                break;
            case '.css':
                $root = Config::getInstance()->PATH_CSS;
                break;
            default:
                throw new Exception('Extension not allowed: ' . $extension);
        }
        return $root;
    }

    public function getTemplatePath($fileName, $extension = '.tpl', $style = null)
    {
        $root = $this->getRoot($extension);
        $styleFile = $style ? $fileName . '-' . $style . $extension : null;
        $fileName .= $extension;
        $return = false;

        // recursive search
        $iterator = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($root));
        foreach ($iterator as $file) {
            if ($file->isDir()) {
                continue;
            }

            if ($styleFile && $file->getFilename() === $styleFile) {
                return $file->getPathname();
            }

            if ($file->getFilename() === $fileName) {
                if (empty($style)) {
                    return $file->getPathname();
                }

                $return = $file->getPathname();
            }
        }

        return $return;
    }

    public function include($templateName, $variables = [], $style = null)
    {
        $fileName = $this->getTemplatePath($templateName, '.tpl', $style);
        if (!is_readable($fileName ?? '')) {
            user_error('File not found for template: ' . $templateName, E_USER_WARNING);
            return "";
        }

        $this->css[$this->getTemplatePath($templateName, '.css', $style)] = true;

        extract($variables);
        $attributes['class'][] = 'tpl-' . $templateName;
        if($style){
            $attributes['class'][] = 'tpl-' . $templateName . '-' . $style;
        }

        $variables = $v = Variables::I();
        $bem = Bem::I($templateName, $style);

        ob_start();
        include $fileName;
        return ob_get_clean();
    }

    public function buildPage($options = [])
    {
        $this->html = Component::page($options);
        $this->addCss();
    }

    public function addCss()
    {
        $html = Component::css();
        $this->html = str_replace('</head>', $html . '</head>', $this->html);
    }

    public static function renderAttributes($attributes)
    {
        $html = '';
        foreach ($attributes as $key => $value) {
            if (is_array($value)) {
                $value = implode(' ', $value);
            }
            $html .= ' ' . $key . '="' . $value . '"';
        }
        return $html;
    }
}
