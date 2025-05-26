<?php
// Set up error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Define constants
define('BASE_PATH', __DIR__);
define('UPLOAD_DIR', __DIR__ . '/src/public/uploads/');
define('PROFILE_PICS_DIR', UPLOAD_DIR . 'profile_pics/');

// Autoloader
spl_autoload_register(function ($class) {
    $class = str_replace('\\', DIRECTORY_SEPARATOR, $class);
    $file = __DIR__ . '/src/' . $class . '.php';
    if (file_exists($file)) {
        require_once $file;
    } else {
        // Try the src directory without namespace
        $altFile = __DIR__ . '/src/' . str_replace('\\', '/', $class) . '.php';
        if (file_exists($altFile)) {
            require_once $altFile;
        }
    }
});

// Initialize database connection for backward compatibility
require_once __DIR__ . '/connection.php';

// Initialize security headers
Utils\Security::secureHeaders();

// Keep the existing functions.php for backward compatibility
require_once __DIR__ . '/functions.php';
