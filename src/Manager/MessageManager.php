<?php

namespace Manager;

use Entity\LazyEntity;
use Entity\Message;
use Entity\User;
use PDO;
use Utils\StatementGenerator;
use View\Page;

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

            $contact = (int) $entity->sender->id === (int) $user->id ? $entity->receiver : $entity->sender;
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
        if (empty($user)) {
            return 0;
        }
        $sql = "SELECT COUNT(*) FROM message WHERE receiver = :user AND checked = 0";
        $query = $this->pdo->prepare($sql);
        $query->execute(['user' => $user->id]);

        return $query->fetchColumn();
    }

    public function typeof_content()
    {
        return 'text';
    }

    public function get_messagerie_view($user)
    {
        // Recuperation des contacts
        $userManager = UserManager::getInstance();
        $contacts = $this->getContacts($user);

        // Gestions nouveau contact
        $newContact = $_SESSION['newContact'] ?? null;
        unset($_SESSION['newContact']);
        if(!$this->new_contact_is_valid($newContact)){
            $newContact = null;
        }

        // Gestion messagerie vide
        if(empty($contacts) && empty($newContact)) {
            return Page::messagerie([
                'user' => $user,
                'selected' => null,
                'phoneSelected' => false,
                'newContact' => null,
                'activeLink' => '/messagerie',
                'title' => 'Messagerie'
            ]);
            return;
        }

        // Selection du contact
        $firstContact = reset($contacts) ?? null;
        $phoneSelected = (!empty($_GET['id']) || !empty($newContact));
        $selectedId = $newContact->id ?? $_GET['id'] ?? reset($contacts)->id;

        // Gestions selection innexistante
        $selected = $userManager->getById($selectedId);
        if (empty($selected)) {
            $selectedId = $firstContact->id ?? null;
            $selected = $userManager->getById($selectedId);
            $phoneSelected = false;
        }

        // Renvoi de la vue
        $this->setAsRead($user->id, $selectedId);
        return Page::messagerie([
            'user' => $user,
            'selected' => $selected,
            'phoneSelected' => $phoneSelected,
            'newContact' => $newContact,
            'activeLink' => '/messagerie',
            'title' => 'Messagerie'
        ]);
    }

    public function new_contact_is_valid($newContact){
        $userManager = UserManager::getInstance();
        $user = $userManager->get_connected_user();
        $contacts = $this->getContacts($user);

        // New contact is not valid if it is empty
        if(empty($newContact)){
            return false;
        }

        // New contact is not valid if it is the user itself
        if($newContact->id == $user->id){
            return false;
        }

        // New contact is not valid if it already exists
        foreach ($contacts as $contact) {
            if ($contact->id == $newContact->id) {
                return false;
            }
        }
        return true;
    }

    public function typeof_sender() : string
    {
        return 'INT(6) NOT NULL';
    }

    public function typeof_receiver() : string
    {
        return 'INT(6) NOT NULL';
    }

    public function typeof_date() : string
    {
        return 'INT(11) NOT NULL';
    }
}
