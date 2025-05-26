<?php
namespace Utils;

class Auth {
    private static $instance = null;
    private $db;

    private function __construct() {
        $this->db = Database::getInstance();
    }

    public static function getInstance(): self {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function login(string $username, string $password): bool {
        $stmt = $this->db->query(
            "SELECT * FROM users WHERE user_name = ? LIMIT 1",
            [$username]
        );
        
        $user = $stmt->fetch();
        
        if ($user && Security::verifyPassword($password, $user['password'])) {
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['user_name'] = $user['user_name'];
            $_SESSION['last_login'] = time();
            return true;
        }
        
        return false;
    }

    public function logout(): void {
        session_destroy();
        session_start();
        session_regenerate_id(true);
    }

    public function isLoggedIn(): bool {
        return isset($_SESSION['user_id']);
    }

    public function getCurrentUser(): ?array {
        if (!$this->isLoggedIn()) {
            return null;
        }

        $stmt = $this->db->query(
            "SELECT * FROM users WHERE user_id = ?",
            [$_SESSION['user_id']]
        );

        return $stmt->fetch();
    }

    public function requireLogin(): void {
        if (!$this->isLoggedIn()) {
            header('Location: /login');
            exit();
        }
    }

    private function __clone() {}
    private function __wakeup() {}
}
