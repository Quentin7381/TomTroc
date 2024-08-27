<?php

namespace Manager;

class Exception extends \Exception\GenericException
{
    const DATABASE_ERROR = 100;
    const ENTITY_UNIQUE_VALUES_COLLISION = 101;
    const USER_INVALID_IMAGE_EXTENSION = 201;

    protected static $excetions = [
        self::DATABASE_ERROR => 'Database error',
        self::ENTITY_UNIQUE_VALUES_COLLISION => 'Entity unique values collision',
    ];

    protected static $tips = [
        self::ENTITY_UNIQUE_VALUES_COLLISION => 'You need to remove the duplicate values from the entity.',
    ];

    public function message_100($data)
    {
        return 'Database error: ' . $data['error'];
    }

    public function message_101($data)
    {
        return 'Entity unique values collision: ' . PHP_EOL . json_encode($data, JSON_PRETTY_PRINT);
    }

    public function message_201($data)
    {
        return 'Invalid image extension: ' . $data['extension'] . PHP_EOL . 'Allowed extensions are ' . implode(', ', $data['allowed']);
    }
}
