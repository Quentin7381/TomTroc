<?php

namespace Controller;

use Manager\UserManager;
use View\Page;

class MessageController extends AbstractController
{

    protected static $instance;
    protected $baseUrl = '/message';
    protected function initRoutes()
    {
        $this->router->addRoute('/messagerie/', [$this, 'page_messagerie']);
        $this->router->addRoute('/message/send/$/$', [$this, 'send']);
    }

    public function provide_count()
    {
        $userManager = UserManager::getInstance();
        $user = $userManager->get_connected_user();
        return $this->manager->countNewMessages($user);
    }

    public function provide_thread()
    {
        return [$this->manager, 'getThread'];
    }

    public function page_messagerie(){
        $userManager = UserManager::getInstance();
        $user = $userManager->get_connected_user();

        if(!$user){
            $this->redirect('/login');
        }
        @$selectedId = $_GET['id'] ?? reset($this->manager->getContacts($user))->id;

        $this->manager->setAsRead($user->id, $selectedId);

        echo Page::messagerie(['user' => $user, 'selectedId' => $selectedId]);
    }

    public function provide_contacts()
    {
        return [$this->manager, 'getContacts'];
    }

    public function send($sender, $receiver){
        $userManager = UserManager::getInstance();
        if($userManager->get_connected_user()->id != $sender){
            $this->redirect('error/403?message=Vous n\'avez pas le droit d\'envoyer un message au nom de quelqu\'un d\'autre');
        }

        $content = $_POST['content'];
        $this->manager->sendMessage($sender, $receiver, $content);
        $this->redirect('/messagerie?id='.$receiver);
    }
}
