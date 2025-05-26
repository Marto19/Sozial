<?php

$dbhost = "127.0.0.1"; // Using IP instead of localhost
$dbuser = "sozial_user";
$dbpass = "password123";
$dbname = "sozial_db";

if(!$con = mysqli_connect($dbhost, $dbuser, $dbpass)){
    // Try to create the database if it doesn't exist
    $temp_con = mysqli_connect($dbhost, $dbuser, $dbpass);
    mysqli_query($temp_con, "CREATE DATABASE IF NOT EXISTS $dbname");
    mysqli_close($temp_con);
}

// Connect to the specific database
if(!$con = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname)){
    die("Failed to connect to database. Make sure MySQL is running and credentials are correct.");
}