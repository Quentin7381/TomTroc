<?php

namespace Manager;

use Config\Config;
use Utils\PDO;
use Utils\View;

/**
 * AbstractManager class
 *
 * This class is the base class for all managers.
 */
abstract class AbstractManager
{

    /**
     * @var Config $config
     *
     * This object holds the configuration.
     */
    protected $config;

    /**
     * @var AbstractManager $instance
     *
     * This object holds the instance of the manager.
     */
    protected static $instance;

    /**
     * @var PDO $pdo
     *
     * This object holds the PDO object.
     */
    protected $pdo;

    /**
     * @var string $table
     *
     * This string holds the table name.
     */
    protected string $table;

    /**
     * @var array $fields
     *
     * This array holds the fields of the entity.
     * The key is the field name and the value is the field type.
     */
    protected array $fields;

    /**
     * Singleton constructor.
     */
    protected function __construct()
    {
        $this->config = Config::getInstance();
        $this->pdo = PDO::getInstance();
        self::$instance = $this;

        $this->table = strtolower($this->getEntityName());
        $this->fields = $this->getEntityFields();
        $this->prepareEntityTable();
    }

    /**
     * Get the instance of the manager.
     */
    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new static();
        }
        return self::$instance;
    }

    // ----- ENTITY MANAGEMENT -----

    /**
     * Get the entity name.
     */
    public function getEntityName()
    {
        $name = explode('\\', get_class($this));
        $name = end($name);
        $name = str_replace('Manager', '', $name);
        return $name;
    }

    /**
     * Get the entity fields.
     * The entity fields are the protected properties of the entity.
     * Any private properties are not returned.
     *
     * @return array An array of fields with the field name as key and the field type (for databases) as value.
     */
    public function getEntityFields()
    {
        $entity = 'Entity\\' . $this->getEntityName();
        return $entity::getFields();
    }

    // ----- DATABASE MANAGEMENT -----

    /**
     * Prepare the entity table in the database.
     * Will create a table with the entity name and the entity fields.
     */
    public function prepareEntityTable()
    {
        if (!$this->tableExists()) {
            return $this->createTable();
        }

        return $this->updateTable();
    }

    /**
     * Create the table in the database.
     * The table name is the entity name.
     * The table fields are the entity fields.
     */
    protected function createTable()
    {
        $sql = "CREATE TABLE $this->table (";
        $sql .= "id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,";
        foreach ($this->fields as $field => $type) {
            if ($field == 'id') {
                continue;
            }
            $sql .= "$field $type, ";
        }
        $sql = rtrim($sql, ', ');
        $sql .= ")";

        $stmt = $this->pdo->prepare($sql);
        
        $success = $stmt->execute();
        if (!$success) {
            throw new Exception("Error creating table $this->table : " . implode(', ' . PHP_EOL, $stmt->errorInfo()));
        }
    }

    /**
     * Get the list of fields that are in the entity but not in the table.
     *
     * @return array An array of fields with the field name as key and the field type as value.
     */
    protected function getMissingTableFields()
    {
        $sql = "DESCRIBE $this->table";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetchAll();

        $dbFields = [];
        foreach ($result as $row) {
            $dbFields[$row['Field']] = $row['Type'];
        }

        $missingFields = $this->fields;
        foreach ($dbFields as $name => $type) {
            unset($missingFields[$name]);
        }

        return $missingFields;
    }

    /**
     * Get the fields that are of the wrong type in the table.
     *
     * @return array An array of fields with the field name as key and the field type as value.
     */
    public function getWrongTableFields()
    {
        $sql = "DESCRIBE $this->table";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetchAll();

        $dbFields = [];
        foreach ($result as $row) {
            $dbFields[$row['Field']] = $row['Type'];
        }

        $wrongFields = [];
        foreach ($this->fields as $name => $entityType) {
            $dbType = $dbFields[$name] ?? null;
            $entityType = strtolower($entityType);
            $dbType = strtolower($dbType);
            
        }

        return $wrongFields;
    }

    /**
     * Get the fields that are in the table but not in the entity.
     *
     * @return array An array of fields with the field name as key and the field type as value.
     */
    public function getUnusedTableFields()
    {
        $sql = "DESCRIBE $this->table";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetchAll();

        $unusedFields = [];
        foreach ($result as $row) {
            $unusedFields[$row['Field']] = $row['Type'];
        }

        foreach ($this->fields as $name => $type) {
            unset($unusedFields[$name]);
        }

        return $unusedFields;
    }

    // ----- TABLE MANAGEMENT -----

    /**
     * Manage the missing table fields.
     *
     * @param array $field An array of fields with the field name as key and the field type as value.
     */
    public function manageMissingTableFields(array $fields)
    {

        $sql = "ALTER TABLE $this->table ";
        foreach ($fields as $name => $type) {
            $sql .= "ADD $name $type, ";
        }
        $sql = rtrim($sql, ', ');
        $stmt = $this->pdo->prepare($sql);

        if (!$stmt->execute()) {
            throw new Exception("Error adding fields to table $this->table : " . implode(', ' . PHP_EOL, $stmt->errorInfo()));
        }
    }

    /**
     * Manage the wrong table fields.
     *
     * @param array $field An array of fields with the field name as key and the field type as value.
     */
    public function manageWrongTableFields(array $fields)
    {
        throw new Exception("Missmatch between entity fields and database fields : " . implode(', ', array_keys($fields)) . PHP_EOL .
            "Please update the entity fields or the database fields.");
    }

    /**
     * Manage the unused table fields.
     *
     * @param array $field An array of fields with the field name as key and the field type as value.
     */
    public function manageUnusedTableFields(array $fields)
    {
        user_error("Unused fields in the database : " . implode(', ', array_keys($fields)) . PHP_EOL .
            "Consider cleaning the database.");
    }

    /**
     * Update the table in the database.
     * Will call the missing, wrong and unused table fields methods.
     */
    protected function updateTable()
    {
        $missingFields = $this->getMissingTableFields();
        $wrongFields = $this->getWrongTableFields();
        $unusedFields = $this->getUnusedTableFields();

        if (!empty($missingFields)) {
            $this->manageMissingTableFields($missingFields);
        }

        if (!empty($wrongFields)) {
            $this->manageWrongTableFields($wrongFields);
        }

        if (!empty($unusedFields)) {
            $this->manageUnusedTableFields($unusedFields);
        }
    }

    /**
     * Check if the table exists in the database.
     *
     * @return bool True if the table exists, false otherwise.
     */
    protected function tableExists()
    {
        $sql = "SHOW TABLES LIKE \"$this->table\"";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetch();
        return !empty($result);
    }

    // ----- CRUD -----

    /**
     * Get all the entities.
     *
     * @return array An array of entities.
     */
    public function getAll()
    {
        $sql = "SELECT * FROM $this->table";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    /**
     * Search for an entity.
     * Uses an array structured like [
     *    'field' => [
     *       'operator' => 'LIKE',
     *       'value' => '%search%'
     *   ]
     * ]
     *
     * @param array $param an array of fields with the operator and the value.
     *
     * @return array The entity.
     */
    public function search(array $search)
    {
        $param = [];
        $sql = "SELECT * FROM $this->table WHERE ";
        foreach ($search as $field => $value) {
            $param[":$field"] = $value['value'];
            $sql .= "$field ";
            $sql .= $value['operator'] ?? '=';
            $sql .= " :$field AND ";
        }
        $sql = rtrim($sql, 'AND ');
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($param);

        $fetch =  $stmt->fetchAll(PDO::FETCH_ASSOC);

        $return = [];
        $class = 'Entity\\' . $this->getEntityName();
        foreach ($fetch as $id => $item) {
            $entity = new $class();
            $entity->fromArray($item);
            $return[] = $entity;
        }

        return $return;
    }

    /**
     * Get an entity by its id.
     *
     * @param int $id The id of the entity.
     *
     * @return array The entity.
     */
    public function getById($id)
    {
        $sql = "SELECT * FROM $this->table WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['id' => $id]);
        $fetch =  $stmt->fetch(PDO::FETCH_ASSOC);

        if (empty($fetch)) {
            return null;
        }

        $class = 'Entity\\' . $this->getEntityName();
        $entity = new $class();
        $entity->fromArray($fetch);
        return $entity;
    }

    /**
     * Insert an entity in the database.
     *
     * @param array $entity The entity to insert.
     */
    public function insert($entity)
    {
        $insert = $entity->toArray();

        $sql = "INSERT INTO $this->table (";
        foreach ($insert as $field => $value) {
            $sql .= "$field,";
        }
        $sql = rtrim($sql, ',');
        $sql .= ") VALUES (";
        foreach ($insert as $field => $value) {
            $sql .= ":$field,";
        }
        $sql = rtrim($sql, ',');
        $sql .= ")";

        $stmt = $this->pdo->prepare($sql);

        try {
            $stmt->execute($insert);
        } catch (\Exception $e) {
            throw new Exception("Error inserting entity in table $this->table : " . $e->getMessage());
        }

        return $this->pdo->lastInsertId();
    }

    /**
     * Update an entity in the database.
     *
     * @param array $entity The entity to update.
     */
    public function update($entity)
    {
        $entity = $entity->toArray();
        $sql = "UPDATE $this->table SET ";
        foreach ($entity as $field => $value) {
            $sql .= "$field = :$field,";
        }
        $sql = rtrim($sql, ',');
        $sql .= " WHERE id = :id";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($entity);
    }

    /**
     * Delete an entity from the database.
     *
     * @param int $id The id of the entity to delete.
     */
    public function delete($id)
    {
        $sql = "DELETE FROM $this->table WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['id' => $id]);
    }
}
