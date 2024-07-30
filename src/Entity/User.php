<?php

namespace Entity;

class User extends AbstractEntity
{
    protected string $name;
    protected string $password;
    protected string $email;
    protected string|Image|LazyEntity $photo;
    protected int $created_at;

    function fromDb(array $data): void
    {
        parent::fromDb($data);
        $this->photo = new LazyEntity(Image::class, $data['photo']);
    }

    function validate_password($password)
    {
        if (strlen($password) < 8) {
            throw new Exception(Exception::INVALID_PROPERTY_VALUE, ['rule' => 'Password must be at least 8 characters long.', 'property' => 'user password']);
        }

        if (!preg_match('/[A-Z]/', $password)) {
            throw new Exception(Exception::INVALID_PROPERTY_VALUE, [
                'rule' => 'Password must contain at least one uppercase letter.',
                'property' => 'user password'
            ]);
        }

        if (!preg_match('/[a-z]/', $password)) {
            throw new Exception(Exception::INVALID_PROPERTY_VALUE, [
                'rule' => 'Password must contain at least one lowercase letter.',
                'property' => 'user password'
            ]);
        }

        if (!preg_match('/[0-9]/', $password)) {
            throw new Exception(Exception::INVALID_PROPERTY_VALUE, [
                'rule' => 'Password must contain at least one number.',
                'property' => 'user password'
            ]);
        }

        if (!preg_match('/[^A-Za-z0-9]/', $password)) {
            throw new Exception(Exception::INVALID_PROPERTY_VALUE, [
                'rule' => 'Password must contain at least one special character.',
                'property' => 'user password'
            ]);
        }
    }

    function validate_email($email)
    {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new Exception(Exception::INVALID_PROPERTY_VALUE, [
                'rule' => 'Email must be a valid email address.',
                'property' => 'user email'
            ]);
        }
    }

    function validate_username($username)
    {
        if (strlen($username) < 4) {
            throw new Exception(Exception::INVALID_PROPERTY_VALUE, [
                'rule' => 'Username must be at least 4 characters long.',
                'property' => 'user username'
            ]);
        }

        if (!preg_match('/^[a-zA-Z0-9_]+$/', $username)) {
            throw new Exception(Exception::INVALID_PROPERTY_VALUE, [
                'rule' => 'Username must contain only letters, numbers and underscores.',
                'property' => 'user username'
            ]);
        }
    }

    function set_password($password)
    {
        $this->password = password_hash($password, PASSWORD_DEFAULT);
    }

    static function typeof_photo()
    {
        return 'varchar(255) NOT NULL';
    }

    public function default_photo()
    {
        $photo = new Image();
        $photo->name = 'default-user-photo';
        $photo->hydrate();
        return $photo;
    }

    public function default_created_at()
    {
        return time();
    }

    public function get_account_age(){
        $now = time();
        $units = [
            'year' => 60 * 60 * 24 * 365,
            'month' => 60 * 60 * 24 * 30,
            'day' => 60 * 60 * 24,
            'hour' => 60 * 60,
            'minute' => 60,
            'second' => 1
        ];

        $diff = $now - $this->created_at;
        
        foreach ($units as $unit => $value) {
            if ($diff > $value) {
                $result = floor($diff / $value);
                return $result . ' ' . $unit . ($result > 1 ? 's' : '');
            }
        }
    }
}