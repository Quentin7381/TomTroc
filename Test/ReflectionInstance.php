<?php

namespace Test;

/**
 * Pairs an instance of a class with its Reflection object, to allow to directly access the instance properties and methods.
 */
class ReflectionInstance
{

    /**
     * @var Reflection $reflection
     *
     * Holds the Reflection object used to access the instance properties and methods.
     *
     * @see Reflection
     */
    protected $reflection;

    /**
     * @var object $instance
     *
     * Holds the instance of the target class.
     */
    protected $instance;

    /**
     * ReflectionInstance constructor
     *
     * Sets up the ReflectionInstance object.
     *
     * @param Reflection $reflection The Reflection object used to access the instance properties and methods.
     * @param object $instance The instance of the target class.
     *
     * @see Reflection
     */
    public function __construct(Reflection $reflection, object $instance)
    {
        if(!is_a($instance, $reflection->_GET_TARGET())){
            throw new \ReflectionException('Argument #2 ($instance) must be an instance of ' . $reflection->_GET_TARGET() . ', instance of ' . get_class($instance) . ' given');
        }

        $this->reflection = $reflection;
        $this->instance = $instance;
    }

    /**
     * Calls the target class method through the Reflection object.
     *
     * @param string $method The target class method name.
     * @param array $args The arguments to pass to the target class method.
     *
     * @return mixed The return value of the target class method.
     */
    public function __call($method, $args)
    {
        return $this->reflection->_CALL($method, $args, $this->instance);
    }

    /**
     * Gets the target class property through the Reflection object.
     *
     * @param string $name The target class property name.
     *
     * @return mixed The value of the target class property.
     */
    public function __get($name)
    {
        return $this->reflection->_GET($name, $this->instance);
    }

    /**
     * Sets the target class property through the Reflection object.
     *
     * @param string $name The target class property name.
     * @param mixed $value The value to set to the target class property.
     */
    public function __set($name, $value)
    {
        return $this->reflection->_SET($name, $value, $this->instance);
    }
}
