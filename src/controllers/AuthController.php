<?php
// src/Controllers/AuthController.php

namespace Controllers;

use Utils\Auth;
use Utils\Security;
use Models\User;

class AuthController {
    private $auth;
    private $user;
    
    public function __construct() {
        $this->auth = new Auth();
        $this->user = new User();
    }
    
    public function login() {
        error_log("Login method called");
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Handle login POST request
            $this->handleLoginPost();
            return;
        }
        
        // Show login form
        $this->showLoginForm();
    }
    
    private function handleLoginPost() {
        // Add your login logic here
        $username = $_POST['username'] ?? '';
        $password = $_POST['password'] ?? '';
        
        // Validate and authenticate user
        // Example implementation:
        if ($this->auth->authenticate($username, $password)) {
            // Redirect to dashboard or home page
            header('Location: /dashboard');
            exit;
        } else {
            $error = 'Invalid username or password';
            $this->showLoginForm($error);
        }
    }
    
    private function showLoginForm($error = null) {
        // Check if view file exists
        $viewFile = VIEW_DIR . '/auth/login.php';
        if (file_exists($viewFile)) {
            include $viewFile;
        } else {
            error_log("View file not found: " . $viewFile);
            echo "Login form template not found.";
        }
    }
}

// Keep the existing helper function to maintain compatibility
function random_num($length) {
    $text = "";
    if ($length < 5) {
        $length = 5;
    }
    $len = rand(4, $length);
    for ($i = 0; $i < $len; ++$i) {
        $text .= rand(0, 9);
    }
    return $text;
}
?>

<?php

namespace Utils;

class Auth {
    public function authenticate($username, $password) {
        // Example authentication logic, replace with your own
        // For demonstration, let's assume a hardcoded user
        if ($username === 'admin' && $password === 'password') {
            return true;
        }
        // You can implement real authentication with database here
        return false;
    }
}

?>