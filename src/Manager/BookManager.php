<?php

namespace Manager;
use Entity\Image;
use Entity\Book;

class BookManager extends AbstractManager
{
    public function add_book($title, $author, $description, $available, $cover, $seller)
    {
        $extension = pathinfo($cover['name'], PATHINFO_EXTENSION);
        $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
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
        $book->available = $available;
        $book->seller = $seller;
        $book->cover = new Image();

        $book->cover->name = uniqid('cover_') . '.' . $extension;
        $book->cover->src = '/public/img/covers/' . $book->cover->name;
        $book->cover->alt = $title;
        $book->cover->content = file_get_contents($cover['tmp_name']);

        $book->insert();
    }

    public function edit_book($id, $title, $author, $description, $available, $cover)
    {
        $book = $this->getById($id);
        $book->title = $title;
        $book->author = $author;
        $book->description = $description;
        $book->available = $available;

        if (!empty($cover['name'])) {
            $extension = pathinfo($cover['name'], PATHINFO_EXTENSION);
            $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
            if (!in_array($extension, $allowed)) {
                throw new Exception(Exception::USER_INVALID_IMAGE_EXTENSION, [
                    'allowed' => $allowed,
                    'extension' => $extension
                ]);
            }

            $book->cover = new Image();
            $book->cover->name = uniqid('cover_') . '.' . $extension;
            $book->cover->src = '/public/img/covers/' . $book->cover->name;
            $book->cover->alt = $title;
            $book->cover->content = file_get_contents($cover['tmp_name']);
        }

        $book->update();
    }

    public function typeof_cover() : string
    {
        return 'INT(6) NOT NULL';
    }

    public function typeof_seller() : string
    {
        return 'INT(6) NOT NULL';
    }
}
