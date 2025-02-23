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
        $this->router->addRoute('/message/new/$/$', [$this, 'init_thread']);
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

    public function page_messagerie()
    {
        // Recuperation de l'utilisateur connecte
        $userManager = UserManager::getInstance();
        $user = $userManager->get_connected_user();
        if (empty($user)) {
            $this->redirect('/user/connect');
        }

        // Affichage de la messagerie
        $view = $this->manager->get_messagerie_view($user);
        $this->view->print($view);
    }

    public function provide_contacts()
    {
        return [$this->manager, 'getContacts'];
    }

    public function send($sender, $receiver)
    {
        $userManager = UserManager::getInstance();
        if ($userManager->get_connected_user()->id != $sender) {
            $this->redirect('error/403?message=Vous n\'avez pas le droit d\'envoyer un message au nom de quelqu\'un d\'autre');
        }

        $content = $_POST['content'];
        $this->manager->sendMessage($sender, $receiver, $content);
        $this->redirect('/messagerie?id=' . $receiver);
    }

    public function init_thread($sender, $receiver)
    {
        $userManager = UserManager::getInstance();
        if ($userManager->get_connected_user()->id != $sender) {
            $this->redirect('error/403?message=Vous n\'avez pas le droit d\'envoyer un message au nom de quelqu\'un d\'autre');
        }

        $user = $userManager->getById($receiver);

        $_SESSION['newContact'] = $user;

        $this->redirect('/messagerie?id=' . $receiver);
    }
}
