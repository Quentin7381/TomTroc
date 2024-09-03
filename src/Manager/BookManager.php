<?php

namespace Manager;
use Entity\Image;
use Entity\Book;

class BookManager extends AbstractManager
{
    public function add_book($title, $author, $description, $cover, $seller)
    {
        $extension = pathinfo($cover['name'], PATHINFO_EXTENSION);
        $allowed = ['jpg', 'jpeg', 'png'];
        if (!in_array($extension, $allowed)) {
            throw new Exception(Exception::USER_INVALID_IMAGE_EXTENSION, [
                'allowed' => $allowed,
                'extension' => $extension
            ]);
        }

        $book = new Book();
        $book->title = $title;
        $book->author = $author;
        $book->description = $description;
        $book->cover = new Image();
        $book->seller = $seller;

        $book->cover->name = uniqid('cover_') . '.' . $extension;
        $book->cover->src = '/public/img/covers/' . $book->cover->name;
        $book->cover->alt = $title;
        $book->cover->content = file_get_contents($cover['tmp_name']);

        $book->insert();
    }
}
