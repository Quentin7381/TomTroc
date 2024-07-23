<?php

namespace Controller;

use Manager\ImageManager;

class ImageController extends AbstractController
{
    protected $baseUrl = '/image';
    protected function initRoutes()
    {
    }

    public function provide_get()
    {
        return [$this->manager, 'getByName'];
    }
}
