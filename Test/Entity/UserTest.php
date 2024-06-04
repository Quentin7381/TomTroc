<?php

namespace Test\Entity;

require_once __DIR__ . '/../../vendor/autoload.php';

use Entity\AbstractEntity;
use Entity\Exception;
use Test\Reflection;
use Test\ReflectionInstance;
use Mockery as m;

class UserTest extends AbstractEntityTest {

    # ----- VALIDATION METHODS -----

    ## validate_password
    ### throws an exception if the password is less than 8 characters long
    function test__validate_password__throws_exception_if_password_is_less_than_8_characters_long(){
        $this->expectException(Exception::class);
        $this->expectExceptionMessage("Password must be at least 8 characters long.");

        $entity = new $this->class;
        $entity->validate_password('pass');
    }

    ### throws an exception if the password does not contain at least one uppercase letter
    function test__validate_password__throws_exception_if_password_does_not_contain_at_least_one_uppercase_letter(){
        $this->expectException(Exception::class);
        $this->expectExceptionMessage("Password must contain at least one uppercase letter.");

        $entity = new $this->class;
        $entity->validate_password('password');
    }    

    ### throws an exception if the password does not contain at least one lowercase letter
    function test__validate_password__throws_exception_if_password_does_not_contain_at_least_one_lowercase_letter(){
        $this->expectException(Exception::class);
        $this->expectExceptionMessage("Password must contain at least one lowercase letter.");

        $entity = new $this->class;
        $entity->validate_password('PASSWORD');
    }

    ### throws an exception if the password does not contain at least one number
    function test__validate_password__throws_exception_if_password_does_not_contain_at_least_one_number(){
        $this->expectException(Exception::class);
        $this->expectExceptionMessage("Password must contain at least one number.");

        $entity = new $this->class;
        $entity->validate_password('Password');
    }

    ### throws an exception if the password does not contain at least one special character
    function test__validate_password__throws_exception_if_password_does_not_contain_at_least_one_special_character(){
        $this->expectException(Exception::class);
        $this->expectExceptionMessage("Password must contain at least one special character.");

        $entity = new $this->class;
        $entity->validate_password('Password1');
    }

    ### lets through a valid password
    function test__validate_password__lets_through_a_valid_password(){
        $entity = new $this->class;
        $entity->validate_password('Password1!');
        $this->assertTrue(true);
    }

    ## validate_email
    ### throws an exception if the email address is invalid
    function test__validate_email__throws_exception_if_email_address_is_invalid(){
        $this->expectException(Exception::class);
        $this->expectExceptionMessage("Invalid email address.");

        $entity = new $this->class;
        $entity->validate_email('invalid');
    }

    ### lets through a valid email address
    function test__validate_email__lets_through_a_valid_email_address(){
        $entity = new $this->class;
        $entity->validate_email('mail@test.com');
        $this->assertTrue(true);
    }

    ## set_password
    ### hashes the password using password_hash
    function test__set_password__hashes_the_password_using_password_hash(){
        $entity = new $this->class;
        $entity->set_password('password');

        $this->assertNotEquals('password', $entity->password);
    }
}
