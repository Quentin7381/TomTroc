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
        $this->router->addRoute('/book/add/submit', [$this, 'add']);
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

    public function add()
    {
        if(
            empty($_POST['title']) ||
            empty($_POST['author']) ||
            empty($_POST['description']) ||
            empty($_FILES['photo'])
        ) {
            $this->redirect('/error/?message=missing data');
        }
        
        $user = \Manager\UserManager::getInstance()->get_connected_user();
        $this->manager->add_book($_POST['title'], $_POST['author'], $_POST['description'], $_FILES['photo'], $user);
        
        $this->redirect('/user/');
    }
}
