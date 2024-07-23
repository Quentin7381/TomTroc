<?php

namespace Controller;

use Manager\PictureManager;

class PictureController extends AbstractController
{
    
    protected $baseUrl = '/picture';
    protected function initRoutes()
    {
    }

    public function provide_get()
    {
        return [$this->manager, 'getByName'];
    }
}
