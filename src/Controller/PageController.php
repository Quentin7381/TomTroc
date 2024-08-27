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
        $this->router->addRoute('dev/fill-db', [$this, 'fillDb']);
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
        $message = $_GET['message'] ?? null;
        echo Page::error(['code' => $code, 'message' => $message]);
    }

    public function fillDb()
    {
        require_once __DIR__ . '../../../dbInsert.php';
    }
}
