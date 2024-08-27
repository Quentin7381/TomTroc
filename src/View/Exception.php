<?php

namespace View;

class Exception extends \Exception\GenericException
{
    const EXTENSION_NOT_ALLOWED = 100;

    // ----- ------ RENDERABLE EXCEPTIONS ------ ----- //
    const PROPERTY_NOT_FOUND = 200;

    protected static $excetions = [
        self::EXTENSION_NOT_ALLOWED => 'Extension not allowed',

        // Renderable exceptions
        self::PROPERTY_NOT_FOUND => 'Property not found',
    ];

    protected static $tips = [
        self::EXTENSION_NOT_ALLOWED => 'Valid extensions are .tpl, .css, .js',
    ];

    public function message_100($data)
    {
        return 'Extension not allowed: ' . $data['extension'];
    }

    public function message_200($data)
    {
        $message = 'Property not found !' . PHP_EOL;
        $message .= print_r($data, true);
        return $message;
    }
}
