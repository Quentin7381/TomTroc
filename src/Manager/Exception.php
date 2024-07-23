<?php

namespace Manager;

class Exception extends \Exception\GenericException
{
    const DATABASE_ERROR = 100;

    protected static $excetions = [
        self::DATABASE_ERROR => 'Database error',
    ];

    protected static $tips = [];

    public function message_100($data)
    {
        return 'Database error: ' . $data['error'];
    }
}
