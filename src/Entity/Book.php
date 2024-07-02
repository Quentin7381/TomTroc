<?php

namespace Entity;

use Entity\LazyEntity;
use Entity\User;
use Entity\Image;

class Book extends AbstractEntity {
    protected string $title;
    protected string $author;
    protected string $description;
    protected Image|LazyEntity $cover;
    protected User|LazyEntity $seller;

    public function toDb(){
        return [
            'title' => $this->title,
            'author' => $this->author,
            'description' => $this->description,
            'cover' => $this->cover->id,
            'seller' => $this->seller->id
        ];
    }

    public function fromDb($data){
        $this->title = $data['title'];
        $this->author = $data['author'];
        $this->description = $data['description'];
        $this->cover = new LazyEntity(Image::class, $data['cover']);
        $this->seller = new LazyEntity(User::class, $data['seller']);
    }
}
