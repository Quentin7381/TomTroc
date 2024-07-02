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

    public function __construct(){
        $this->folder = Config::getInstance()->PATH_IMG;
    }

    public function get_content(){
        if(empty($this->content)){
            $this->content = file_get_contents($this->get_src());
        }
    }

    public function set_content($content){
        $this->content = $content;
        file_put_contents($this->get_src(), $content);
        return $this;
    }

    public function get_src(){
        return $this->folder . $this->name . '.' . $this->extension;
    }

    public function set_src($src){
        $src = explode('/', $src);
        $name = end($src);
        $name = explode('.', $name);
        $this->name = $name[0];
        $this->extension = $name[1] ?? '';
        return $this;
    }

    public function render($variables = [], $style = null)
    {
        $variables['attributes']['src'] = $this->get_src();
        $variables['attributes']['alt'] = $this->alt;
        return parent::render($variables, $style);
    }

    public function toArray()
    {
        $array = parent::toArray();
        $ignore = ['content'];
        foreach ($ignore as $key) {
            unset($array[$key]);
        }
        return $array;
    }

}
