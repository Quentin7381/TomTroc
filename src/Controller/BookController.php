<?php

namespace Controller;

use Entity\Book;
use Entity\Image;
use Entity\User;

class BookController extends AbstractController
{

    protected function initRoutes()
    {

    }

    public function provider_lasts()
    {
        $generator = new \Utils\Generator();
        $generator->current_set_callback(function () {
            $user = new User();
            $user->name = "Jean";
    
            $cover = new Image();
            $cover->src = "assets/img/the-kinfolk.png";
            $cover->alt = "Couverture du livre";
    
            $book = new Book();
            $book->author = "Rudyard Kipling";
            $book->cover = $cover;
            $book->seller = $user;
            $book->title = "The Jungle Book";

            return $book;
        });

        $generator->valid_set_callback(function ($data, $position) {
            return $position < 6;
        });

        return $generator;
    }
}
