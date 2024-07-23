<?php

namespace View;

class Exception extends \Exception\GenericException
{
    const EXTENSION_NOT_ALLOWED = 100;

    protected static $excetions = [
        self::EXTENSION_NOT_ALLOWED => 'Extension not allowed',
    ];

    protected static $tips = [
        self::EXTENSION_NOT_ALLOWED => 'Valid extensions are .tpl, .css, .js',
    ];

    public function message_100($data)
    {
        return 'Extension not allowed: ' . $data['extension'];
    }
}
