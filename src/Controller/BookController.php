<?php

namespace Controller;

use Entity\Book;
use Utils\PDO;
use Utils\StatementGenerator;
use View\Page;

class BookController extends AbstractController
{

    protected static $instance;
    protected $baseUrl = '/book';
    protected function initRoutes()
    {
        $this->router->addRoute('/book/add', [$this, 'page_add']);
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

    public function page_add()
    {
        $userManager = \Manager\UserManager::getInstance();
        $user = $userManager->get_connected_user();
        echo Page::bookEdit(['user' => $user]);
    }
}
