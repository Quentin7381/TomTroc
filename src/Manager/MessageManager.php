<?php

namespace Manager;

use Entity\LazyEntity;
use Entity\Message;
use Entity\User;
use PDO;
use Utils\StatementGenerator;

class MessageManager extends AbstractManager
{

    public function getContacts(User $user): array
    {
        $sql = "SELECT * FROM message WHERE sender = :user OR receiver = :user";
        $query = $this->pdo->prepare($sql);
        $query->execute(['user' => $user->id]);

        $contacts = [];
        foreach ($query->fetchAll(PDO::FETCH_ASSOC) as $message) {
            $entity = new Message();
            $entity->fromDb($message);

            $contact = $entity->sender->id == $user->id ? $entity->receiver : $entity->sender;
            $contacts[$contact->id] = $contact;
        }

        return $contacts;
    }

    public function getThread(User|LazyEntity $user, User|LazyEntity $contact): StatementGenerator
    {
        $query = $this->pdo->prepare('SELECT * FROM message WHERE (sender = :user AND receiver = :contact) OR (sender = :contact AND receiver = :user)');
        @$query->bindParam(':user', $user->id, PDO::PARAM_INT);
        @$query->bindParam(':contact', $contact->id, PDO::PARAM_INT);
        
        $generator = new StatementGenerator($query);

        $generator->current_set_post_process(function ($data) {
            $message = new Message();
            $message->fromDb($data);
            return $message;
        });

        return $generator;
    }

    public function sendMessage(int $sender, int $receiver, string $content): void
    {
        $message = new Message();
        $message->sender = $sender;
        $message->receiver = $receiver;
        $message->content = $content;
        $message->persist();
    }

    public function setAsRead(int $user, int $contact): void
    {
        $sql = "UPDATE message SET checked = 1 WHERE sender = :contact AND receiver = :user";
        $query = $this->pdo->prepare($sql);
        $query->execute(['user' => $user, 'contact' => $contact]);
    }

    public function countNewMessages(?User $user): int
    {
        if(empty($user)) {
            return 0;
        }
        $sql = "SELECT COUNT(*) FROM message WHERE receiver = :user AND checked = 0";
        $query = $this->pdo->prepare($sql);
        $query->execute(['user' => $user->id]);

        return $query->fetchColumn();
    }

    public function typeof_content() {
        return 'text';
    }
}
