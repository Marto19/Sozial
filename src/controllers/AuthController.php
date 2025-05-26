<?php
namespace Controllers;

use Utils\Auth;
use Utils\Security;
use Models\User;

class AuthController {
    private $auth;
    private $user;

    public function __construct() {
        $this->auth = Auth::getInstance();
        $this->user = new User();
    }    public function login() {
        try {
            if ($_SERVER['REQUEST_METHOD'] == "POST") {
                $user_name = Security::sanitizeInput($_POST['user_name']);
                $password = $_POST['password'];

                if (!empty($user_name) && !empty($password) && !is_numeric($user_name)) {
                $user = $this->user->findByUsername($user_name);                if ($user && Security::verifyPassword($password, $user['password'])) {
                    $_SESSION['user_id'] = $user['user_id'];
                    header("Location: /");
                    exit;
                } else {
                    echo '<script>alert("Wrong username or password!");</script>';
                }
            } else {                echo '<script>alert("Please enter valid information!");</script>';
            }
        }        } catch (\Exception $e) {
            error_log("Login error: " . $e->getMessage());
            error_log("Stack trace: " . $e->getTraceAsString());
            echo '<script>alert("An error occurred during login. Please try again.");</script>';
        }
        
        try {
            $loginPath = __DIR__ . '/../views/auth/login.php';
            error_log("Attempting to load login view from: " . $loginPath);
            if (!file_exists($loginPath)) {
                throw new \Exception("Login view file not found at: " . $loginPath);
            }
            include($loginPath);
        } catch (\Exception $e) {
            error_log("View error: " . $e->getMessage());
            echo "Error loading login page. Please try again later.";
        }
    }

    public function signup() {
        if ($_SERVER['REQUEST_METHOD'] == "POST") {
            $user_name = Security::sanitizeInput($_POST['user_name']);
            $password = $_POST['password'];

            if (!empty($user_name) && !empty($password) && !is_numeric($user_name)) {
                // Check if user exists
                if ($this->user->findByUsername($user_name)) {
                    echo '<script>alert("User with this username already exists!");</script>';
                } else {
                    $userData = [
                        'user_id' => random_num(20), // Maintaining existing function
                        'user_name' => $user_name,
                        'password' => $password
                    ];

                    if ($this->user->createUser($userData)) {
                        header("Location: login.php");
                        exit;
                    }
                }
            } else {
                echo "Please enter valid information!";
            }
        }
          // Include the signup view
        include(__DIR__ . '/../views/auth/signup.php');
    }

    public function logout() {
        if(isset($_SESSION['user_id'])) {
            unset($_SESSION['user_id']);
        }
        
        session_destroy();
        header("Location: login.php");
        exit;
    }
}

// Keep the existing helper function to maintain compatibility
function random_num($length) {
    $text = "";
    if($length < 5){
        $length = 5;
    }

    $len = rand(4, $length);
    for($i = 0; $i < $len; ++$i){
        $text .= rand(0, 9);
    }

    return $text;
}
