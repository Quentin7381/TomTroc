<?php

namespace Test;

class TestInit extends \PHPUnit\Framework\TestCase
{

    public function setUp() : void
    {
        require_once __DIR__ . '/../vendor/autoload.php';
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
