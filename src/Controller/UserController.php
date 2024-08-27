<?php

namespace Controller;
use View\Page;

class UserController extends AbstractController
{
    protected function initRoutes(){
        $this->router->addRoute('/user/connect', [$this, 'page_connect']);
        $this->router->addRoute('/user/request/login', [$this, 'login']);
        $this->router->addRoute('/user/disconnect', [$this, 'disconnect']);

        $this->router->addRoute('/user/register', [$this, 'page_register']);
        $this->router->addRoute('/user/request/register', [$this, 'register']);

        $this->router->addRoute('/user', [$this, 'page_profile']);
        $this->router->addRoute('/user/profile', [$this, 'page_profile']);
        $this->router->addRoute('/user/update/photo', [$this, 'update_photo']);
        $this->router->addRoute('/user/edit/$id', [$this, 'edit']);
    }

    public function page_connect(){
        echo Page::userConnect();
    }

    public function page_register(){
        echo Page::userRegister();
    }

    public function page_profile(){
        echo Page::personnalProfile();
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

    public function register(){
        if (!isset($_POST['email']) || !isset($_POST['password']) || !isset($_POST['name'])){
            echo Page::error(['code' => 422]);
            return;
        }

        if($this->manager->register($_POST['email'], $_POST['password'], $_POST['name'])){
            $this->redirect('/user/profile');
        } else {
            $this->redirect('/user/register');
        }
    }

    public function disconnect(){
        $this->manager->logout();
        $this->redirect('/');
    }

    public function provide_connected(){
        return !empty($this->manager->get_connected_user());
    }

    public function provide_current(){
        return $this->manager->get_connected_user();
    }

    public function update_photo(){
        if (!isset($_FILES['photo'])){
            $this->redirect('/error/422?message=No photo provided');
            return;
        }

        try {
            $this->manager->update_photo($this->manager->get_connected_user(), $_FILES['photo']);
        } catch (\Manager\Exception $e){
            if ($e->getCode() === \Manager\Exception::USER_INVALID_IMAGE_EXTENSION){
                $this->redirect('/error/422?message=Invalid image extension');
            }
        }
        
        $this->redirect('/user/profile');
    }

    public function edit($id){
        if (!isset($_POST['name'])){
            echo Page::error(['code' => 422]);
            return;
        }

        $this->manager->edit($id, $_POST);
        $this->redirect('/user/profile');
    }
}
