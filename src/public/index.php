<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('error_log', dirname(__DIR__) . '/php_errors.log');
ini_set('log_errors', 1);

define('ROOT_DIR', dirname(__DIR__, 2));
require_once ROOT_DIR . '/bootstrap.php';

// Enable error display
error_reporting(E_ALL);
ini_set('display_errors', 1);

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

// Initialize controllers
$authController = new Controllers\AuthController();
$postController = new Controllers\PostController();
$commentController = new Controllers\CommentController();

// Route handling while maintaining existing URLs
switch ($route) {
    case '/login':
        $authController->login();
        break;
    case '/signup':
        $authController->signup();
        break;
    case '/logout':
        $authController->logout();
        break;
    case '/profile':
        $postController->userPosts();
        break;
    case '/submit_post':
        $postController->create();
        break;
    case '/like_post':
        $postController->like();
        break;
    case '/add_comment':
        $commentController->add();
        break;
    case '/delete_comment':
        $commentController->delete();
        break;
    case '/delete_post':
        $postController->delete();
        break;
    case '/':
    case '/index':
        $postController->index();
        break;    default:
        // 404 handling
        header($_SERVER["SERVER_PROTOCOL"] . " 404 Not Found");
        include(__DIR__ . '/../views/errors/404.php');
        exit;
        break;
}
