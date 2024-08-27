<?php

namespace Exception;

class GenericException extends \Exception {

    public $data;
    protected static $exceptions = [];
    protected static $tips = [];

    public function __construct($code, $data = [], $previous = null)
    {
        $message = "[$code] : ";

        if (method_exists($this, 'message_' . $code)) {
            $message .= $this->{'message_' . $code}($data);
        } else {
            $message .= static::$exceptions[$code] ?? 'Unknown error';
        }

        if(!empty(static::$tips[$code])){
            $message .= PHP_EOL . static::$tips[$code];
        }

        if(!empty($data)){
            $message .= PHP_EOL . print_r($data, true);
        }

        parent::__construct($message, $code, $previous);
        $this->data = $data;
    }

}
