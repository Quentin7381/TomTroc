<?php

namespace Utils;

class Exception extends \Exception\GenericException
{
    // ----- ------ DATABASE EXCEPTIONS ------ ----- //
    const DATABASE_ERROR = 1000;
    const DATABASE_STRUCTURE_ERROR = 1100;

    // ----- ------ RENDERABLE EXCEPTIONS ------ ----- //
    const PROPERTY_NOT_FOUND = 2000;

    protected static $excetions = [
        // Database exceptions
        self::DATABASE_ERROR => 'Database error',
        self::DATABASE_STRUCTURE_ERROR => 'Database structure error',

        // Renderable exceptions
        self::PROPERTY_NOT_FOUND => 'Property not found',
    ];

    protected static $tips = [
        self::DATABASE_STRUCTURE_ERROR => 'Please update the entity fields or the database fields.',
    ];

    public function message_1000($data)
    {
        return 'Database error: ' . $data['error'];
    }

    public function message_1100($data)
    {
        return 'Missmatch between entity fields and table \'' . $data['table'] . '\' fields : ' . implode(', ', array_keys($data['fields']));
    }

    public function message_2000($data)
    {
        return 'Property not found: ' . $data['property'];
    }
}
