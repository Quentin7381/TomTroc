<?php

namespace Controller;
use View\Page;

class UserController extends AbstractController
{
    protected function initRoutes(){
        $this->router->addRoute('/user/connect', [$this, 'page_connect']);
        $this->router->addRoute('/user/request/login', [$this, 'login']);
        $this->router->addRoute('/user/disconnect', [$this, 'disconnect']);
    }

    public function page_connect(){
        echo Page::userConnect();
    }

    public function login(){
        if (!isset($_POST['email']) || !isset($_POST['password'])){
            echo Page::error(['code' => 422]);
            return;
        }

        if($this->manager->login($_POST['email'], $_POST['password'])){
            $this->redirect('/user/profile');
        } else {
            $this->redirect('/user/connect');
        }
    }

    public function disconnect(){
        $this->manager->logout();
        $this->redirect('/');
    }

    public function provide_connected(){
        return !empty($this->manager->get_connected_user());
    }
}
