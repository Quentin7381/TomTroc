<?php

namespace Test;

class TestInit extends \PHPUnit\Framework\TestCase
{

    public function setUp() : void
    {
        require_once __DIR__ . '/../vendor/autoload.php';
    }

    public function testPhpunitWorks()
    {
        $this->assertTrue(true);
    }
}