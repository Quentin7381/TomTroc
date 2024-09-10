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
        $this->router->addRoute('/user/edit/$', [$this, 'edit']);

        $this->router->addRoute('/user/$', [$this, 'page_user']);
    }

    public function page_connect(){
        echo Page::userConnect(['activeLink' => '/user/connect']);
    }

    public function page_register(){
        echo Page::userRegister(['activeLink' => '/user/connect']);
    }

    public function page_profile($id = null){
        if(!$id){
            $id = $this->manager->get_connected_user()->id;
        }

        $editable = $this->manager->get_connected_user()->id === $id;

        echo Page::personnalProfile(['editable' => $editable, 'activeLink' => '/user']);
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
        $current_user = $this->manager->get_connected_user();
        if ($current_user->id != $id){
            $this->redirect('/error/403?message=You are not allowed to edit another user');
        }

        try {
            $this->manager->edit($id, $_POST);
        } catch (\Manager\Exception $e){
            if ($e->getCode() === \Manager\Exception::USER_NOT_FOUND){
                $this->redirect('/error/404?message=No user with this id');
            }
        }

        $this->manager->updateSession(
            $this->manager->getById($id)
        );

        $this->redirect('/user/profile');
    }

    public function page_user($id){
        $user = $this->manager->getById($id);
        if (!$user){
            $this->redirect('/error/404?message=No user with this id');
        }

        echo Page::userProfile(['user' => $user, 'activeLink' => '/book/list']);
    }

}
