<?php
require_once __DIR__ . '/../core/config.php';

// Simple PDO wrapper for database queries and statement handling.
class Database
{
    private $host = DB_HOST;
    private $user = DB_USER;
    private $password = DB_PASSWORD;
    private $dbname = DB_NAME;

    private $connection;
    private $error;
    private $stmt;
    private $dbconnected = false;

    // Open a PDO connection to the MySQL database using configuration constants.
    public function __construct()
    {
        $dsn = 'mysql:host=' . $this->host . ";dbname=" . $this->dbname;
        $option = [
            PDO::ATTR_PERSISTENT => true,
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        ];

        try {
            $this->connection = new PDO($dsn, $this->user, $this->password, $option);
            $this->dbconnected = true;
        } catch (PDOException $e) {
            $this->error = $e->getMessage();
            $this->dbconnected = false;
        }
    }

    // Return the last connection or query error message.
    public function getError()
    {
        return $this->error;
    }

    // Return whether the database connection opened successfully.
    public function isConnected()
    {
        return $this->dbconnected;
    }

    // Prepare a SQL statement for execution.
    public function query($query)
    {
        $this->stmt = $this->connection->prepare($query);
    }

    // Execute the current prepared statement.
    public function execute()
    {
        return $this->stmt->execute();
    }

    // Fetch all rows as an array of objects.
    public function set()
    {
        $this->execute();
        return $this->stmt->fetchAll(PDO::FETCH_OBJ);
    }

    // Fetch a single row as an object.
    public function single()
    {
        $this->execute();
        return $this->stmt->fetch(PDO::FETCH_OBJ);
    }

    // Return the number of rows affected by the last statement.
    public function rowCount()
    {
        return $this->stmt->rowCount();
    }

    // Return the last inserted ID from the connection.
    public function lastInsertId()
    {
        return $this->connection->lastInsertId();
    }

    // Check whether the given table exists in the current database.
    public function tableExists(string $tableName): bool
    {
        $this->query('SHOW TABLES LIKE :table');
        $this->bind(':table', $tableName);
        return (bool) $this->single();
    }

    // Create a table if it does not already exist, then return whether the operation succeeded.
    public function createTableIfMissing(string $tableName, string $createSql): bool
    {
        if ($this->tableExists($tableName)) {
            return true;
        }

        $this->query($createSql);
        return $this->execute();
    }

    // Bind a value to the prepared statement using the correct PDO type.
    public function bind($param, $value, $type = null)
    {
        if (is_null($type)) {
            switch (true) {
                case is_int($value):
                    $type = PDO::PARAM_INT;
                    break;
                case is_bool($value):
                    $type = PDO::PARAM_BOOL;
                    break;
                case is_null($value):
                    $type = PDO::PARAM_NULL;
                    break;
                default:
                    $type = PDO::PARAM_STR;
            }
        }

        $this->stmt->bindValue($param, $value, $type);
    }
}
?>