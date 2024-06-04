<?php

namespace Entity;

class User extends AbstractEntity{
    protected int $id;
    protected string $username;
    protected string $password;
    protected string $email;

    function validate_password($password){
        if(strlen($password) < 8){
            throw new Exception("Password must be at least 8 characters long.");
        }

        if(!preg_match('/[A-Z]/', $password)){
            throw new Exception("Password must contain at least one uppercase letter.");
        }

        if(!preg_match('/[a-z]/', $password)){
            throw new Exception("Password must contain at least one lowercase letter.");
        }

        if(!preg_match('/[0-9]/', $password)){
            throw new Exception("Password must contain at least one number.");
        }

        if(!preg_match('/[^A-Za-z0-9]/', $password)){
            throw new Exception("Password must contain at least one special character.");
        }
    }

    function validate_email($email){
        if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
            throw new Exception("Invalid email address.");
        }
    }

    function validate_username($username){
        if(strlen($username) < 4){
            throw new Exception("Username must be at least 4 characters long.");
        }

        if(!preg_match('/^[a-zA-Z0-9_]+$/', $username)){
            throw new Exception("Username must contain only letters, numbers and underscores.");
        }
    }

    function set_password($password){
        $this->password = password_hash($password, PASSWORD_DEFAULT);
    }
}
