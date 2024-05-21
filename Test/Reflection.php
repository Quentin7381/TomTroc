<?php

namespace Test;

use Mockery as m;

/**
 * ### Definition
 *
 * This class is used to bypass the Reflection object and call the target class methods directly.
 * This is used to simplify the Reflection object calls.
 *
 * All Reflection public methods are named as _METHOD_NAME so they won't conflict with the target class methods.
 *
 * ### Usage
 *
 * First, get the Reflection object for the target class using `Reflection::_GET_INSTANCE('Namespace\Class')`.
 * If needed, create a new instance of the target class using `_NEW`, `_NEW_FROM_INSTANCE($instance)` or `_NEW_MOCK`.
 *
 * Then, you can call the target class methods directly using `$myReflectionObject->methodName()`.
 * Or access the target class properties directly using `$myReflectionObject->propertyName`.
 *
 * ### Public Methods
 *
 * `_GET_INSTANCE()`
 *
 * `_GET()`
 * `_SET()`
 * `_CALL()`
 *
 * `_NEW()`
 * `_NEW_FROM_INSTANCE()`
 * `_NEW_MOCK()`
 *
 * `_METHOD_ACCESS()`
 * `_PROPERTY_ACCESS()`
 */
class Reflection
{

    /**
     * @var Reflection[] $instances
     *
     * Holds all the instances of Reflection.
     * Only one instance per target class.
     * This uses the target namespace\class as the key.
     */
    protected static $instances = [];

    /**
     * @var \ReflectionMethod[] $method
     *
     * Holds all the methods of the target class.
     * This uses the method name as the key.
     */
    protected $method;

    /**
     * @var \ReflectionProperty[] $property
     *
     * Holds all the properties of the target class.
     * This uses the property name as the key.
     */
    protected $property;

    /**
     * @var string $target
     *
     * The target namespace\class.
     */
    protected $target;

    /**
     * @var \ReflectionClass $class
     *
     * The ReflectionClass object of the target class.
     */
    protected $class;

    /**
     * Reflection constructor
     * Sets up the Reflection object.
     * Sets up the methods and makes them accessible.
     * Sets up the properties and makes them accessible.
     *
     * @param string $target The target namespace\class
     */
    protected function __construct($target)
    {
        $this->target = $target;
        self::$instances[$target] = $this;
        $this->setupClass();
        $this->setupMethods();
        $this->setupProperties();
    }

    /**
     * Fills the method array with the methods of the target class and makes them accessible.
     */
    protected function setupMethods()
    {
        $methods = $this->class->getMethods();
        foreach ($methods as $method) {
            $method->setAccessible(true);
            $this->method[$method->name] = $method;
        }
    }

    /**
     * Fills the property array with the properties of the target class and makes them accessible.
     */
    protected function setupProperties()
    {
        $properties = $this->class->getProperties();
        foreach ($properties as $property) {
            $property->setAccessible(true);
            $this->property[$property->name] = $property;
        }
    }

    /**
     * Sets up the ReflectionClass object of the target class.
     */
    protected function setupClass()
    {
        $this->class = new \ReflectionClass($this->target);
    }

    /**
     * Allow to bypass the Reflection object and call the target class methods directly.
     * These methods will be accessible by default.
     * @see _ACCESS to override this behavior
     *
     * If given as the last argument, the ReflectionInstance object will be used as target instance of the call.
     *
     * @param string $method The method to call.
     * @param array $args The arguments to pass to the method (that can end with a ReflectionInstance object).
     */
    public function __call($method, $args)
    {
        $lastI = count($args) - 1;
        if ($args[$lastI] instanceof ReflectionInstance) {
            $instance = array_pop($args);
        }

        return $this->_CALL($method, $args, $instance ?? null);
    }

    /**
     * Allow to bypass the Reflection object and access the target class properties directly.
     * @see _GET
     */
    public function __get($name)
    {
        $this->_GET($name);
    }

    /**
     * Allow to bypass the Reflection object and set the target class properties directly.
     * @see _SET
     */
    public function __set($name, $value)
    {
        $this->_SET($name, $value);
    }

    /**
     * Get a the Relfection object for the target class.
     *
     * @param string $target The target namespace\class.
     * @return object The instance of the target class.
     * @throws \ReflectionException If the target class does not exist.
     */
    public static function _GET_INSTANCE($target)
    {
        return self::$instances[$target] ?? new Reflection($target);
    }

    /**
     * Get the value of a property of the target class.
     * This property will be accessible by default.
     * @see _ACCESS to override this behavior
     *
     * @param string $name The property name.
     * @param object $instance The instance of the target class.
     * @return mixed The value of the property.
     *
     * note: This can bypass __get method setups.
     */
    public function _GET($name, $instance = null)
    {
        if (isset($this->property[$name])) {
            return $this->property[$name]->getValue($instance);
        }
        
        return $instance->$name;
    }

    /**
     * Get the target namespace\class.
     *
     * @return string The target namespace\class.
     */
    public function _GET_TARGET()
    {
        return $this->target;
    }

    /**
     * Set the value of a property of the target class.
     * This property will be accessible by default.
     * @see _ACCESS to override this behavior
     *
     * @param string $name The property name.
     * @param mixed $value The value to set.
     * @param object $instance The instance of the target class.
     *
     * note: This can bypass __set method setups.
     */
    public function _SET($name, $value, $instance = null)
    {
        if(isset($this->property[$name])){
            $this->property[$name]->setValue($instance, $value);
            return;
        }

        $instance->$name = $value;
    }

    /**
     * Call a method of the target class.
     * This method will be accessible by default.
     * @see _ACCESS to override this behavior
     *
     * If given an instance, the method will be called on that instance.
     * Else, the method will be called statically.
     *
     * @param string $method The method to call.
     * @param array $args The arguments to pass to the method (that can end with a ReflectionInstance object).
     * @return mixed The return value of the method.
     */
    public function _CALL($method, $args, $instance = null)
    {
        if (!empty($this->method[$method])) {
            return $this->method[$method]->invokeArgs($instance, $args);
        }
        
        if(!method_exists($instance, $method)){
            throw new \ReflectionException("Method $method does not exist in class {$this->target}");
        }

        return $instance->$method(...$args);
    }

    /**
     * Create a new ReflectionInstance object for the target class.
     *
     * @param mixed ...$args The arguments to pass to the constructor.
     * @return object The instance of the target class.
     */
    public function _NEW(...$args)
    {
        $instance = $this->class->newInstanceWithoutConstructor();
        $constructor = $this->class->getConstructor();
        if($constructor){
            $constructor->invokeArgs($instance, $args);
        }

        return new ReflectionInstance($this, $instance);
    }

    /**
     * Create a new ReflectionInstance object from an existing instance of the target class.
     * This will load any existing properties into the new ReflectionInstance object.
     *
     * @param object $instance The instance of the target class.
     * @return object The instance of the target class.
     */
    public function _NEW_FROM_INSTANCE($instance)
    {
        return new ReflectionInstance($this, $instance);
    }

    /**
     * Create a new Mockery Mock object for the target class.
     * This will allow both mocking and access to protected and protected methods and properties.
     *
     * @param mixed ...$args The arguments to pass to the constructor.
     * @return object The instance of the target class.
     */
    public function _NEW_MOCK(...$args){
        $mock = m::mock($this->target);
        return $this->_NEW_FROM_INSTANCE($mock);
    }

    /**
     * Set the accessibility of a method of the target class.
     *
     * @param string $name The method name.
     * @param bool $accessible The accessibility of the method.
     */
    public function _METHOD_ACCESS($method, $name, $accessible = true)
    {
        if(!isset($this->$method[$name])){
            throw new \ReflectionException("Method $name does not exist in class {$this->target}");
        }
        $this->$method[$name]->setAccessible($accessible);
    }

    /**
     * Set the accessibility of a property of the target class.
     *
     * @param string $name The property name.
     * @param bool $accessible The accessibility of the property.
     */
    public function _PROPERTY_ACCESS($name, $accessible = true)
    {
        if(!isset($this->property[$name])){
            throw new \ReflectionException("Property $name does not exist in class {$this->target}");
        }
        $this->property[$name]->setAccessible($accessible);
    }
}