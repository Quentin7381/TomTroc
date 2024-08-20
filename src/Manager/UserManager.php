<?php

namespace Manager;

class UserManager extends AbstractManager{

    public function login($email, $password){
        $sql = 'SELECT * FROM user WHERE email = :email';
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['email' => $email]);

        $result = $stmt->fetch(\PDO::FETCH_ASSOC);

        if (!$result){
            return false;
        }

        if (!password_verify($password, $result['password'])){
            return false;
        }

        $user = new \Entity\User();
        $user->fromDb($result);

        $_SESSION['user'] = $user;
        return true;
    }

    public function register($email, $password, $name){
        $sql = 'INSERT INTO user (email, password, name) VALUES (:email, :password, :name)';
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            'email' => $email,
            'password' => password_hash($password, PASSWORD_DEFAULT),
            'name' => $name
        ]);

        return $this->login($email, $password);
    }

    public function logout(){
        unset($_SESSION['user']);
    }

    public function get_connected_user(){
        return $_SESSION['user'] ?? null;
    }

    public function get_library_size($user){
        $sql = 'SELECT COUNT(*) FROM book WHERE seller = :seller';
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['seller' => $user->id]);
        return $stmt->fetchColumn();
    }

    public function get_books($user){
        $sql = 'SELECT * FROM book WHERE seller = :seller';
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['seller' => $user->id]);
        $results = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        foreach ($results as $key => $result){
            $results[$key] = new \Entity\Book();
            $results[$key]->fromDb($result);
        }

        return $results;
    }

}
