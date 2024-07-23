<?php

namespace Utils;

class Exception extends \Exception\GenericException
{
    // ----- ------ DATABASE EXCEPTIONS ------ ----- //
    const DATABASE_ERROR = 100;
    const DATABASE_STRUCTURE_ERROR = 101    ;

    protected static $excetions = [
        // Database exceptions
        self::DATABASE_ERROR => 'Database error',
        self::DATABASE_STRUCTURE_ERROR => 'Database structure error',
    ];

    protected static $tips = [
        self::DATABASE_STRUCTURE_ERROR => 'Please update the entity fields or the database fields.',
    ];

    public function message_100($data)
    {
        return 'Database error: ' . $data['error'];
    }

    public function message_101($data)
    {
        return 'Missmatch between entity fields and table \'' . $data['table'] . '\' fields : ' . implode(', ', array_keys($data['fields']));
    }
}
