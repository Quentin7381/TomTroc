<?php

namespace Manager;

use Config\Config;
use Utils\PDO;
use Utils\Database;
use Entity\AbstractEntity;
use Entity\LazyEntity;
use ReflectionClass;
use ReflectionProperty;
use Utils\StatementGenerator;
use Entity\Book;


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
        $sql = "SELECT * FROM $this->table WHERE id = :id";
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
        $update = $this->toDb($entity);
        $sql = "UPDATE $this->table SET ";
        foreach ($update as $field => $value) {
            $sql .= "$field = :$field,";
        }
        $sql = rtrim($sql, ',');
        $sql .= " WHERE id = :id";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($update);

        $class = 'Entity\\' . $this->getEntityName();
        $entity = new $class();
        $entity->fromDb($update);

        return $entity;
    }

    /**
     * Delete an entity from the database.
     *
     * @param int $id The id of the entity to delete.
     */
    public function delete(AbstractEntity|int $id): void
    {
        if ($id instanceof AbstractEntity) {
            $id = $id->id;
        }
        $sql = "DELETE FROM $this->table WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['id' => $id]);
    }

    public function hydrate(AbstractEntity $entity): AbstractEntity
    {
        $dbEntity = $this->exists($entity);

        if (empty($dbEntity)) {
            return $entity;
        }

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
                if (empty($value)) {
                    continue;
                }
                $entity->$field = $value;
            }
        }

        return $entity;
    }

    public function exists(AbstractEntity $entity): ?AbstractEntity
    {

        $identifiers = $this->database->getIdentifiers($this->table);

        $sql = "SELECT * FROM $this->table WHERE ";
        foreach ($identifiers as $field) {
            $sql .= "$field = :$field OR ";
        }
        $sql = rtrim($sql, 'OR ');
        $stmt = $this->pdo->prepare($sql);

        $param = [];
        foreach ($identifiers as $field) {
            $param[":$field"] = $entity->$field;
        }

        $stmt->execute($param);
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (count($result) > 1) {
            throw new Exception(Exception::ENTITY_UNIQUE_VALUES_COLLISION, ['search' => $entity->toDb(), 'found' => $result]);
        }

        if (empty($result)) {
            return null;
        }

        $fetch = $result[0];

        $class = 'Entity\\' . $this->getEntityName();
        $entity = new $class();
        $entity->fromDb($fetch);

        return $entity;
    }

    public function persist(AbstractEntity $entity): AbstractEntity
    {
        $merge = $this->exists($entity);

        if (!empty($merge)) {
            return $this->update($this->merge($merge, $entity));
        } else {
            return $this->insert($entity);
        }
    }

    public function getDbType(string $type): string
    {
        $nullable = strpos($type, '?') !== false;

        // Si la propriete est nullable, on prend le type
        $type = str_replace('?', '', $type);

        // Si la propriete est multitype, on prend le premier type
        $type = explode('|', $type);
        $type = $type[0];

        $types = [
            'int' => 'INT',
            'string' => 'VARCHAR(255)',
            'bool' => 'TINYINT(1)',
            'float' => 'FLOAT',
            'array' => 'TEXT',
            'object' => 'TEXT',
            'null' => 'TEXT',
        ];

        $type = $types[$type] ?? 'VARCHAR(255)';

        if (!$nullable) {
            $type .= ' NOT NULL';
        }

        return $type;
    }

    public function getLasts(): StatementGenerator
    {
        $pdo = PDO::getInstance();
        $sql = "SELECT * FROM book ORDER BY created DESC";
        $stmt = $pdo->prepare($sql);

        $generator = new StatementGenerator($stmt);

        $generator->current_set_post_process(function ($data) {
            $book = new Book();
            $book->fromDb($data);
            return $book;
        });

        return $generator;
    }

    public function fromDb(?AbstractEntity $entity, array $array): AbstractEntity
    {
        if (empty($entity)) {
            $entityClass = 'Entity\\' . $this->getEntityName();
            $entity = new $entityClass();
        }

        foreach ($array as $field => $value) {
            $entity->$field = $value;
        }

        return $entity;
    }

    public function toDb(AbstractEntity $entity): array
    {
        $entityClass = get_class($entity);

        $array = [];
        $fields = static::getEntityFields();
        foreach ($fields as $name => $type) {
            $value = $entity->get($name);

            // Entities become their id
            if (
                $value instanceof AbstractEntity
            ) {
                if (empty($value->get('id'))) {
                    $value = $value->persist();
                }

                $value = $value->get('id');
            }

            if ($value instanceof LazyEntity) {
                $value = $value->id;
            }

            if (is_bool($value)) {
                $value = $value ? 1 : 0;
            }

            $array[$name] = $value;
        }

        foreach ($entityClass::get_local_fields() as $field) {
            unset($array[$field]);
        }

        return $array;
    }

    public function getEntityClass(): string
    {
        return 'Entity\\' . $this->getEntityName();
    }

    /**
     * Get the fields of the entity.
     * Keys are the field names and values are the database types.
     *
     * @return array
     */
    public function getEntityFields(): array
    {
        $entityClass = $this->getEntityClass();
        $reflectionClass = new ReflectionClass($entityClass);
        $properties = $reflectionClass->getProperties(ReflectionProperty::IS_PROTECTED);

        $protectedProperties = [];
        foreach ($properties as $property) {
            $name = $property->getName();

            // Skip properties starting with an underscore
            if (strpos($name, '_') === 0) {
                continue;
            }

            if (method_exists(static::class, 'typeof_' . $name)) {
                $type = static::{'typeof_' . $name}();
            } else {
                $type = $property->getType();
                $type = static::getDbType($type);
            }

            $protectedProperties[$name] = $type;
        }

        foreach ($entityClass::get_local_fields() as $field) {
            unset($protectedProperties[$field]);
        }

        return $protectedProperties;
    }

    public static function typeof_id(): string
    {
        return 'int(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY';
    }
}
