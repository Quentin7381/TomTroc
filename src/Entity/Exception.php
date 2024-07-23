<?php

namespace Entity;

class Exception extends \Exception\GenericException
{
    const INVALID_PROPERTY_VALUE = 100;

    protected static $excetions = [
        self::INVALID_PROPERTY_VALUE => 'Invalid property value.',
    ];

    protected static $tips = [
    ];

    public function message_1100($data)
    {
        return 'Invalid ' . $data['property'] . ' value.' . $data['rule'];
    }
}
