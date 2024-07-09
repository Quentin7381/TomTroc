<?php

namespace Controller;

use Entity\Book;
use Entity\Image;
use Entity\User;
use Utils\PDO;
use Utils\StatementGenerator;
use Manager\BookManager;

class BookController extends AbstractController
{

    protected static $instance;
    protected $baseUrl = '/book';
    protected function initRoutes()
    {

    }

    public function provide_lasts()
    {
        $pdo = PDO::getInstance();
        $sql = "SELECT * FROM book ORDER BY created DESC";
        $stmt = $pdo->prepare($sql);

        $generator = new StatementGenerator($stmt);

        $generator->current_set_post_process(function ($data) {
            $book = new Book();
            $book->fromDb($data);
            return $book;
        });

        return $generator;
    }
}
