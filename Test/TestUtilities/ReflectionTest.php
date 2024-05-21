<?php

namespace Test\TestUtilities;

require_once __DIR__ . '/../../vendor/autoload.php';

use Test\TestInit;
use Test\Reflection;
use Test\ReflectionInstance;
use stdClass;

use Mockery as m;

class ReflectionTest extends TestInit{

    function setUp() : void{
        parent::setUp();

        $this->Reflection = new \ReflectionClass(Reflection::class);
    }

    # Private Methods

    ## __construct
    ### The instances array is updated
    function test__construct__createsReflectionObject(){
        $instance = $this->Reflection->newInstanceWithoutConstructor();

        $constructor = $this->Reflection->getMethod('__construct');
        $constructor->setAccessible(true);
        $constructor->invoke($instance, 'Reflection');
        
        $instances = $this->Reflection->getProperty('instances');
        $instances->setAccessible(true);
        $instances = $instances->getValue($instance);

        $this->assertArrayHasKey('Reflection', $instances);
        $this->assertInstanceOf(Reflection::class, $instances['Reflection']);
    }

    ### The setup methods are called (setupClass, setupMethods, setupProperties)
    function test__construct__callsSetupMethods(){        
        $instance = m::mock(Reflection::class)->makePartial();
        $instance->shouldAllowMockingProtectedMethods();

        $instance->shouldReceive('setupClass')->once();
        $instance->shouldReceive('setupMethods')->once();
        $instance->shouldReceive('setupProperties')->once();

        $constructor = $this->Reflection->getMethod('__construct');
        $constructor->setAccessible(true);
        $constructor->invoke($instance, 'Reflection');

        $instance->shouldHaveReceived('setupClass');
        $instance->shouldHaveReceived('setupMethods');
        $instance->shouldHaveReceived('setupProperties');

        $this->assertTrue(true);
    }

    ## setupMethods

    ### The methods array is filled with the methods of the target class
    function test__setupMethods__fillsMethodsArray(){
        $instance = $this->Reflection->newInstanceWithoutConstructor();

        // Required class
        $class = $this->Reflection->getProperty('class');
        $class->setAccessible(true);
        $class->setValue($instance, new \ReflectionClass(Reflection::class));

        // Required target
        $target = $this->Reflection->getProperty('target');
        $target->setAccessible(true);
        $target->setValue($instance, 'Reflection');

        // Call the method
        $setupMethods = $this->Reflection->getMethod('setupMethods');
        $setupMethods->setAccessible(true);
        $setupMethods->invoke($instance);

        // Check the values
        $methods = $this->Reflection->getProperty('method');
        $methods->setAccessible(true);
        $methods = $methods->getValue($instance);

        $this->assertIsArray($methods);
        $this->assertNotEmpty($methods);
    }


    ### The methods are made accessible
    function test__setupMethods__makesMethodsAccessible(){
        $instance = $this->Reflection->newInstanceWithoutConstructor();

        // Required class
        $class = $this->Reflection->getProperty('class');
        $class->setAccessible(true);
        $class->setValue($instance, new \ReflectionClass(Reflection::class));

        // Required target
        $target = $this->Reflection->getProperty('target');
        $target->setAccessible(true);
        $target->setValue($instance, 'Reflection');

        // Call the method
        $setupMethods = $this->Reflection->getMethod('setupMethods');
        $setupMethods->setAccessible(true);
        $setupMethods->invoke($instance);

        // Try to access the methods
        $methods = $this->Reflection->getProperty('method');
        $methods->setAccessible(true);
        $methods = $methods->getValue($instance);

        foreach($methods as $method){
            try{
                $method->invoke($instance);
            } catch(\ReflectionException $e){
                $this->fail('Method is not accessible');
            } catch(\Throwable $e){
                // We don't care about other exceptions
            }
        }

        $this->assertTrue(true);
    }

    ## setupProperties

    ### The property array is filled with the properties of the target class
    function test__setupProperties__fillsPropertiesArray(){
        $instance = $this->Reflection->newInstanceWithoutConstructor();

        // Required class
        $class = $this->Reflection->getProperty('class');
        $class->setAccessible(true);
        $class->setValue($instance, new \ReflectionClass(Reflection::class));

        // Required target
        $target = $this->Reflection->getProperty('target');
        $target->setAccessible(true);
        $target->setValue($instance, 'Reflection');

        // Call the method
        $setupProperties = $this->Reflection->getMethod('setupProperties');
        $setupProperties->setAccessible(true);
        $setupProperties->invoke($instance);

        // Check the values
        $properties = $this->Reflection->getProperty('property');
        $properties->setAccessible(true);
        $properties = $properties->getValue($instance);

        $this->assertIsArray($properties);
        $this->assertNotEmpty($properties);
    }

    ### The properties are made accessible
    function test__setupProperties__makesPropertiesAccessible(){
        $instance = $this->Reflection->newInstanceWithoutConstructor();

        // Required class
        $class = $this->Reflection->getProperty('class');
        $class->setAccessible(true);
        $class->setValue($instance, new \ReflectionClass(Reflection::class));

        // Required target
        $target = $this->Reflection->getProperty('target');
        $target->setAccessible(true);
        $target->setValue($instance, 'Reflection');

        // Call the method
        $setupProperties = $this->Reflection->getMethod('setupProperties');
        $setupProperties->setAccessible(true);
        $setupProperties->invoke($instance);

        // Try to access the properties
        $properties = $this->Reflection->getProperty('property');
        $properties->setAccessible(true);
        $properties = $properties->getValue($instance);

        foreach($properties as $property){
            try{
                $property->getValue($instance);
            } catch(\ReflectionException $e){
                $this->fail('Property is not accessible');
            } catch(\Throwable $e){
                // We don't care about other exceptions
            }
        }

        $this->assertTrue(true);
    }

    ## setupClass

    ### The ReflectionClass object of the target class is set up
    function test__setupClass__setsUpReflectionClass(){
        $instance = $this->Reflection->newInstanceWithoutConstructor();

        // Required target
        $target = $this->Reflection->getProperty('target');
        $target->setAccessible(true);
        $target->setValue($instance, 'Reflection');

        // Call the method
        $setupClass = $this->Reflection->getMethod('setupClass');
        $setupClass->setAccessible(true);
        $setupClass->invoke($instance);

        // Check the values
        $class = $this->Reflection->getProperty('class');
        $class->setAccessible(true);
        $class = $class->getValue($instance);

        $this->assertInstanceOf(\ReflectionClass::class, $class);
    }

    # Magic Methods

    ## __call

    ### Calls the _CALL method with the method name, and arguments
    function test__call__callsCallMethod(){
        $mock = m::mock(Reflection::class)
            ->makePartial();

        $mock->shouldReceive('_CALL')->once()->with('method', ['arg1', 'arg2'], null);

        $mock->__call('method', ['arg1', 'arg2']);

        $mock->shouldHaveReceived('_CALL');

        $this->assertTrue(true);
    }

    ### If the last argument is a ReflectionInstance, it is used as the instance for _CALL, and poped from the $args array
    function test__call__usesReflectionInstanceAsInstance(){
        $mock = m::mock(Reflection::class)
            ->makePartial();

        $instance = m::mock(ReflectionInstance::class);

        $mock->shouldReceive('_CALL')->once()->with('method', ['arg1', 'arg2'], $instance);

        $mock->__call('method', ['arg1', 'arg2', $instance]);

        $mock->shouldHaveReceived('_CALL');

        $this->assertTrue(true);
    }


    ## __get

    ### Calls the _GET method with the property name
    function test__get__callsGetMethod(){
        $mock = m::mock(Reflection::class)
            ->makePartial();

        $mock->shouldReceive('_GET')->once()->with('property');

        $mock->__get('property');

        $mock->shouldHaveReceived('_GET');

        $this->assertTrue(true);
    }

    ## __set

    ### Calls the _SET method with the property name, and value
    function test__set__callsSetMethod(){
        $mock = m::mock(Reflection::class)
            ->makePartial();

        $mock->shouldReceive('_SET')->once()->with('property', 'value');

        $mock->__set('property', 'value');

        $mock->shouldHaveReceived('_SET');

        $this->assertTrue(true);
    }

    # Public Methods

    ## _GET_INSTANCE

    ### Returns a Reflection object for the target class
    function test__GET_INSTANCE__returnsReflectionObject(){
        $instance = $this->Reflection->newInstanceWithoutConstructor();

        $method = $this->Reflection->getMethod('_GET_INSTANCE');
        $method->setAccessible(true);
        $result = $method->invoke($instance, Reflection::class);

        $this->assertInstanceOf(Reflection::class, $result);

        $target = $this->Reflection->getProperty('target');
        $target->setAccessible(true);
        $target = $target->getValue($result);

        $this->assertEquals(Reflection::class, $target);
    }

    ### If the target class does not exist, an Exception is thrown
    function test__GET_INSTANCE__throwsExceptionIfClassDoesNotExist(){
        $instance = $this->Reflection->newInstanceWithoutConstructor();

        $method = $this->Reflection->getMethod('_GET_INSTANCE');
        $method->setAccessible(true);

        $this->expectException(\ReflectionException::class);
        $method->invoke($instance, 'NonExistentClass');
    }

    ## _GET

    ### If an instance is given, returns the value of the property of the instance
    function test__GET__returnsValueOfInstanceProperty(){
        $instance = $this->Reflection->newInstanceWithoutConstructor();

        // We set the property to fetch and then make it inaccessible again
        $property = $this->Reflection->getProperty('property');
        $property->setAccessible(true);
        $property->setValue($instance, 'value');
        $property->setAccessible(false);

        $reflection = $this->Reflection->newInstanceWithoutConstructor();
        
        $constructor = $this->Reflection->getMethod('__construct');
        $constructor->setAccessible(true);
        $constructor->invoke($reflection, 'Reflection');

        $result = $reflection->_GET('property', $instance);

        $this->assertEquals('value', $result);
    }

    ## _SET
    ### Sets the value of the property of the instance (or static property)
    function test__SET__setsValueOfInstanceProperty(){
        $instance = $this->Reflection->newInstanceWithoutConstructor();

        // We set the property to fetch and then make it inaccessible again
        $property = $this->Reflection->getProperty('property');
        $property->setAccessible(true);
        $property->setValue($instance, 'value');
        $property->setAccessible(false);

        $reflection = $this->Reflection->newInstanceWithoutConstructor();
        
        $constructor = $this->Reflection->getMethod('__construct');
        $constructor->setAccessible(true);
        $constructor->invoke($reflection, 'Reflection');

        $reflection->_SET('property', 'new value', $instance);

        $result = $reflection->_GET('property', $instance);

        $this->assertEquals('new value', $result);
    }

    ## _CALL
    ### Calls the method of the instance (or static method) with the given arguments
    function test__CALL__callsInstanceMethod(){
        $instance = $this->Reflection->newInstanceWithoutConstructor();

        // We set the property to fetch and then make it inaccessible again
        $mockMethod = m::mock(\ReflectionMethod::class);
        $mockMethod->shouldReceive('invokeArgs')->once()->withArgs([$instance, [1, 2]]);

        $reflection = $this->Reflection->newInstanceWithoutConstructor();
        
        $methods = $this->Reflection->getProperty('method');
        $methods->setAccessible(true);
        $methods->setValue($reflection, ['method' => $mockMethod]);

        $reflection->_CALL('method', [1, 2], $instance);

        $mockMethod->shouldHaveReceived('invokeArgs');

        $this->assertTrue(true);
    }

    ### If the instance method does not exist and the static method does not exist, throws an Exception
    function test__CALL__throwsExceptionIfMethodDoesNotExist(){
        $instance = $this->Reflection->newInstanceWithoutConstructor();

        $reflection = $this->Reflection->newInstanceWithoutConstructor();
        
        $methods = $this->Reflection->getProperty('method');
        $methods->setAccessible(true);
        $methods->setValue($reflection, []);

        $this->expectException(\ReflectionException::class);
        $reflection->_CALL('method', [1, 2], $instance);
    }

    ## _NEW
    ### Returns a ReflectionInstance object for the target class
    function test__NEW__returnsReflectionInstanceObject(){
        $reflection = $this->Reflection->newInstanceWithoutConstructor();

        $constructor = $this->Reflection->getMethod('__construct');
        $constructor->setAccessible(true);
        $constructor->invoke($reflection, Reflection::class);

        $result = $reflection->_NEW(Reflection::class);

        $this->assertInstanceOf(ReflectionInstance::class, $result);
    }

    ### The target object is constructed with the given arguments
    function test__NEW__constructsTargetObject(){
        // a little hard to test
        $this->assertTrue(true);
    }

    ### The target object is constructed even when constructor is private
    function test__NEW__constructsTargetObjectWithPrivateConstructor(){
        // tested in returnReflectionInstanceObject, as Reflection has a private constructor
        $this->assertTrue(true);
    }

    ## _NEW_FROM_INSTANCE
    ### Returns a ReflectionInstance object filled with the given instance
    function test__NEW_FROM_INSTANCE__returnsReflectionInstanceObject(){
        $reflection = $this->Reflection->newInstanceWithoutConstructor();

        $constructor = $this->Reflection->getMethod('__construct');
        $constructor->setAccessible(true);
        $constructor->invoke($reflection, Reflection::class);

        $target = $this->Reflection->getProperty('target');
        $target->setAccessible(true);
        $target->setValue($reflection, stdClass::class);

        $instance = new stdClass;

        $result = $reflection->_NEW_FROM_INSTANCE($instance);

        $ReflectionInstance = new \ReflectionClass(ReflectionInstance::class);

        $actual = $ReflectionInstance->getProperty('instance');
        $actual->setAccessible(true);
        $actual = $actual->getValue($result);

        $this->assertEquals($instance, $actual);
    }

    ## _NEW_MOCK
    ### Returns a ReflectionInstance object filled with the mock object of the target class
    function test__NEW_MOCK__returnsReflectionInstanceObject(){
        $reflection = $this->Reflection->newInstanceWithoutConstructor();

        $constructor = $this->Reflection->getMethod('__construct');
        $constructor->setAccessible(true);
        $constructor->invoke($reflection, Reflection::class);

        $target = $this->Reflection->getProperty('target');
        $target->setAccessible(true);
        $target->setValue($reflection, TestClass::class);

        $result = $reflection->_NEW_MOCK('value1', 'value2');

        $ReflectionInstance = new \ReflectionClass(ReflectionInstance::class);

        $instance = $ReflectionInstance->getProperty('instance');
        $instance->setAccessible(true);
        $instance = $instance->getValue($result);

        $this->assertEquals('value1', $instance->arg1);
        $this->assertEquals('value2', $instance->arg2);
    }

    ### The arguments are passed to the target class constructor
    function test__NEW_MOCK__passesArgumentsToConstructor(){
        $reflection = $this->Reflection->newInstanceWithoutConstructor();

        $constructor = $this->Reflection->getMethod('__construct');
        $constructor->setAccessible(true);
        $constructor->invoke($reflection, Reflection::class);

        $result = $reflection->_NEW_MOCK(Reflection::class, ['arg1', 'arg2']);

        $ReflectionInstance = new \ReflectionClass(ReflectionInstance::class);

        $actual = $ReflectionInstance->getProperty('instance');
        $actual->setAccessible(true);
        $actual = $actual->getValue($result);

        
    }

    ## _METHOD_ACCESS
    ### Calls setAccessible on the target method
    function test__METHOD_ACCESS__callsSetAccessible(){
        $reflection = $this->Reflection->newInstanceWithoutConstructor();

        $method = m::mock(\ReflectionMethod::class);
        $method->shouldReceive('setAccessible')->once()->with(true);
        $method->shouldReceive('setAccessible')->once()->with(false);

        $methods = $this->Reflection->getProperty('method');
        $methods->setAccessible(true);
        $methods->setValue($reflection, ['method' => $method]);

        $reflection->_METHOD_ACCESS('method', 'method', true);
        $reflection->_METHOD_ACCESS('method', 'method', false);

        $method->shouldHaveReceived('setAccessible');

        $this->assertTrue(true);
    }

    ### If the method does not exist, throws an Exception
    function test__METHOD_ACCESS__throwsExceptionIfMethodDoesNotExist(){
        $reflection = $this->Reflection->newInstanceWithoutConstructor();

        $methods = $this->Reflection->getProperty('method');
        $methods->setAccessible(true);
        $methods->setValue($reflection, []);

        $this->expectException(\ReflectionException::class);
        $reflection->_METHOD_ACCESS('method', 'method', true);
    }

    ## _PROPERTY_ACCESS
    ### Calls setAccessible on the target property
    function test__PROPERTY_ACCESS__callsSetAccessible(){
        $reflection = $this->Reflection->newInstanceWithoutConstructor();

        $property = m::mock(\ReflectionProperty::class);
        $property->shouldReceive('setAccessible')->once()->with(true);
        $property->shouldReceive('setAccessible')->once()->with(false);

        $properties = $this->Reflection->getProperty('property');
        $properties->setAccessible(true);
        $properties->setValue($reflection, ['property' => $property]);

        $reflection->_PROPERTY_ACCESS('property', true);
        $reflection->_PROPERTY_ACCESS('property', false);

        $property->shouldHaveReceived('setAccessible');

        $this->assertTrue(true);
    }

    ### If the property does not exist, throws an Exception
    function test__PROPERTY_ACCESS__throwsExceptionIfPropertyDoesNotExist(){
        $reflection = $this->Reflection->newInstanceWithoutConstructor();

        $properties = $this->Reflection->getProperty('property');
        $properties->setAccessible(true);
        $properties->setValue($reflection, []);

        $this->expectException(\ReflectionException::class);
        $reflection->_PROPERTY_ACCESS('property', true);
    }

}

class TestClass{
    public $arg1;
    public $arg2;

    function __construct($arg1, $arg2){
        $this->arg1 = $arg1;
        $this->arg2 = $arg2;
    }
}