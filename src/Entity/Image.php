<?php

namespace Entity;

use Config\Config;

class Image extends AbstractEntity
{
    protected $folder;
    protected $name;
    protected $extension;
    protected $content;
    protected $src;
    protected $alt;
    protected static $_LOCAL_FIELDS = ['attributes', 'LOCAL_FIELDS', 'content', 'folder', 'extension', 'id'];
    public static $_IDENTIFIER = 'name';

    public function __construct()
    {
        $this->folder = Config::getInstance()->PATH_IMG;
    }

    public function get_content()
    {
        if (empty($this->content)) {
            $this->content = file_get_contents($this->get_src());
        }
    }

    public function set_content($content)
    {
        $this->content = $content;
        file_put_contents($this->get_src(), $content);
        return $this;
    }

    public function get_src()
    {
        return
            !empty($this->src) ?
            $this->src :
            $this->folder . $this->name . '.' . $this->extension;
    }

    public function set_src($src)
    {
        $this->src = $src;

        $isHttp = strpos($src, 'http') === 0;
        if (!$isHttp) {
            $src = explode('/', $src);
            $name = end($src);
            $name = explode('.', $name);
            $this->name = $name[0];
            $this->extension = $name[1] ?? '';
        }

        return $this;
    }

    public function get_id()
    {
        return $this->name;
    }

    public function set_id($id)
    {
        $this->name = $id;
        return $this;
    }

    public static function typeof_id()
    {
        return 'varchar(255) PRIMARY KEY';
    }

    public function render($variables = [], $style = null)
    {
        $variables['attributes']['src'] = $this->get_src();
        $variables['attributes']['alt'] = $this->alt;
        return parent::render($variables, $style);
    }

    public function toDb()
    {
        $array = parent::toDb();
        $ignore = ['content'];
        foreach ($ignore as $key) {
            unset($array[$key]);
        }
        return $array;
    }

    public static function typeof_name()
    {
        return 'varchar(255) NOT NULL UNIQUE';
    }

}
