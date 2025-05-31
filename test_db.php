<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

try {
    echo "Attempting connection...\n";
    
    $host = '127.0.0.1';
    $user = 'root';
    $pass = 'kali3301';
    $port = 3307;
    
    echo "Using settings:\n";
    echo "Host: $host\n";
    echo "Port: $port\n";
    echo "User: $user\n";
    
    $conn = mysqli_connect($host, $user, $pass, '', $port);
    
    if ($conn) {
        echo "Connected successfully!\n";
        
        $result = mysqli_query($conn, "SELECT VERSION()");
        $row = mysqli_fetch_row($result);
        echo "MySQL Version: " . $row[0] . "\n";
    }
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    echo "Error Code: " . mysqli_connect_errno() . "\n";
}