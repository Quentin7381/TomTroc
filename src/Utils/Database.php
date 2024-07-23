<?php

namespace Utils;

/**
 * Classe utilitaire pour la gestion des tables en base de donnees.
 *
 * L'appel a prepare table permet de s'assurer qu'une table existe avec les champs adequats.
 */
class Database
{

    /**
     * @var Database $instance
     *
     * Instance de la classe Database.
     */
    protected static $instance;

    /**
     * @var PDO $pdo
     *
     * Instance de la classe PDO.
     */
    protected $pdo;

    /**
     * Constructeur de la classe Database.
     */
    public function __construct()
    {
        $this->pdo = PDO::getInstance();
    }

    /**
     * Retourne l'instance de la classe Database.
     *
     * @return Database
     */
    public static function getInstance(): self
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    /**
     * Prepare la table en base de donnees.
     * Si la table n'existe pas, elle est creee.
     * Si la table existe, elle est mise a jour.
     *
     * @param string $table Nom de la table.
     * @param array $fields Liste des champs de la table avec le nom du champ en cle et le type du champ en valeur.
     *      [
     *         'id' => 'INT PRIMARY KEY AUTO_INCREMENT',
     *         'name' => 'VARCHAR(255)',
     *          ...
     *      ]
     *
     * @throws Exception En cas d'erreur lors de la creation ou de la mise a jour de la table.
     * @throws Exception Si un champ de type different existe deja dans la table.
     *
     * Affiche un warning si un champ de la base de donnees n'est pas utilise.
     */
    public function prepareTable(string $table, array $fields)
    {
        if (!$this->tableExists($table)) {
            $this->createTable($table, $fields);
        } else {
            $this->updateTable($table, $fields);
        }
    }

    /**
     * Check if the table exists in the database.
     *
     * @return bool True if the table exists, false otherwise.
     */
    protected function tableExists(string $table): bool
    {
        $sql = "SHOW TABLES LIKE \"$table\"";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetch();
        return !empty($result);
    }

    /**
     * Create the table in the database.
     * The table name is the entity name.
     * The table fields are the entity fields.
     */
    protected function createTable(string $table, array $fields): void
    {
        $sql = "CREATE TABLE $table (";
        foreach ($fields as $field => $type) {
            $sql .= "$field $type, ";
        }
        $sql = rtrim($sql, ', ');
        $sql .= ")";


        $stmt = $this->pdo->prepare($sql);

        $success = $stmt->execute();
        if (!$success) {
            throw new Exception(Exception::DATABASE_ERROR, ['error' => implode(', ' . PHP_EOL, $stmt->errorInfo())]);
        }
    }

    /**
     * Update the table in the database.
     * Will call the missing, wrong and unused table fields methods.
     */
    protected function updateTable(string $table, array $fields): void
    {
        $missingFields = $this->getMissingTableFields($table, $fields);
        $wrongFields = $this->getWrongTableFields($table, $fields);
        $unusedFields = $this->getUnusedTableFields($table, $fields);

        if (!empty($missingFields)) {
            $this->manageMissingTableFields($table, $missingFields);
        }

        if (!empty($wrongFields)) {
            $this->manageWrongTableFields($table, $wrongFields);
        }

        if (!empty($unusedFields)) {
            $this->manageUnusedTableFields($table, $unusedFields);
        }
    }

    /**
     * Get the list of fields that are in the entity but not in the table.
     *
     * @return array An array of fields with the field name as key and the field type as value.
     */
    protected function getMissingTableFields(string $table, array $fields): array
    {
        $sql = "DESCRIBE $table";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetchAll();

        $dbFields = [];
        foreach ($result as $row) {
            $dbFields[$row['Field']] = $row['Type'];
        }

        foreach ($fields as $name => $type) {
            unset($fields[$name]);
        }

        return $fields;
    }

    /**
     * Get the fields that are of the wrong type in the table.
     *
     * @return array An array of fields with the field name as key and the field type as value.
     */
    public function getWrongTableFields(string $table, array $fields): array
    {
        $sql = "DESCRIBE $table";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetchAll();

        $dbFields = [];
        foreach ($result as $row) {
            $dbFields[$row['Field']] = $row['Type'];
        }

        $wrongFields = [];
        foreach ($fields as $name => $entityType) {
            if(!isset($dbFields[$name])){
                continue;
            }
            
            $dbType = $dbFields[$name];
            $entityType = strtolower($entityType);
            $dbType = strtolower($dbType);

            if ($entityType !== $dbType) {
                $wrongFields[$name] = $dbType;
            }
        }

        return $wrongFields;
    }

    /**
     * Get the fields that are in the table but not in the entity.
     *
     * @return array An array of fields with the field name as key and the field type as value.
     */
    public function getUnusedTableFields(string $table, array $fields): array
    {
        $sql = "DESCRIBE $table";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetchAll();

        $unusedFields = [];
        foreach ($result as $row) {
            $unusedFields[$row['Field']] = $row['Type'];
        }

        foreach ($fields as $name => $type) {
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
    public function manageMissingTableFields(string $table, array $fields): void
    {

        $sql = "ALTER TABLE $table ";
        foreach ($fields as $name => $type) {
            $sql .= "ADD $name $type, ";
        }
        $sql = rtrim($sql, ', ');
        $stmt = $this->pdo->prepare($sql);

        if (!$stmt->execute()) {
            throw new Exception(Exception::DATABASE_ERROR, ['error' => implode(', ' . PHP_EOL, $stmt->errorInfo())]);
        }
    }

    /**
     * Manage the wrong table fields.
     *
     * @param array $field An array of fields with the field name as key and the field type as value.
     */
    public function manageWrongTableFields(string $table, array $fields): void
    {
        throw new Exception(Exception::DATABASE_STRUCTURE_ERROR, ['table' => $table, 'fields' => $fields]);
    }

    /**
     * Manage the unused table fields.
     *
     * @param array $field An array of fields with the field name as key and the field type as value.
     */
    public function manageUnusedTableFields(string $table, array $fields): void
    {
        user_error("Unused fields in table '$table' : " . implode(', ', array_keys($fields)) . PHP_EOL .
            "Consider cleaning the database.");
    }
}
