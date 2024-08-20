<?php

namespace Entity;

use Manager\UserManager;

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

    public function set_photo(string|Image|LazyEntity $photo)
    {
        if (is_string($photo)) {
            $this->photo = new LazyEntity(Image::class, $photo);
        } else {
            $this->photo = $photo;
        }
    }

    public function default_created_at()
    {
        return time();
    }

    /**
     * Get the value of created_at
     *
     * The value is returned in seconds, minutes, hours, days, months or years
     * Choosen unit is the biggest possible, and the value is rounded down
     * Plural is added to the unit if the value is greater than 1
     *
     * @return string like "2 years", "3 months", "1 day"
     */
    public function get_account_age(){
        $now = time();
        $units = [
            'an' => 60 * 60 * 24 * 365,
            'mois' => 60 * 60 * 24 * 30,
            'jour' => 60 * 60 * 24,
            'heure' => 60 * 60,
            'minute' => 60,
            'seconde' => 1
        ];

        $diff = $now - $this->created_at;
        
        foreach ($units as $unit => $value) {
            if ($diff > $value) {
                // Calculate the number of units
                $result = floor($diff / $value);

                // Add plural if needed
                $result = $result . ' ' . $unit . ($result > 1 ? 's' : '');

                // Avoid multiple s for months
                $result = str_replace('ss', 's', $result);

                return $result;
            }
        }
    }

    public function get_library_size(){
        $manager = UserManager::getInstance();
        return $manager->get_library_size($this);
    }

    public function get_books(){
        $manager = UserManager::getInstance();
        return $manager->get_books($this);
    }
}
