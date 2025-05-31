<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('error_log', dirname(__DIR__) . '/php_errors.log');
ini_set('log_errors', 1);

define('ROOT_DIR', dirname(__DIR__, 2));
require_once ROOT_DIR . '/bootstrap.php';

// Initialize controllers
$authController = new \Controllers\AuthController();

// Map URLs to controller actions
$route = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$route = rtrim($route, '/');
if (empty($route)) {
    $route = '/';
}
$route = str_replace('.php', '', $route);

// Debug routing information
error_log("Requested URI: " . $_SERVER['REQUEST_URI']);
error_log("Mapped route: " . $route);

// Debug: Check if files exist
$controllerPath = ROOT_DIR . '/src/Controllers/AuthController.php';
error_log("Looking for controller at: " . $controllerPath);
error_log("File exists: " . (file_exists($controllerPath) ? 'YES' : 'NO'));

// Debug: Test autoloading
if (class_exists('Controllers\AuthController')) {
    error_log("AuthController class loaded successfully");
} else {
    error_log("AuthController class NOT loaded");
}

// Simple router
switch ($route) {
    case '/login':
        $authController->login();
        break;
    default:
        echo "404 Not Found";
        break;
}
