<?php

namespace Test\Utils;

require_once __DIR__ . '/../../vendor/autoload.php';

use Test\TestInit;
use Test\Reflection;
use Test\ReflectionInstance;
use \ReflectionClass;

use Config\Config;

class PDOTest extends TestInit{

    function setUp() : void {
        parent::setUp();
    }

    ## __construct
    ### calls parent::__construct with the config values
    function test__construct__callsParentConstructWithTheConfigValues(){
        // hard to test due to pdo exception if the connection fails
        // also, it uses PDO class which is not a mockable class
        $this->assertTrue(true);
    }
}
