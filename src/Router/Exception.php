<?php

namespace Router;

class Exception extends \Exception\GenericException
{
    const ROUTE_ALREADY_EXISTS = 100;
    const ROUTE_NOT_FOUND = 101;

    protected static $exceptions = [
        100 => 'Route already exists.',
        101 => 'Route not found.',
    ];

    protected static $tips = [
        100 => 'You can remove the existing route through the Router::removeRoute method',
        101 => 'You can add a new route through the Router::addRoute method',
    ];

    public function message_100($data)
    {
        return 'Route already exists: ' . implode('/', $data['route']);
    }

    public function message_101($data)
    {
        return 'Route not found: ' . $data['route'];
    }
}
