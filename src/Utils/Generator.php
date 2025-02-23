<?php

namespace Utils;

class Generator implements \Iterator
{
    protected $data = [];
    protected int $position = 0;
    protected $callbacks = [];


    public function __construct($data = [])
    {
        $this->data = $data;
    }

    // ----- ----- CALLBACKS ----- ----- //

    public function current_set_callback(callable $callback)
    {
        $this->callbacks['current'] = $callback;
    }

    public function key_set_callback(callable $callback)
    {
        $this->callbacks['key'] = $callback;
    }

    public function next_set_callback(callable $callback)
    {
        $this->callbacks['next'] = $callback;
    }

    public function rewind_set_callback(callable $callback)
    {
        $this->callbacks['rewind'] = $callback;
    }

    public function valid_set_callback(callable $callback)
    {
        $this->callbacks['valid'] = $callback;
    }

    public function current_set_post_process(callable $callback)
    {
        $this->callbacks['current_post_process'] = $callback;
    }

    // ----- ----- ITERATOR ----- ----- //

    public function current() : mixed
    {
        if (isset($this->callbacks['current'])) {
            $return = ($this->callbacks['current'])($this->data, $this->position);
        } else {
            $return = $this->data[$this->position];
        }

        if (isset($this->callbacks['current_post_process'])) {
            return ($this->callbacks['current_post_process'])($return);
        }

        return $return;
    }

    public function key() : mixed
    {
        if (isset($this->callbacks['key'])) {
            return ($this->callbacks['key'])($this->data, $this->position);
        }

        return $this->position;
    }

    public function next() : void
    {
        if (isset($this->callbacks['next'])) {
            ($this->callbacks['next'])($this->data, $this->position);
        }

        ++$this->position;
    }

    public function rewind() : void
    {
        if (isset($this->callbacks['rewind'])) {
            ($this->callbacks['rewind'])($this->data, $this->position);
        }

        $this->position = 0;
    }

    public function valid() : bool
    {
        if (isset($this->callbacks['valid'])) {
            return ($this->callbacks['valid'])($this->data, $this->position);
        }

        return isset($this->data[$this->position]);
    }

    // ----- ----- UTILS ----- ----- //
    public function getNexts(int $number) : array
    {
        $nexts = [];
        for ($i = 0; $i < $number; $i++) {
            $nexts[] = $this->current();
            $this->next();
            if (!$this->valid()) {
                break;
            }
        }

        return $nexts;
    }

    public function last() : mixed
    {
        $last = null;
        while ($this->valid()) {
            $last = $this->current();
            $this->next();
        }

        return $last;
    }
}
