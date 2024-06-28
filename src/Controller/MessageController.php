<?php

namespace Controller;

class MessageController extends AbstractController
{

    protected static $instance;
    protected $baseUrl = '/message';
    protected function initRoutes()
    {

    }

    public function provide_count()
    {
        return 3;
    }
}
