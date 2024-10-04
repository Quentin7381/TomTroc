<?php

namespace Manager;

use Entity\User;
use Entity\AbstractEntity;
use Entity\Image;
use Entity\LazyEntity;

class UserManager extends AbstractManager
{

    public function login($email, $password)
    {
        $sql = 'SELECT * FROM user WHERE email = :email';
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['email' => $email]);

        $result = $stmt->fetch(\PDO::FETCH_ASSOC);

        if (!$result) {
            return false;
        }

        if (!password_verify($password, $result['password'])) {
            return false;
        }

        $user = new User();
        $user->fromDb($result);

        $_SESSION['user'] = $user;
        return true;
    }

    public function register($email, $password, $name)
    {
        $user = new User();
        $user->fromArray([
            'email' => $email,
            'name' => $name,
            'password' => $password
        ]);

        $user->insert();

        return $this->login($email, $password);
    }

    public function logout()
    {
        unset($_SESSION['user']);
    }

    public function get_connected_user()
    {
        return $_SESSION['user'] ?? null;
    }

    public function get_library_size($user)
    {
        $sql = 'SELECT COUNT(*) FROM book WHERE seller = :seller';
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['seller' => $user->id]);
        return $stmt->fetchColumn();
    }

    public function get_books($user)
    {
        $sql = 'SELECT * FROM book WHERE seller = :seller';
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['seller' => $user->id]);
        $results = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        foreach ($results as $key => $result) {
            $results[$key] = new \Entity\Book();
            $results[$key]->fromDb($result);
        }

        return $results;
    }

    public function fromDb(?AbstractEntity $entity, array $array): AbstractEntity
    {
        if (!$entity) {
            $entity = new User();
        }

        $entity = parent::fromDb($entity, $array);

        $entity->password_hash = $array['password'];
        $entity->photo = new LazyEntity(Image::class, $array['photo']);

        return $entity;
    }

    public function update_photo($user, $photo)
    {
        $extension = pathinfo($photo['name'], PATHINFO_EXTENSION);
        $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        if (!in_array($extension, $allowed)) {
            throw new Exception(Exception::USER_INVALID_IMAGE_EXTENSION, [
                'allowed' => $allowed,
                'extension' => $extension
            ]);
        }

        $user->photo->delete();
        $user->photo = new Image();
        $user->photo->name = uniqid() . '.' . $extension;
        $user->photo->src = '/public/img/users/' . $user->photo->name;
        $user->photo->persist();

        $path = str_replace('/public', 'public', $user->photo->src);
        move_uploaded_file($photo['tmp_name'], $path);
    }

    public function edit(int $id)
    {
        $user = new User();
        $user->id = $id;
        $user = $user->exists();

        if (!$user) {
            throw new Exception(Exception::USER_NOT_FOUND);
        }

        $user->fromArray($_POST);
        // var_dump($user); exit;
        $user->persist();
    }

    public function merge(AbstractEntity|array ...$entities): AbstractEntity
    {
        $entity = array_shift($entities);
        if (!$entity instanceof AbstractEntity) {
            $class = 'Entity\\' . $this->getEntityName();
            $e = new $class();
            $e->fromArray($entity);
            $entity = $e;
        }

        foreach ($entities as $merge) {
            if (!is_array($merge)) {
                $merge = $merge->toArray();
            }
            foreach ($merge as $field => $value) {
                if (empty($value)) {
                    continue;
                }

                if ($field === 'password') {
                    $entity->set_password_hash($value);
                    continue;
                }

                $entity->$field = $value;
            }
        }

        return $entity;
    }

    public function updateSession(User $user)
    {
        $_SESSION['user'] = $user;
    }

    public function typeof_photo()
    {
        return 'INT(6) UNSIGNED NOT NULL';
    }

    public function typeof_created_at()
    {
        return 'INT(11) NOT NULL';
    }
}
