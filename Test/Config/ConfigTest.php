<?php

namespace Test\TestUtilities;

require_once __DIR__ . '/../../vendor/autoload.php';

use Test\TestInit;
use Test\Reflection;
use Test\ReflectionInstance;

use Config\Config;

use Mockery as m;

class ConfigTest extends TestInit{

    function setUp() : void{
        parent::setUp();

        $this->Config = Reflection::_GET_INSTANCE(Config::class);
        $config = $this->Config->getInstance();
        $this->Config->_SET('config', [], $config);
    }

    ## __get
    ### calls get
    function test__get__callsGet(){
        $mock = m::mock('Config\Config')->makePartial();
        $mock->shouldReceive('get')->once()->with('test')->andReturn('test');

        $mock->test;

        $this->assertTrue(true);
    }

    ## get
    ### throws exception if property is not set
    function test__get__throwsExceptionIfPropertyIsNotSet(){
        $this->expectException('Exception');
        $this->expectExceptionMessage('This property is not set!');

        $config = Config::getInstance();
        $config->get('nonExistentProperty');
    }

    ### returns value if property is set
    function test__get__returnsValueIfPropertyIsSet(){
        $config = Config::getInstance();
        $config->set('test', 'test');

        $this->assertEquals('test', $config->get('test'));
    }

    ## __set
    ### calls set
    function test__set__callsSet(){
        $mock = m::mock('Config\Config')->makePartial();
        $mock->shouldReceive('set')->once()->with('test', 'test');

        $mock->test = 'test';

        $this->assertTrue(true);
    }

    ## set
    ### throws exception if property is already set
    function test__set__throwsExceptionIfPropertyIsAlreadySet(){
        $this->expectException('Exception');
        $this->expectExceptionMessage('This property is already set!');

        $config = Config::getInstance();
        $config->set('test', 'test');
        $config->set('test', 'test');
    }

    ### sets property
    function test__set__setsProperty(){
        $config = Config::getInstance();
        $config->set('test', 'test');

        $this->assertEquals('test', $config->get('test'));
    }

    ## override
    ### sets property, even if it is already set
    function test__override__setsPropertyEvenIfItIsAlreadySet(){
        $config = Config::getInstance();
        $config->set('test', 'test');
        $config->override('test', 'test2');

        $this->assertEquals('test2', $config->get('test'));
    }

    ## load
    ### uses set for each config when override is false
    function test__load__usesSetForEachConfigWhenOverrideIsFalse(){
        $mock = m::mock('Config\Config')->makePartial();

        $mock->shouldReceive('set')->once()->with('test', 'test');
        $mock->shouldReceive('set')->once()->with('test2', 'test2');

        $mock->load(['test' => 'test', 'test2' => 'test2']);

        $this->assertTrue(true);
    }

    ### uses override for each config when override is assertTrue
    function test__load__usesOverrideForEachConfigWhenOverrideIsTrue(){
        $mock = m::mock('Config\Config')->makePartial();

        $mock->shouldReceive('override')->once()->with('test', 'test');
        $mock->shouldReceive('override')->once()->with('test2', 'test2');

        $mock->load(['test' => 'test', 'test2' => 'test2'], true);

        $this->assertTrue(true);
    }
}