<?php
namespace Utils;

class Database {
    private static $instance = null;
    private $connection;    private function __construct() {
        try {
            $config = require __DIR__ . '/../config/database.php';
            if (!isset($config['database'])) {
                throw new \Exception("Database configuration not found");
            }
            $db = $config['database'];

            if (!isset($db['host']) || !isset($db['username']) || !isset($db['password']) || !isset($db['dbname'])) {
                throw new \Exception("Incomplete database configuration");
            }

            try {
            $this->connection = new \PDO(
                "mysql:host={$db['host']};dbname={$db['dbname']};charset={$db['charset']}",
                $db['username'],
                $db['password'],
                [
                    \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
                    \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
                    \PDO::ATTR_EMULATE_PREPARES => false
                ]
            );
        } catch (\PDOException $e) {
            throw new \Exception("Connection failed: " . $e->getMessage());
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
    private function __wakeup() {}
}
