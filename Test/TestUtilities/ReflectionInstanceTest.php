<?php

namespace Test\TestUtilities;

require_once __DIR__ . '/../../vendor/autoload.php';

use Test\TestInit;
use Test\Reflection;
use Test\ReflectionInstance;
use stdClass;

use Mockery as m;

class ReflectionInstanceTest extends TestInit{

    protected $Reflection;
    protected $ReflectionInstance;

    function setUp() : void{
        parent::setUp();

        $this->Reflection = new \ReflectionClass(Reflection::class);

        $this->ReflectionInstance = new \ReflectionClass(ReflectionInstance::class);
    }

    # Magic methods

    ## __construct
    ### Links the Reflection object to the desired instance.
    function test__construct__LinksTheReflectionObjectToTheDesiredInstance(){
        $instance = new stdClass;
        $reflection = Reflection::_GET_INSTANCE(stdClass::class);

        $reflectionInstance = new ReflectionInstance($reflection, $instance);

        $actual = $this->ReflectionInstance->getProperty('reflection')->getValue($reflectionInstance);
        $this->assertSame($reflection, $actual);

        $actual = $this->ReflectionInstance->getProperty('instance')->getValue($reflectionInstance);
        $this->assertSame($instance, $actual);
    }

    ### Throws an exception if the instance is not an object.
    function test__construct__ThrowsAnExceptionIfTheInstanceIsNotAnObject(){
        $this->expectException(\Error::class);
        $this->expectExceptionMessage('Argument #2 ($instance) must be of type object, string given');

        $reflection = Reflection::_GET_INSTANCE(stdClass::class);

        new ReflectionInstance($reflection, 'not an object');
    }

    ### Throws an exception if the instance is not an instance of the target class.
    function test__construct__ThrowsAnExceptionIfTheInstanceIsNotAnInstanceOfTheTargetClass(){

        $instance = new stdClass;
        $reflection = Reflection::_GET_INSTANCE(Reflection::class);

        $this->expectException(\ReflectionException::class);
        $this->expectExceptionMessage('Argument #2 ($instance) must be an instance of Test\Reflection, instance of stdClass given');

        new ReflectionInstance($reflection, $instance);
    }

    ### Throws an exception if the reflection object is not an instance of the Reflection class.
    function test__construct__ThrowsAnExceptionIfTheReflectionObjectIsNotAnInstanceOfTheReflectionClass(){
        $this->expectException(\Error::class);
        $this->expectExceptionMessage('Argument #1 ($reflection) must be of type Test\Reflection, string given');

        $reflection = 'not an object';
        $instance = new stdClass;

        new ReflectionInstance($reflection, $instance);
    }

    ## __call
    ### Calls the target class method through the Reflection object.
    function test__call__CallsTheTargetClassMethodThroughTheReflectionObject(){
        $instance = new stdClass;
        $reflection = m::mock(Reflection::class);

        $reflection->shouldReceive('_GET_TARGET')
            ->once()
            ->andReturn(stdClass::class);

        $reflection->shouldReceive('_CALL')
            ->once()
            ->with('method', ['arg1', 'arg2'], $instance)
            ->andReturn('return value');

        $reflectionInstance = new ReflectionInstance($reflection, $instance);

        $actual = $reflectionInstance->method('arg1', 'arg2');

        $this->assertSame('return value', $actual);
    }

    ## __get
    ### Gets the target class property through the Reflection object.
    function test__get__GetsTheTargetClassPropertyThroughTheReflectionObject(){
        $instance = new stdClass;
        $reflection = m::mock(Reflection::class);

        $reflection->shouldReceive('_GET_TARGET')
            ->once()
            ->andReturn(stdClass::class);

        $reflection->shouldReceive('_GET')
            ->once()
            ->with('property', $instance)
            ->andReturn('property value');

        $reflectionInstance = new ReflectionInstance($reflection, $instance);

        $actual = $reflectionInstance->property;

        $this->assertSame('property value', $actual);
    }

    ## __set
    ### Sets the target class property through the Reflection object.
    function test__set__SetsTheTargetClassPropertyThroughTheReflectionObject(){
        $instance = new stdClass;
        $reflection = m::mock(Reflection::class);

        $reflection->shouldReceive('_GET_TARGET')
            ->once()
            ->andReturn(stdClass::class);

        $reflection->shouldReceive('_SET')
            ->once()
            ->with('property', 'value', $instance);

        $reflectionInstance = new ReflectionInstance($reflection, $instance);

        $reflectionInstance->property = 'value';

        $this->assertTrue(true);
    }
}
