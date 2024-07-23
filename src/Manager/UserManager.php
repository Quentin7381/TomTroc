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

        $_SESSION['user'] = $result;
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

}
