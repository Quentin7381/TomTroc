<?php

namespace Test\Utils;

require_once __DIR__ . '/../../vendor/autoload.php';

use Test\TestInit;
use Test\Reflection;
use Test\ReflectionInstance;
use \ReflectionClass;

use Utils\Singleton;

use Mockery as m;

class SingletonTest extends TestInit{

    function setUp() : void {
        $this->Singleton = Reflection::_GET_INSTANCE(Singleton::class);
    }

    ## __construct
    ### is protected
    function test__construct__isProtected(){
        $reflection = new ReflectionClass(Singleton::class);
        $this->assertTrue($reflection->hasMethod('__construct'));
        $this->assertTrue($reflection->getMethod('__construct')->isProtected());
    }

    ## getInstance
    ### if instance is null, instanciate the Singleton
    function testGetInstance__ifInstanceIsNull__instanciateTheSingleton(){
        $singleton = $this->Singleton->_NEW();
        $singleton->instance = null;
        $instance = $this->Singleton->getInstance();
        $this->assertEquals($instance, $singleton->instance);
    }

    ### if instance is set, return the same instance
    function testGetInstance__ifInstanceIsSet__returnTheSameInstance(){
        $singleton = $this->Singleton->_NEW();
        $instance1 = $this->Singleton->getInstance();
        $instance2 = $this->Singleton->getInstance();
        $this->assertSame($instance1, $instance2);
    }

    ## I
    ### calls getInstance
    function testI__callsGetInstance(){
        $singleton = $this->Singleton->_NEW();
        
        $mock = m::mock(Singleton::class);
        $mock->shouldReceive('getInstance')->once()->andReturn($mock);

        $singleton->instance = $mock;
        $this->assertEquals($singleton->I(), $mock);
    }

    ## __callStatic
    ### calls getInstance and the method upon the instance
    function test__callStatic__callsGetInstanceAndTheMethodUponTheInstance(){        
        $mock = m::mock(TestSingleton::class);
        $mock->shouldReceive('testMethod')->once()->andReturn('value');

        $TestSigleton = Reflection::_GET_INSTANCE(TestSingleton::class);
        $singleton = $TestSigleton->_NEW();

        $singleton->instance = $mock;
        $this->assertEquals($singleton->testMethod(), 'value');
    }
}

class TestSingleton extends Singleton{
    function testMethod(){
        return 'value';
    }
}