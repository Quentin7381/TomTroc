<?php

namespace Controller;
use View\Page;

class PageController extends AbstractController
{
    protected $baseUrl = '';

    public function initRoutes()
    {
        $this->router->addRoute('', [$this, 'index']);
        $this->router->addRoute('error/$', [$this, 'error']);
    }

    public function get($name)
    {
        return $this->manager->getByName($name);
    }

    public function index()
    {
        $this->view->print(Page::accueil(['activeLink' => '/']));
    }

    public function error($code)
    {
        $message = $_GET['message'] ?? null;
        $this->view->print(Page::error(['code' => $code, 'message' => $message]));
    }
}
