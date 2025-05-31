<?php
define('BASE_URL', 'http://localhost:8000');
define('UPLOAD_DIR', __DIR__ . '/../public/uploads/');
define('PROFILE_PICS_DIR', UPLOAD_DIR . 'profile_pics/');
define('IMAGES_DIR', UPLOAD_DIR . 'images/');
define('PUBLIC_DIR', __DIR__ . '/../public/');
define('DEFAULT_PROFILE_PIC', '/public/uploads/profile_pics/default_image.png');

// Session configuration
ini_set('session.cookie_httponly', 1);
ini_set('session.use_only_cookies', 1);
ini_set('session.cookie_secure', 1);

// Error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Time zone
date_default_timezone_set('UTC');
