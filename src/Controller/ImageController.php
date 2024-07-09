<?php

namespace Controller;

use Manager\ImageManager;

class ImageController extends AbstractController
{
    protected function initRoutes(){}

    public function provide_get(){
        $imageManager = ImageManager::getInstance();
        return [$imageManager, 'getByName'];
    }
}
