<?php

namespace Entity;

use Config\Config;

class Image extends AbstractEntity
{

    protected $id;
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
            $this->content = file_get_contents($this->folder . $this->name);
        }
    }

    public function set_content($content){
        $this->content = $content;
        file_put_contents($this->folder . $this->name, $content);
        return $this;
    }

    public function get_src(){
        return $this->folder . $this->name . '.' . $this->extension;
    }

    public function set_src($src){
        $src = explode('/', $src);
        $this->name = end($src);
        $this->extension = explode('.', $this->name)[1];
        return $this;
    }

}
