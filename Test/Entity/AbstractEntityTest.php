<?php

namespace Test\Controller;

require_once __DIR__ . '/../../vendor/autoload.php';

use Controller\AbstractEntity;
use Controller\Exception;
use Test\Reflection;
use Test\ReflectionInstance;
use Test\TestInit;
use Mockery as m;

abstract class AbstractEntityTest extends TestInit{

    public function setUp(): void {
        parent::setUp();

        $this->AbstractEntity = Reflection::_GET_INSTANCE(AbstractEntity::class);
    }

    ## set
    ### if a validate_* method exists, it calls it
    ### if a set_* method exists, it calls it
    ### if the property exists and no custom setter method exists, it sets the property
    ### if the property does not exist, it throws an exception

    ## get
    ### if a get_* method exists, it calls it
    ### if no custom getter method exists, it returns the property
    ### if the property does not exist, it throws an exception

    ## __get
    ### __get calls the get method

    ## __set
    ### __set calls the set method

    ## getFields
    ### getFields returns the protected properties of the entity with their types
    ### getFields does not return any private properties

    ## getDbType
    ### getDbType returns VARCHAR(255) as default type
    
}
