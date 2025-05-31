<?php
// bootstrap.php - Place this in your project root directory

// Error reporting for development
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Define project paths if not already defined
if (!defined('ROOT_DIR')) {
    define('ROOT_DIR', __DIR__);
}
if (!defined('SRC_DIR')) {
    define('SRC_DIR', ROOT_DIR . '/src');
}
if (!defined('VIEW_DIR')) {
    define('VIEW_DIR', SRC_DIR . '/views');
}
if (!defined('CONFIG_DIR')) {
    define('CONFIG_DIR', SRC_DIR . '/config');
}

// Use Composer's autoloader
require_once __DIR__ . '/vendor/autoload.php';

// Include any additional configuration files
if (file_exists(CONFIG_DIR . '/database.php')) {
    require_once CONFIG_DIR . '/database.php';
}

// You can add other initialization code here
?>