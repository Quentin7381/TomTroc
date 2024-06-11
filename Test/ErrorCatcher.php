<?php

namespace Test;

class ErrorCatcher extends Singleton
{
    public static $I;
    public $ignore = [];
    public $caught = [];
    protected $handlers = [];

    protected function __construct()
    {
        parent::__construct();
        $this->caught = [];
        $this->handlers = [];
        $this->ignore = [];
    }

    protected function getActualHandlers()
    {
        $handlers = [];
        do {
            $handler = set_error_handler(function () {});
            restore_error_handler(); // remove the empty handler

            if ($handler !== null) {
                $handlers[] = $handler;
                restore_error_handler(); // remove the actual handler
            }
        } while (!empty($handler));

        $handlers = array_reverse($handlers);
        foreach ($handlers as $handler) {
            set_error_handler($handler);
        }

        return $handlers;
    }

    protected function restore($count = -1, $handlers = null)
    {

        $i = 0;
        while (count($this->handlers) > 0 && ($count === -1 || $i < $count)) {
            $handler = restore_error_handler();
            array_pop($this->handlers);
            $i++;
        }
    }

    protected function ignore($errno, $errstr, $errfile, $errline)
    {
        // ignore by number
        if (in_array($errno, $this->ignore['number'] ?? [])) {
            return true;
        }

        // ignore by message
        foreach ($this->ignore['message'] ?? [] as $ignore) {
            if (strpos($errstr, $ignore) !== false) {
                return true;
            }
        }

        // ignore by file
        foreach ($this->ignore['file'] ?? [] as $ignore) {
            if (strpos($errfile, $ignore) !== false) {
                return true;
            }
        }

        return false;
    }

    protected function throw()
    {
        $Catcher = $this;
        $this->handlers[] = set_error_handler(function ($errno, $errstr, $errfile, $errline) use ($Catcher) {
            if ($Catcher->ignore($errno, $errstr, $errfile, $errline)) {
                return;
            }

            throw new \Exception('Warning : ' . $errstr . ' in ' . $errfile . ' on line ' . $errline . ' with error number ' . $errno);
        });
    }

    protected function catch($times = 1)
    {
        $Catcher = $this;
        $handlersClosure = function () use ($Catcher) {
            return $Catcher->getActualHandlers();
        };

        $this->handlers[] = set_error_handler(function ($errno, $errstr, $errfile, $errline) use (&$times, $Catcher, &$handlersClosure) {
            if ($Catcher->ignore($errno, $errstr, $errfile, $errline)) {
                return;
            }

            // catch
            $Catcher->caught[] = [
                'message' => $errstr,
                'file' => $errfile,
                'line' => $errline,
                'code' => $errno
            ];

            if (--$times === 0) {
                $handlers = $handlersClosure();
                $Catcher->restore(1, $handlers);
            }
        });
    }

    protected function hasCaught(array $error, bool $strict = false)
    {
        foreach ($this->caught as $caught) {
            if ($strict) {
                foreach ($caught as $key => $value) {
                    if (!isset($error[$key]) || $value !== $error[$key]) {
                        continue 2;
                    }
                }

                return true;
            } else {
                foreach ($caught as $key => $value) {
                    if (isset($error[$key]) && strpos($value, $error[$key]) !== false) {
                        return true;
                    }
                }
            }
        }

        return false;
    }
}
