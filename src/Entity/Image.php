<?php

namespace Entity;

use Config\Config;

class Image extends AbstractEntity
{
    protected ?string $folder = null;
    protected ?string $name = null;
    protected ?string $extension = null;
    protected ?string $content = null;
    protected ?string $src = null;
    protected ?string $alt = null;
    protected static array $_LOCAL_FIELDS = ['attributes', 'LOCAL_FIELDS', 'content', 'folder', 'extension'];

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
        $src = $this->get_src();
        // Remove the / at the beginning of the src if it's there
        if (strpos($src, '/') === 0) {
            $src = substr($src, 1);
        }

        file_put_contents($src, $content);
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
        // add / at the beginning of the src if it's not already there
        if (strpos($src, '/') !== 0) {
            $src = '/' . $src;
        }

        $this->src = $src;
        return $this;
    }

    public function default_name()
    {
        $src = $this->get('src');
        return pathinfo($src, PATHINFO_FILENAME);
    }

    public function default_extension()
    {
        $src = $this->get('src');
        return pathinfo($src, PATHINFO_EXTENSION);
    }

    public function render(array $variables = [], ?string $style = null): string
    {
        $this->get('attributes')->set('src', $this->get('src'));
        $this->get('attributes')->set('alt', $this->get('alt'));
        return parent::render($variables, $style);
    }
}
