<?php

namespace Manager;

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

    public function getThread(User $user, User $contact): StatementGenerator
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
}
