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
        $this->router->addRoute('/book/edit/$', [$this, 'page_edit']);
        $this->router->addRoute('/book/edit/$/submit', [$this, 'edit']);
        $this->router->addRoute('/book/delete/$', [$this, 'delete']);
        $this->router->addRoute('/book/list', [$this, 'page_list']);
        $this->router->addRoute('/book/$', [$this, 'page_full']);
    }

    public function provide_lasts()
    {
        return $this->manager->getLasts();
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
            !isset($_POST['title']) ||
            !isset($_POST['author']) ||
            !isset($_POST['description']) ||
            !isset($_POST['available']) ||
            !isset($_FILES['photo'])
        ) {
            $this->redirect('/error/?message=missing data');
        }
        
        $user = \Manager\UserManager::getInstance()->get_connected_user();
        $this->manager->add_book($_POST['title'], $_POST['author'], $_POST['description'],$_POST['available'], $_FILES['photo'], $user);
        
        $this->redirect('/user/');
    }

    public function page_edit($id)
    {
        $userManager = \Manager\UserManager::getInstance();
        $user = $userManager->get_connected_user();
        echo Page::bookEdit(['user' => $user, 'book' => $this->manager->getById($id)]);
    }

    public function edit($id)
    {
        if(
            !isset($_POST['title']) ||
            !isset($_POST['author']) ||
            !isset($_POST['description']) ||
            !isset($_POST['available'])
        ) {
            var_dump($_POST); exit;
            $this->redirect('/error/?message=missing data');
        }
        
        $user = \Manager\UserManager::getInstance()->get_connected_user();
        $this->manager->edit_book($id, $_POST['title'], $_POST['author'], $_POST['description'], $_POST['available'], $_FILES['photo'], $user);
        
        $this->redirect('/user/');
    }

    public function delete($id)
    {
        $this->manager->delete($id);
        $this->redirect('/user/');
    }

    public function page_list()
    {
        echo Page::bookList();
    }

    public function page_full($id)
    {
        echo Page::bookFull(['book' => $this->manager->getById($id)]);
    }
}
