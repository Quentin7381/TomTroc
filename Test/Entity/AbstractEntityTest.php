<?php

namespace Test\Entity;

require_once __DIR__ . '/../../vendor/autoload.php';

use Entity\AbstractEntity;
use Entity\Exception;
use Test\Reflection;
use Test\ReflectionInstance;
use \ReflectionProperty;
use \ReflectionClass;
use Test\TestInit;
use Mockery as m;

abstract class AbstractEntityTest extends TestInit{
    protected $class;
    protected $Reflection;

    public function setUp(): void {
        parent::setUp();

        $class = static::class;
        $class = str_replace('Test\\', '', $class);
        $class = str_replace('Test', '', $class);
        $this->class = $class;
        $this->Reflection = Reflection::_GET_INSTANCE($class);
    }

    ## set
    ### if a validate_* method exists, it calls it
    function test__set__calls_validate_method(){
        $entityMethods = get_class_methods($this->class);
        $validateMethods = array_filter($entityMethods, function($method){
            return strpos($method, 'validate_') === 0;
        });

        if(empty($validateMethods)){
            $this->markTestSkipped('No validate_* methods found.');
        }

        $validateMethod = $validateMethods[array_rand($validateMethods)];
        $property = substr($validateMethod, 9);

        $entity = m::mock($this->class)->makePartial();
        $entity->shouldReceive($validateMethod)->once();
        $entity->shouldReceive('set_' . $property)->zeroOrMoreTimes();

        $entity->set($property, 'value');

        $entity->shouldHaveReceived($validateMethod);
        $this->assertTrue(true);
    }

    ### if a set_* method exists, it calls it
    function test__set__calls_set_method(){
        $entityMethods = get_class_methods($this->class);
        $setMethods = array_filter($entityMethods, function($method){
            return strpos($method, 'set_') === 0;
        });

        if(empty($setMethods)){
            $this->markTestSkipped('No set_* methods found.');
        }

        $setMethod = $setMethods[array_rand($setMethods)];
        $property = substr($setMethod, 4);

        $entity = m::mock($this->class)->makePartial();
        $entity->shouldReceive($setMethod)->once();
        $entity->shouldReceive('validate_' . $property)->zeroOrMoreTimes();

        $entity->set($property, 'value');

        $entity->shouldHaveReceived($setMethod);
        $this->assertTrue(true);
    }

    ### if the property exists and no custom setter method exists, it sets the property
    function test__set__sets_property(){
        $entityProperties = $this->Reflection->_GET_PROPERTIES();

        $entitySetters = array_filter($this->Reflection->_GET_METHODS(), function($method){
            return strpos($method, 'set_') === 0;
        });

        $setterlessProperties = array_diff($entityProperties, array_map(function($method){
            return substr($method, 4);
        }, $entitySetters));

        if(empty($setterlessProperties)){
            $this->markTestSkipped('No properties without setter methods found.');
        }

        $property = array_rand($setterlessProperties);
        $property = $setterlessProperties[$property];

        $entity = m::mock($this->class)->makePartial();
        $entity->shouldReceive('validate_' . $property)->zeroOrMoreTimes();
        $entity->shouldReceive('set_' . $property)->never();

        $entity->set($property, 'value');

        $this->assertEquals('value', $entity->$property);
    }

    ### if the property does not exist, it throws an exception
    function test__set__throws_exception_if_property_does_not_exist(){
        $entity = m::mock($this->class)->makePartial();

        $this->expectException(Exception::class);

        if(in_array('nonexistent_property', $this->Reflection->_GET_PROPERTIES())){
            $this->markTestSkipped('This is very surprising, but it occurs nonexistent_property exists in this class...');
        }

        $entity->set('nonexistent_property', 'value');
    }

    ## get
    ### if a get_* method exists, it calls it
    function test__get__calls_get_method(){
        $entityMethods = get_class_methods($this->class);
        $getMethods = array_filter($entityMethods, function($method){
            return strpos($method, 'get_') === 0;
        });

        if(empty($getMethods)){
            $this->markTestSkipped('No get_* methods found.');
        }

        $getMethod = $getMethods[array_rand($getMethods)];
        $property = substr($getMethod, 4);

        $entity = m::mock($this->class)->makePartial();
        $entity->shouldReceive($getMethod)->once();

        $entity->get($property);

        $entity->shouldHaveReceived($getMethod);
        $this->assertTrue(true);
    }

    ### if no custom getter method exists, it returns the property
    function test__get__returns_property(){
        $entityProperties = $this->Reflection->_GET_PROPERTIES();

        $entityGetters = array_filter($this->Reflection->_GET_METHODS(), function($method){
            return strpos($method, 'get_') === 0;
        });

        $getterlessProperties = array_diff($entityProperties, array_map(function($method){
            return substr($method, 4);
        }, $entityGetters));

        if(empty($getterlessProperties)){
            $this->markTestSkipped('No properties without getter methods found.');
        }

        $property = array_rand($getterlessProperties);
        $property = $getterlessProperties[$property];

        $entity = m::mock($this->class)->makePartial();
        $entity->shouldReceive('get_' . $property)->never();

        $values = ['value', 1, 1.1, true, null, [], new \stdClass];
        $value = array_pop($values);
        do {
            try {
                $this->Reflection->_SET($property, $value, $entity);
                break;
            } catch (\TypeError $e) {}

            if(empty($values)){
                $this->markTestSkipped('No valid value found for property ' . $property);
                break;
            } else {
                $value = array_pop($values);
            }
        } while (true);

        $this->assertEquals($value, $entity->get($property));
    }

    ### if the property does not exist, it throws an exception
    function test__get__throws_exception_if_property_does_not_exist(){
        $entity = m::mock($this->class)->makePartial();

        $this->expectException(Exception::class);

        if(in_array('nonexistent_property', $this->Reflection->_GET_PROPERTIES())){
            $this->markTestSkipped('This is very surprising, but it occurs nonexistent_property exists in this class...');
        }

        $entity->get('nonexistent_property');
    }

    ## __get
    ### __get calls the get method
    function test__get__calls_get(){
        $entity = m::mock($this->class)->makePartial();
        $entity->shouldReceive('get')->once();

        $entity->nonexistent_property;

        $entity->shouldHaveReceived('get');
        $this->assertTrue(true);
    }

    ## __set
    ### __set calls the set method
    function test__set__calls_set(){
        $entity = m::mock($this->class)->makePartial();
        $entity->shouldReceive('set')->once();

        $entity->nonexistent_property = 'value';

        $entity->shouldHaveReceived('set');
        $this->assertTrue(true);
    }

    ## getFields
    ### getFields returns the protected properties of the entity with their types
    function test_getFields_returns_properties(){
        $fields = $this->Reflection->getFields();

        $entityProperties = $this->Reflection->_GET_PROPERTIES();

        $this->assertEquals(count($entityProperties), count($fields));

        foreach($entityProperties as $property){
            $this->assertArrayHasKey($property, $fields);
        }
    }

    ### getFields does not return any private properties
    function test_getFields_does_not_return_private_properties(){
        $reflection = new ReflectionClass($this->class);
        $privateProperties = $reflection->getProperties(ReflectionProperty::IS_PRIVATE);

        if(empty($privateProperties)){
            $this->markTestSkipped('No private properties found.');
        }

        $fields = $this->Reflection->getFields();

        foreach($privateProperties as $property){
            $this->assertArrayNotHasKey($property->getName(), $fields);
        }
    }

    ## getDbType
    ### getDbType returns VARCHAR(255) as default type
    function test_getDbType_returns_default_type(){
        $this->assertEquals('VARCHAR(255)', $this->Reflection->getDbType('walalay'));
    }
    
}
