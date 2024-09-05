<?php

namespace Entity;

class Message extends AbstractEntity {
    protected ?int $id;
    protected string $content;
    protected User|LazyEntity $sender;
    protected User|LazyEntity $receiver;
    protected int $date;
    protected bool $checked;

    public function default_date() {
        return time();
    }

    public function checked_read() {
        return false;
    }

    public function set_sender(User|string|LazyEntity $sender) {
        if(is_string($sender)) {
            $sender = new LazyEntity(User::class, $sender);
        }

        $this->sender = $sender;
    }

    public function set_receiver(User|string|LazyEntity $receiver) {
        if(is_string($receiver)) {
            $receiver = new LazyEntity(User::class, $receiver);
        }

        $this->receiver = $receiver;
    }

    public function validate_content($content) {
        if (strlen($content) < 1) {
            throw new Exception(Exception::INVALID_PROPERTY_VALUE, ['rule' => 'Message content cannot be empty.', 'property' => 'message content']);
        }
    }

    public function get_hour() {
        return date('H:i', $this->date);
    }
}
