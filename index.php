<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('error_log', __DIR__ . '/php_errors.log');

define('ROOT_DIR', __DIR__);
require_once ROOT_DIR . '/src/bootstrap.php';

try {
    $authController = new \Controllers\AuthController();

    $route = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    $route = rtrim($route, '/');
    $route = empty($route) ? '/' : $route;
    $route = str_replace('.php', '', $route);

    error_log("Routing request: " . $route);

    switch ($route) {
        case '/login':
            $authController->login();
            break;
        default:
            echo "404 Not Found";
            break;
    }
} catch (\Exception $e) {
    error_log("Error: " . $e->getMessage());
    echo "An error occurred. Check the error log for details.";
}