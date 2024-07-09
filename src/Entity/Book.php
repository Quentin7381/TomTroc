<?php

namespace Entity;

use Entity\LazyEntity;
use Entity\User;
use Entity\Image;

class Book extends AbstractEntity
{
    protected ?string $title = null;
    protected ?string $author = null;
    protected ?string $description = null;
    protected ?int $created = null;
    protected null|string|Image|LazyEntity $cover = null;
    protected null|int|User|LazyEntity $seller = null;

    public function fromDb(array $array) : void
    {
        parent::fromDb($array);
        $this->cover = new LazyEntity(Image::class, $array['cover']);
        $this->seller = new LazyEntity(User::class, $array['seller']);
    }

    public function get_created() : int
    {
        return $this->created ?? time();
    }

    public static function typeof_cover() : string
    {
        return 'varchar(255) NOT NULL';
    }

    public static function typeof_seller() : string
    {
        return 'int(6) NOT NULL';
    }

    public static function typeof_created() : string
    {
        return 'int(11) NOT NULL';
    }
}
