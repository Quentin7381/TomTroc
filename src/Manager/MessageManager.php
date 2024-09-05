<?php

namespace Manager;

use Entity\Message;
use Entity\User;
use PDO;

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

    public function getChat(User $user, User $contact): array
    {
        $query = $this->pdo->prepare('SELECT * FROM message WHERE (sender = :user AND receiver = :contact) OR (sender = :contact AND receiver = :user)');
        $query->execute(['user' => $user->id, 'contact' => $contact->id]);

        $messages = [];
        foreach ($query->fetchAll() as $message) {
            $entity = new Message();
            $entity->fromDb($message);
            $messages[$entity->date] = $entity;
        }

        return $messages;
    }
}
