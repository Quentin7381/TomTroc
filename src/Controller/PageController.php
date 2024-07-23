<?php

namespace Controller;
use View\Page;

class PageController extends AbstractController
{
    protected $baseUrl = '';

    public function initRoutes()
    {
        $this->router->addRoute('', [$this, 'index']);
    }

    public function get($name)
    {
        return $this->manager->getByName($name);
    }

    public function index()
    {
        echo Page::accueil();
    }
}
