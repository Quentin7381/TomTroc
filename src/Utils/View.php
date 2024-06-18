<?php

namespace Utils;

use Config\Config;

class View
{
    protected static $instance;
    protected $css = [];

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
        $this->include($templateName, $variables, $style);
    }

    public function getRoot($extension){
        switch ($extension) {
            case '.php':
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

    public function getTemplatePath($fileName, $extension = '.php', $style = null)
    {
        $fileName = $this->getRoot($extension) . $fileName;

        if (!empty($style)) {
            $fileNameStyle = '-' . $style;
            $fileNameStyle .= $extension;

            if (file_exists($fileNameStyle)) {
                $fileName = $fileNameStyle;
            } else {
                user_error('Style not found: ' . $fileNameStyle . 'trying to use default style');
            }
        }

        $fileName = str_replace($extension, '', $fileName);
        $fileName .= $extension;

        if (file_exists($fileName)) {
            return $fileName;
        } else {
            user_error('File not found: ' . $fileName);
        }
    }

    public function include($templateName, $variables = [], $style = null){
        $fileName = $this->getTemplatePath($templateName, '.php', $style);
        if(!file_exists($fileName ?? '')){
            user_error('File not found: ' . $fileName);
            return;
        }

        $this->css[] = $this->getTemplatePath($templateName, '.css', $style);

        extract($variables);
        $wrapper = $wrapper ?? 'div';

        ob_start();
        ?>
        <<?= $wrapper ?> class="tpl<?= $className . $style ? ' ' . $style : '' ?>">
            <?php
            include $fileName;
            ?>
        </<?= $wrapper ?>>
        <?php

        return ob_get_clean();
        
    }

}
