<?php

namespace Variables;

class Exception extends \Exception
{

    // ----- ------ DATA EXCEPTIONS ------ ----- //
    const DATA_NOT_FOUND = 100;

    // ----- ------ PROVIDER EXCEPTIONS ------ ----- //
    const PROVIDER_NOT_FOUND = 200;
    const PROVIDER_ALREADY_EXISTS = 201;
    const PROVIDER_NOT_CALLABLE = 202;

    // ----- ------ STRUCTURE EXCEPTIONS ------ ----- //
    // const STRUCTURE_PATH_CONFLICT = 300; // Old
    const STRUCTURE_PATH_NOT_FOUND = 301;

    // ----- ------ VARIABLES EXCEPTIONS ------ ----- //
    const VARIABLE_NOT_FOUND = 400;

    public $data;

    protected static $exceptions = [
        100 => 'Data not found',
        200 => 'Provider not found',
        201 => 'Provider already exists',
        202 => 'Provider is not callable',
        // 300 => 'Path conflict', // Old
        301 => 'Path not found',
        400 => 'Variable not found',
    ];

    protected static $tips = [
        100 => 'You can set a new data provider for this data through the Provider::set method',
        200 => 'You can set a new provider through the Provider::set method',
        201 => 'You can remove the existing provider through the Provider::remove method',
        202 => 'Provider must receive a callable that will return the desired value',
        400 => 'You can set a new provider for this variable through the Provider::set method',
    ];

    public function __construct($code, $data, $previous = null)
    {
        if (method_exists($this, 'message_)' . $code)) {
            $message = $this->{'message_' . $code}($data);
        } else {
            $message = self::$exceptions[$code];
        }

        if(!empty(self::$tips[$code])){
            $message .= PHP_EOL . self::$tips[$code];
        }

        parent::__construct($message, $code, $previous);
        $this->data = $data;
    }

    protected function message_100($data)
    {
        return 'Data not found: ' . $data['key'];
    }

    protected function message_200($data)
    {
        return 'Provider not found: ' . $data['key'];
    }

    protected function message_201($data)
    {
        return 'Provider already exists: ' . $data['key'];
    }

    protected function message_301($data)
    {
        return 'Path not found: ' . $data['key'];
    }

    protected function message_400($data)
    {
        return 'Variable not found: ' . $data['key'];
    }
}

