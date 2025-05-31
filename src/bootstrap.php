<?php
// Set up error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Define constants
define('BASE_PATH', dirname(__DIR__));
define('UPLOAD_DIR', BASE_PATH . '/public/uploads/');
define('PROFILE_PICS_DIR', UPLOAD_DIR . 'profile_pics/');
define('VIEW_DIR', __DIR__ . '/views');

// Use Composer's autoloader
if (!class_exists('Composer\Autoload\ClassLoader')) {
    require_once dirname(__DIR__) . '/vendor/autoload.php';
}

// Initialize database connection for backward compatibility
require_once BASE_PATH . '/connection.php';

// Initialize security headers
Utils\Security::secureHeaders();

// Keep the existing functions.php for backward compatibility
require_once BASE_PATH . '/functions.php';
