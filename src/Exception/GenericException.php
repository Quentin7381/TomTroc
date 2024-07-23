<?php

namespace Exception;

class GenericException extends \Exception {

    public $data;
    protected static $exceptions = [];
    protected static $tips = [];

    public function __construct($code, $data, $previous = null)
    {
        $message = "[$code] : ";

        if (method_exists($this, 'message_' . $code)) {
            $message .= $this->{'message_' . $code}($data);
        } else {
            $message .= self::$exceptions[$code];
        }

        if(!empty(self::$tips[$code])){
            $message .= PHP_EOL . self::$tips[$code];
        }

        parent::__construct($message, $code, $previous);
        $this->data = $data;
    }

}
