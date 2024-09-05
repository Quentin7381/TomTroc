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
    }

    public function provide_count()
    {
        return 3;
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
        $selectedId = $_GET['id'] ?? null;
        echo Page::messagerie(['user' => $user, 'selectedId' => $selectedId]);
    }

    public function provide_contacts()
    {
        return [$this->manager, 'getContacts'];
    }
}
