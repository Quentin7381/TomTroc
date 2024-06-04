<?php

namespace Test\Controller;

require_once __DIR__ . '/../../vendor/autoload.php';

use Controller\AbstractManager;
use Controller\Exception;
use Test\Reflection;
use Test\ReflectionInstance;
use Test\TestInit;
use Mockery as m;

abstract class AbstractManagerTest extends TestInit{

    public function setUp(): void {
        parent::setUp();

        $this->AbstractManager = Reflection::_GET_INSTANCE(AbstractManager::class);
    }

    ## __construct
    ### construct loads the configuration through Config::getInstance
    ### construct set the instance of the manager
    ### construct sets the table name
    ### construct sets the table fields
    ### construct prepares the entity table in the database

    ## getInstance
    ### getInstance returns the instance of the manager
    ### getInstance creates a new instance of the manager if it does not exist

    ## getEntityName
    ### getEntityName returns the associated entity name without the namespace

    ## getEntityFields
    ### uses the getFields method of the entity to get the fields

    ## prepareEntityTable
    ### prepareEntityTable calls tableExists to check if the table exists
    ### if the table does not exist, prepareEntityTable calls createTable to create the table
    ### if the table exists, prepareEntityTable calls updateTable to update the table

    ## tableExists
    ### tableExists returns true if the table exists
    ### tableExists returns false if the table does not exist
    ### tableExists throws an exception if the sql fails

    ## createTable
    ### createTable creates the table in the database
    ### createTable throws an exception if the sql fails
    ### the created table has the name of the entity
    ### the created table has the fields of the entity
    
    ## updateTable
    ### untested : only calls sub methods

    ## getMissingTalbeFields
    ### getMissingTalbeFields returns an empty array if the table fields are up to date
    ### getMissingTalbeFields returns an array of missing fields if the table fields are not up to date

    ## ManageMissingTableFields
    ### ManageMissingTableFields sets the missing fields in the table
    ### ManageMissingTableFields throws an exception if the sql fails

    ## getWrongTableFields
    ### getWrongTableFields returns an empty array if the table fields are up to date
    ### getWrongTableFields returns an array of wrong fields if some fields are of the wrong type

    ## ManageWrongTableFields
    ### ManageWrongTableFields throw an exception

    ## getUnusedTableFields
    ### getUnusedTableFields returns an empty array if the table fields are up to date
    ### getUnusedTableFields returns an array of unused fields if some fields are not in the entity

    ## ManageUnusedTableFields
    ### ManageUnusedTableFields shows a warning message


}
