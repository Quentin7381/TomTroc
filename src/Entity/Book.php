<?php

namespace Entity;

use Entity\LazyEntity;
use Entity\User;
use Entity\Image;

class Book extends AbstractEntity {
    protected string $title;
    protected string $author;
    protected string $description;
    protected int $created;
    protected string|Image|LazyEntity $cover;
    protected int|User|LazyEntity $seller;

    public function fromDb($array){
        parent::fromDb($array);
        $this->cover = new LazyEntity(Image::class, $array['cover']);
        $this->seller = new LazyEntity(User::class, $array['seller']);
    }

    public function get_created(){
        return $this->created ?? time();
    }

    public static function typeof_cover(){
        return 'varchar(255) NOT NULL';
    }

    public static function typeof_seller(){
        return 'int(6) NOT NULL';
    }

    public static function typeof_created(){
        return 'int(11) NOT NULL';
    }
}
