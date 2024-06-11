<?php

namespace Test\Manager;

require_once __DIR__ . '/../../vendor/autoload.php';

use Manager\AbstractManager;
use Manager\Exception;
use Test\Reflection;
use Test\ReflectionInstance;
use Test\TestInit;
use Mockery as m;
use Test\ErrorCatcher;

abstract class AbstractManagerTest extends TestInit
{

    protected $class;
    protected $Reflection;
    protected $config;
    protected $pdo;
    protected $instance;
    protected $statement;
    protected $entity;

    public function setUp(): void
    {
        parent::setUp();

        $class = static::class;
        $class = str_replace('Test\\', '', $class);
        $class = str_replace('Test', '', $class);
        $this->class = $class;
        $this->Reflection = Reflection::_GET_INSTANCE($class);

        // if(!isset($this->config)){
            $this->config = m::mock('alias:Config\Config');
            $this->config->shouldReceive('getInstance')
            ->zeroOrMoreTimes()
            ->andReturnSelf();
        // }
        
        // if(!isset($this->pdo)){
            $this->pdo = m::mock('alias:Utils\PDO');
            $this->pdo->shouldReceive('getInstance')
            ->zeroOrMoreTimes()
            ->andReturnSelf();
        // }
        
        $this->statement = m::mock('PDOStatement');
        $this->pdo->shouldReceive('prepare')
            ->zeroOrMoreTimes()
            ->andReturn($this->statement);

        $this->instance = $this->Reflection->_NEW_MOCK();
        $this->instance->table = 'test_table';
        $this->instance->pdo = $this->pdo;

        $entityName = str_replace('Manager\\', '', $this->class);
        $entityName = str_replace('Manager', '', $entityName);
        try{
            $this->entity = m::mock('alias:Entity\\' . $entityName);
        } catch (\Mockery\Exception\RuntimeException $e) {
            $this->markTestSkipped('Could not create the alias for Entity\\' . $entityName . '. Rerun the test in separate processes.');
        }
    }

    public function tearDown(): void
    {
        parent::tearDown();
        m::close();
    }

    ## __construct
    ### construct loads the configuration through Config::getInstance
    ### construct set the instance of the manager
    ### construct sets the table name
    ### construct sets the table fields
    ### construct prepares the entity table in the database
    function test__construct__loads_the_configuration_through_Config_getInstance()
    {
        $mock = $this->instance;

        $mock->shouldReceive('__construct')->passthru();
        $mock->shouldReceive('getEntityName')->andReturn('entity');
        $mock->shouldReceive('getEntityFields')->andReturn([]);
        $mock->shouldReceive('prepareEntityTable')->andReturn(true);

        $mock->__call('__construct', []);

        $this->assertTrue(true);
    }

    ## getInstance
    ### getInstance returns the instance of the manager
    function test__getInstance__returns_the_instance_of_the_manager()
    {
        $instance = $this->instance;
        $instance->instance = $instance;
        $this->assertEquals($instance, $instance->getInstance());
    }

    ### getInstance creates a new instance of the manager if it does not exist
    function test__getInstance__creates_a_new_instance_of_the_manager_if_it_does_not_exist()
    {
        $this->statement->shouldReceive('execute')->andReturn(true);
        $this->statement->shouldReceive('fetch')->andReturn(false);

        $instance = $this->instance;
        $instance->instance = null;

        $this->entity->shouldReceive('getFields')->andReturn([]);

        $this->assertInstanceOf($this->class, $instance->getInstance());
    }

    ## getEntityName
    ### getEntityName returns the associated entity name without the namespace
    function test__getEntityName__returns_the_associated_entity_name_without_the_namespace()
    {
        $instance = $this->Reflection->_NEW_WITHOUT_CONSTRUCTOR();
        $entityName = $instance->getEntityName();

        $class = str_replace('Manager\\', '', $this->class);
        $class = str_replace('Manager', '', $class);
        $this->assertEquals($class, $entityName);
    }

    ## getEntityFields
    ### uses the getFields method of the right entity to get the fields
    function test__getEntityFields__uses_the_getFields_method_of_the_entity_to_get_the_fields()
    {
        $entity = $this->entity;
        $entity->shouldReceive('getFields')->once();

        $instance = $this->Reflection->_NEW_WITHOUT_CONSTRUCTOR();
        $instance->getEntityFields();
        $this->assertTrue(true);
    }

    ## prepareEntityTable

    ### if the table does not exist, prepareEntityTable calls createTable to create the table
    function test__prepareEntityTable__calls_createTable_to_create_the_table_if_the_table_does_not_exist()
    {
        $instance = $this->instance;

        $instance->shouldReceive('tableExists')->andReturn(false);
        $instance->shouldReceive('createTable')->once();


        $instance->prepareEntityTable();
        $this->assertTrue(true);
    }

    ### if the table exists, prepareEntityTable calls updateTable to update the table
    function test__prepareEntityTable__calls_updateTable_to_update_the_table_if_the_table_exists()
    {
        $instance = $this->instance;

        $instance->shouldReceive('tableExists')->andReturn(true);
        $instance->shouldReceive('updateTable')->once();

        $instance->prepareEntityTable();
        $this->assertTrue(true);
    }

    ## tableExists
    ### tableExists returns true if the table exists
    function test__tableExists__returns_true_if_the_table_exists()
    {
        $instance = $this->instance;
        $instance->table = 'test_table';
        $instance->pdo = $this->pdo;
        $instance->shouldReceive('getTableName')->andReturn('table');
        
        $this->pdo->shouldReceive('query')->andReturn($this->statement);
        $this->statement->shouldReceive('execute')->andReturn(true);
        $this->statement->shouldReceive('fetch')->andReturn(['table' => 'table']);

        $this->assertTrue($instance->tableExists());
    }

    ### tableExists returns false if the table does not exist
    function test__tableExists__returns_false_if_the_table_does_not_exist()
    {
        $instance = $this->instance;
        $instance->table = 'test_table';
        $instance->pdo = $this->pdo;
        $instance->shouldReceive('getTableName')->andReturn('table');
        
        $this->pdo->shouldReceive('query')->andReturn($this->statement);
        $this->statement->shouldReceive('execute')->andReturn(true);
        $this->statement->shouldReceive('fetch')->andReturn(false);

        $this->assertFalse($instance->tableExists());
    }

    ## createTable
    ### createTable creates the table in the database
    function test__createTable__creates_the_table_in_the_database()
    {
        $instance = $this->instance;
        $instance->fields = ['field1', 'field2'];
        $instance->shouldReceive('getTableName')->andReturn('table');
        $instance->shouldReceive('getEntityFields')->andReturn(['field1', 'field2']);
        
        $this->statement->shouldReceive('execute')->once()->andReturn(true);

        $instance->createTable();
        $this->assertTrue(true);
    }

    ### createTable throws an exception if the sql fails
    function test__createTable__throws_an_exception_if_the_sql_fails()
    {
        $instance = $this->instance;
        $instance->fields = ['field1', 'field2'];
        $instance->shouldReceive('getTableName')->andReturn('table');
        $instance->shouldReceive('getEntityFields')->andReturn(['field1', 'field2']);
        
        $this->statement->shouldReceive('execute')->once()->andReturn(false);
        $this->statement->shouldReceive('errorInfo')->andReturn(['error']);

        $this->expectException(Exception::class);
        $instance->createTable();
    }

    ### the created table has the name of the entity
    function test__createTable__the_created_table_has_the_name_of_the_entity()
    {
        $instance = $this->instance;
        $instance->fields = ['field1' => 'type1', 'field2' => 'type2'];
        $instance->shouldReceive('getTableName')->andReturn('test_table');
        $instance->shouldReceive('getEntityFields')->andReturn(['field1', 'field2']);

        // Reset the pdo mock
        $this->pdo = m::mock('alias:Utils\PDO');
        $instance->pdo = $this->pdo;
        
        $this->pdo->shouldReceive('prepare')->with(m::pattern('/CREATE TABLE test_table .*/'))->once()->andReturn($this->statement);
        $this->statement->shouldReceive('execute')->once()->andReturn(true);

        $instance->createTable();
        $this->assertTrue(true);
    }

    ### the created table has the fields of the entity
    function test__createTable__the_created_table_has_the_fields_of_the_entity()
    {
        $instance = $this->instance;
        $instance->fields = ['field1', 'field2'];
        $instance->shouldReceive('getTableName')->andReturn('table');
        $instance->shouldReceive('getEntityFields')->andReturn(['field1', 'field2']);

        $this->pdo = m::mock('alias:Utils\PDO');
        $instance->pdo = $this->pdo;
        $this->statement = m::mock('PDOStatement');
        
        $this->pdo->shouldReceive('prepare')->with(m::pattern('/\\(.*field1.*\\)/'))->once()->andReturn($this->statement);
        $this->statement->shouldReceive('execute')->once()->andReturn(true);

        $instance->createTable();
        $this->assertTrue(true);
    }

    ## updateTable
    ### untested : only calls sub methods

    ## getMissingTalbeFields
    ### getMissingTalbeFields returns an empty array if the table fields are up to date
    function test__getMissingTalbeFields__returns_an_empty_array_if_the_table_fields_are_up_to_date()
    {
        $fields = ['field1' => 'type1', 'field2' => 'type2'];
        $instance = $this->instance;
        $instance->fields = $fields;
        $instance->shouldReceive('getTableName')->andReturn('table');
        $instance->shouldReceive('getEntityFields')->andReturn($fields);
        
        $this->statement->shouldReceive('execute')->once()->andReturn(true);
        $this->statement->shouldReceive('fetchAll')
            ->once()
            ->andReturn([['Field' => 'field1', 'Type' => 'type1'], ['Field' => 'field2', 'Type' => 'type2']]);

        $this->assertEquals([], $instance->getMissingTableFields());
    }

    ### getMissingTalbeFields returns an array of missing fields if the table fields are not up to date
    function test__getMissingTalbeFields__returns_an_array_of_missing_fields_if_the_table_fields_are_not_up_to_date()
    {
        $fields = ['field1' => 'type1', 'field2' => 'type2'];
        $instance = $this->instance;
        $instance->fields = $fields;
        $instance->shouldReceive('getTableName')->andReturn('table');
        $instance->shouldReceive('getEntityFields')->andReturn($fields);
        
        $this->statement->shouldReceive('execute')->once()->andReturn(true);
        $this->statement->shouldReceive('fetchAll')
            ->once()
            ->andReturn([['Field' => 'field1', 'Type' => 'type1']]);

        $this->assertEquals(['field2' => 'type2'], $instance->getMissingTableFields());
    }

    ## ManageMissingTableFields
    ### ManageMissingTableFields sets the missing fields in the table
    function test__ManageMissingTableFields__sets_the_missing_fields_in_the_table()
    {
        $missingFields = ['field2' => 'type2', 'field3' => 'type3'];
        $instance = $this->instance;

        $this->pdo = m::mock('alias:Utils\PDO');
        $instance->pdo = $this->pdo;

        $this->pdo->shouldReceive('prepare')->with(m::pattern('/.*ADD field2 type2, ADD field3 type3.*/'))->once()->andReturn($this->statement);
        $this->statement->shouldReceive('execute')->once()->andReturn(true);

        $instance->ManageMissingTableFields($missingFields);
        $this->assertTrue(true);
    }
    
    ### ManageMissingTableFields throws an exception if the sql fails
    function test__ManageMissingTableFields__throws_an_exception_if_the_sql_fails()
    {
        $missingFields = ['field2' => 'type2', 'field3' => 'type3'];
        $instance = $this->instance;

        $this->pdo = m::mock('alias:Utils\PDO');
        $instance->pdo = $this->pdo;

        $this->pdo->shouldReceive('prepare')->with(m::pattern('/.*ADD field2 type2, ADD field3 type3.*/'))->once()->andReturn($this->statement);
        $this->statement->shouldReceive('execute')->once()->andReturn(false);
        $this->statement->shouldReceive('errorInfo')->andReturn(['error']);

        $this->expectException(Exception::class);
        $instance->ManageMissingTableFields($missingFields);
    }

    ## getWrongTableFields
    ### getWrongTableFields returns an empty array if the table fields are up to date
    function test__getWrongTableFields__returns_an_empty_array_if_the_table_fields_are_up_to_date()
    {
        $fields = ['field1' => 'type1', 'field2' => 'type2'];
        $instance = $this->instance;
        $instance->fields = $fields;
        
        $this->statement->shouldReceive('execute')->once()->andReturn(true);
        $this->statement->shouldReceive('fetchAll')
            ->once()
            ->andReturn([['Field' => 'field1', 'Type' => 'type1'], ['Field' => 'field2', 'Type' => 'type2']]);

        $this->assertEquals([], $instance->getWrongTableFields());
    }

    ### getWrongTableFields returns an array of wrong fields if some fields are of the wrong type
    function test__getWrongTableFields__returns_an_array_of_wrong_fields_if_some_fields_are_of_the_wrong_type()
    {
        $fields = ['field1' => 'type1', 'field2' => 'type2'];
        $instance = $this->instance;
        $instance->fields = $fields;
        
        $this->statement->shouldReceive('execute')->once()->andReturn(true);
        $this->statement->shouldReceive('fetchAll')
            ->once()
            ->andReturn([['Field' => 'field1', 'Type' => 'type1'], ['Field' => 'field2', 'Type' => 'wrongType']]);

        $this->assertEquals(['field2' => 'type2'], $instance->getWrongTableFields());
    }

    ## ManageWrongTableFields
    ### ManageWrongTableFields throw an exception
    function test__ManageWrongTableFields__throw_an_exception()
    {
        $wrongFields = ['field2' => 'type2', 'field3' => 'type3'];
        $instance = $this->instance;

        $this->expectException(Exception::class);
        $instance->ManageWrongTableFields($wrongFields);
    }

    ## getUnusedTableFields
    ### getUnusedTableFields returns an empty array if the table fields are up to date
    function test__getUnusedTableFields__returns_an_empty_array_if_the_table_fields_are_up_to_date()
    {
        $fields = ['field1' => 'type1', 'field2' => 'type2'];
        $instance = $this->instance;
        $instance->fields = $fields;
        
        $this->statement->shouldReceive('execute')->once()->andReturn(true);
        $this->statement->shouldReceive('fetchAll')
            ->once()
            ->andReturn([['Field' => 'field1', 'Type' => 'type1'], ['Field' => 'field2', 'Type' => 'type2']]);

        $this->assertEquals([], $instance->getUnusedTableFields());
    }

    ### getUnusedTableFields returns an array of unused fields if some fields are not in the entity
    function test__getUnusedTableFields__returns_an_array_of_unused_fields_if_some_fields_are_not_in_the_entity()
    {
        $fields = ['field1' => 'type1', 'field2' => 'type2'];
        $unusedField = ['field3' => 'type3'];
        $instance = $this->instance;
        $instance->fields = $fields;
        
        $this->statement->shouldReceive('execute')->once()->andReturn(true);
        $this->statement->shouldReceive('fetchAll')
            ->once()
            ->andReturn([['Field' => 'field1', 'Type' => 'type1'], ['Field' => 'field2', 'Type' => 'type2'], ['Field' => 'field3', 'Type' => 'type3']]);

        $this->assertEquals($unusedField, $instance->getUnusedTableFields());
    }

    ## ManageUnusedTableFields
    ### ManageUnusedTableFields shows a warning message
    function test__ManageUnusedTableFields__shows_a_warning_message()
    {
        $unusedFields = ['field3' => 'type3'];
        $instance = $this->instance;

        ErrorCatcher::catch(1);

        $instance->ManageUnusedTableFields($unusedFields);

        $this->assertTrue(ErrorCatcher::hasCaught(['message' => 'Unused fields in the database :']));
    }


}
