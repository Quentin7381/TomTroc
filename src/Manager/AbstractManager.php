<?php

namespace Manager;

use Config\Config;
use Utils\PDO;
use Utils\Database;
use Entity\AbstractEntity;

/**
 * AbstractManager class
 *
 * This class is the base class for all managers.
 */
abstract class AbstractManager
{
    /**
     * @var array $instances
     *
     * This object holds the instance of the manager.
     */
    protected static $instances = [];

    /**
     * @var Config $config
     *
     * This object holds the configuration.
     */
    protected $config;

    /**
     * @var PDO $pdo
     *
     * This object holds the PDO object.
     */
    protected $pdo;

    protected $database;

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

    // ----- INITIALIZATION -----

    /**
     * Singleton constructor.
     */
    protected function __construct()
    {
        $this->config = Config::getInstance();
        $this->pdo = PDO::getInstance();
        $this->database = Database::getInstance();

        $cls = get_class($this);
        static::$instances[$cls] = $this;

        $this->table = strtolower($this->getEntityName());
        $this->fields = $this->getEntityFields();
        $this->database->prepareTable($this->table, $this->fields);
    }

    /**
     * Get the instance of the manager.
     */
    public static function getInstance(): AbstractManager
    {
        $cls = get_called_class();
        if (empty(self::$instances[$cls])) {
            self::$instances[$cls] = new $cls();
        }
        return self::$instances[$cls];
    }

    // ----- ENTITY MANAGEMENT -----

    /**
     * Get the entity name.
     */
    public function getEntityName(): string
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
    public function getEntityFields(): array
    {
        $entity = 'Entity\\' . $this->getEntityName();
        return $entity::getFields();
    }

    // ----- CRUD -----

    /**
     * Get all the entities.
     *
     * @return array An array of entities.
     */
    public function getAll(): array
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
    public function search(array $search): array
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

        $fetch = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $return = [];
        $class = 'Entity\\' . $this->getEntityName();
        foreach ($fetch as $id => $item) {
            $entity = new $class();
            $entity->fromDb($item);
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
    public function getById(string $id): ?AbstractEntity
    {
        $class = 'Entity\\' . $this->getEntityName();
        $sql = "SELECT * FROM $this->table WHERE    id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['id' => $id]);
        $fetch = $stmt->fetch(PDO::FETCH_ASSOC);

        if (empty($fetch)) {
            return null;
        }

        $entity = new $class();
        $entity->fromDb($fetch);
        return $entity;
    }

    /**
     * Insert an entity in the database.
     *
     * @param AbstractEntity $entity The entity to insert.
     */
    public function insert(AbstractEntity $entity): AbstractEntity
    {
        $insert = $entity->toDb();

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
            throw new Exception(Exception::DATABASE_ERROR, ['error' => $e->getMessage()]);
        }

        $entity->id = $this->pdo->lastInsertId();
        return $entity;
    }

    /**
     * Update an entity in the database.
     *
     * @param array $entity The entity to update.
     */
    public function update(AbstractEntity $entity): AbstractEntity
    {
        $update = $entity->toDb();
        $sql = "UPDATE $this->table SET ";
        foreach ($update as $field => $value) {
            $sql .= "$field = :$field,";
        }
        $sql = rtrim($sql, ',');
        $sql .= " WHERE id = :id";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($update);

        return $entity;
    }

    /**
     * Delete an entity from the database.
     *
     * @param int $id The id of the entity to delete.
     */
    public function delete(string $id): void
    {
        $sql = "DELETE FROM $this->table WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['id' => $id]);
    }

    public function hydrate(AbstractEntity $entity): AbstractEntity
    {
        $id = $entity->id;
        $dbEntity = $this->getById($id);

        foreach ($entity as $field => $type) {
            $dbEntity->$field = $entity->$field;
        }

        return $dbEntity;
    }

    public function merge(AbstractEntity|array ...$entities): AbstractEntity
    {
        $entity = array_shift($entities);
        if (!$entity instanceof AbstractEntity) {
            $class = 'Entity\\' . $this->getEntityName();
            $e = new $class();
            $e->fromArray($entity);
            $entity = $e;
        }

        foreach ($entities as $merge) {
            if (!is_array($merge)) {
                $merge = $merge->toArray();
            }
            foreach ($merge as $field => $value) {
                $entity->$field = $value;
            }
        }

        return $entity;
    }

    public function exists(AbstractEntity $entity): AbstractEntity|bool
    {
        $sql = "SELECT * FROM $this->table WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['id' => $entity->id]);
        $fetch = $stmt->fetch(PDO::FETCH_ASSOC);

        if (empty($fetch)) {
            return false;
        }

        $entity->fromDb($fetch);
        return $entity;
    }

    public function persist(AbstractEntity $entity): AbstractEntity
    {
        if ($merge = $this->exists($entity)) {
            return $this->update($this->merge($merge, $entity));
        } else {
            return $this->insert($entity);
        }
    }
}
