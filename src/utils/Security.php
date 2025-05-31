<?php
namespace Utils;

class Security {
    public static function sanitize($data) {
        if (is_array($data)) {
            return array_map([self::class, 'sanitize'], $data);
        }
        return htmlspecialchars(trim($data), ENT_QUOTES, 'UTF-8');
    }

    public static function generateCsrfToken() {
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }

    public static function validateCsrfToken($token) {
        return isset($_SESSION['csrf_token']) && 
               hash_equals($_SESSION['csrf_token'], $token);
    }

    public static function hashPassword($password) {
        return password_hash($password, PASSWORD_DEFAULT);
    }

    public static function verifyPassword($password, $hash) {
        return password_verify($password, $hash);
    }

    public static function secureHeaders() {
        // Protect against XSS and other injection attacks
        header("X-XSS-Protection: 1; mode=block");
        // Prevent MIME-sniffing
        header("X-Content-Type-Options: nosniff");
        // Enable HSTS
        header("Strict-Transport-Security: max-age=31536000; includeSubDomains");
        // Prevent clickjacking
        header("X-Frame-Options: SAMEORIGIN");
        // Set CSP
        header("Content-Security-Policy: default-src 'self'; script-src 'self' 'unsafe-inline' 'unsafe-eval'; style-src 'self' 'unsafe-inline';");
    }
}
