<?php
namespace Utils;

class Database {
    private static $instance = null;
    private $connection;
    
    private function __construct() {
        try {
            $config = require __DIR__ . '/../config/database.php';
            if (!isset($config['database'])) {
                throw new \Exception("Database configuration not found");
            }
            $db = $config['database'];

            if (!isset($db['host']) || !isset($db['username']) || !isset($db['password']) || !isset($db['dbname'])) {
                throw new \Exception("Incomplete database configuration");
            }

            $port = $db['port'] ?? 3306;
            $dsn = "mysql:host={$db['host']};port={$port};dbname={$db['dbname']};charset={$db['charset']}";
            
            $options = [
                \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
                \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
                \PDO::ATTR_EMULATE_PREPARES => false,
                \PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT => false,
                \PDO::MYSQL_ATTR_SSL_CA => false
            ];

            try {
                $this->connection = new \PDO($dsn, $db['username'], $db['password'], $options);
                // Test the connection
                $this->connection->query("SELECT 1");
            } catch (\PDOException $e) {
                // If SSL is causing issues, try without SSL options
                unset($options[\PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT]);
                unset($options[\PDO::MYSQL_ATTR_SSL_CA]);
                $this->connection = new \PDO($dsn, $db['username'], $db['password'], $options);
            }
        } catch (\Exception $e) {
            throw new \Exception("Database Error: " . $e->getMessage());
        }
    }

    public static function getInstance(): self {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function getConnection(): \PDO {
        return $this->connection;
    }

    public function query(string $sql, array $params = []): \PDOStatement {
        $stmt = $this->connection->prepare($sql);
        $stmt->execute($params);
        return $stmt;
    }

    private function __clone() {}
    public function __wakeup() {}
}
