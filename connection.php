<?php

// Use the new Database class from Utils namespace
use Utils\Database;

// For backward compatibility
if (!function_exists('get_db_connection')) {
    function get_db_connection() {
        static $pdo = null;
        if ($pdo === null) {
            $pdo = Database::getInstance()->getConnection();
        }
        return $pdo;
    }
}

// Return the PDO connection
return get_db_connection();