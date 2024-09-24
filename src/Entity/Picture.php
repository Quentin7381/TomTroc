<?php

namespace Entity;

use Entity\AbstractEntity;
use Entity\Image;
use Variables\Variables;
use View\Attributes;

class Picture extends AbstractEntity
{
    protected array $images = [

    ];

    protected ?string $name;

    public function addImage(string|Image $image, int $breakpoint = 0)
    {
        if (is_string($image)) {
            $v = Variables::I();
            $image = $v->image_get($image);
            if (!$image) {
                throw new \Exception('Image not found: ' . $image);
            }
        }

        $this->images[$breakpoint] = $image;
    }

    public function set_images($content)
    {
        throw new \Exception('Use addImage() instead');
    }

    public static function typeof_images()
    {
        return 'varchar(511)';
    }

    public function toDb(): array
    {
        $data = parent::toDb();
        $images = [];
        foreach($this->images as $breakpoint => $image) {
            $images[$breakpoint] = $image->name;
        }
        $data['images'] = json_encode($images);

        return $data;
    }

    public function fromDb(array $data): void
    {
        $images = json_decode($data['images'], true);
        foreach($images as $breakpoint => $image) {
            $this->addImage($image, $breakpoint);
        }
        unset($data['images']);
        parent::fromDb($data);
    }

}
