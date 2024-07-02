<?php

namespace Test;

require_once __DIR__ . '/../vendor/autoload.php';

use Config\Config;
use Utils\PDO;

class TestInit extends \PHPUnit\Framework\TestCase
{

    public function setUp() : void
    {
        Config::getInstance()->load(__DIR__ . '/');
        PDO::resetDatabase();
        ErrorCatcher::throw();
    }

    public function tearDown(): void
    {
        ErrorCatcher::restore();
    }

    public function testPhpunitWorks()
    {
        $this->assertTrue(true);
    }
}
