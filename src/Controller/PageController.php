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
        echo Page::accueil();
    }

    public function error($code)
    {
        echo Page::error(['code' => $code]);
    }
}
