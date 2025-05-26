<?php
namespace Models;

use Utils\Database;

abstract class Model {
    protected $db;
    protected $table;
    protected $primaryKey = 'id';

    public function __construct() {
        $this->db = Database::getInstance();
    }

    public function find(int $id): ?array {
        $stmt = $this->db->query(
            "SELECT * FROM {$this->table} WHERE {$this->primaryKey} = ? LIMIT 1",
            [$id]
        );
        return $stmt->fetch() ?: null;
    }

    public function all(): array {
        $stmt = $this->db->query("SELECT * FROM {$this->table}");
        return $stmt->fetchAll();
    }

    public function create(array $data): int {
        $fields = array_keys($data);
        $values = array_values($data);
        $placeholders = str_repeat('?,', count($fields) - 1) . '?';
        
        $fields = implode(',', $fields);
        
        $stmt = $this->db->query(
            "INSERT INTO {$this->table} ({$fields}) VALUES ({$placeholders})",
            $values
        );
        
        return (int) $this->db->getConnection()->lastInsertId();
    }

    public function update(int $id, array $data): bool {
        $fields = array_keys($data);
        $set = implode('=?,', $fields) . '=?';
        $values = array_values($data);
        $values[] = $id;

        $stmt = $this->db->query(
            "UPDATE {$this->table} SET {$set} WHERE {$this->primaryKey} = ?",
            $values
        );

        return $stmt->rowCount() > 0;
    }

    public function delete(int $id): bool {
        $stmt = $this->db->query(
            "DELETE FROM {$this->table} WHERE {$this->primaryKey} = ?",
            [$id]
        );
        
        return $stmt->rowCount() > 0;
    }

    public function where(string $field, $value): array {
        $stmt = $this->db->query(
            "SELECT * FROM {$this->table} WHERE {$field} = ?",
            [$value]
        );
        return $stmt->fetchAll();
    }

    public function paginate(int $page = 1, int $perPage = 10): array {
        $offset = ($page - 1) * $perPage;
        
        $stmt = $this->db->query(
            "SELECT * FROM {$this->table} LIMIT ? OFFSET ?",
            [$perPage, $offset]
        );
        
        return $stmt->fetchAll();
    }
}
