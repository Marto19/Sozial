<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once dirname(__DIR__, 2) . '/bootstrap.php';

try {
    echo "Testing database connection...<br>";
    
    $db = Utils\Database::getInstance();
    echo "✓ Database instance created<br>";
    
    $conn = $db->getConnection();
    echo "✓ Got database connection<br>";
    
    $stmt = $conn->query('SELECT * FROM users LIMIT 1');
    echo "✓ Query executed<br>";
    
    $user = $stmt->fetch();
    echo "✓ Data fetched<br>";
    
    if ($user) {
        echo "<br>Connection test successful!<br>";
        echo "Found user: " . htmlspecialchars($user['user_name']) . "<br>";
    } else {
        echo "<br>Connection successful but no users found in database.<br>";
    }
} catch (\Exception $e) {
    echo "<br>❌ Error: " . htmlspecialchars($e->getMessage()) . "<br>";
    echo "Stack trace:<br><pre>" . htmlspecialchars($e->getTraceAsString()) . "</pre>";
}
