<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "Starting connection test...<br>";

try {
    // Test MySQL configuration
    echo "Testing MySQL configuration...<br>";
    echo "Default socket: " . ini_get('mysqli.default_socket') . "<br>";
    echo "Default port: " . ini_get('mysqli.default_port') . "<br>";
    
    // Try TCP connection
    echo "Testing TCP connection...<br>";
    $tcp = mysqli_connect('127.0.0.1', 'root', '', '', 3307);
    echo "TCP connection successful!<br>";
    
    // Try setting password
    $query = "ALTER USER 'root'@'localhost' IDENTIFIED BY 'kali3301'";
    mysqli_query($tcp, $query);
    echo "Password set successfully!<br>";
    
    mysqli_close($tcp);
    
    echo "All tests completed successfully!";
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "<br>";
    echo "Code: " . mysqli_connect_errno() . "<br>";
    exit;
}