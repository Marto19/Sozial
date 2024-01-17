<?php
session_start();
include("connection.php");

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}


// Process the like submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $post_id = $_POST['post_id'];
    $user_id = $_SESSION['user_id'];

    // Update the likes_count in the database
    $update_query = "UPDATE posts SET likes_count = likes_count + 1 WHERE id = $post_id";
    mysqli_query($con, $update_query);

    // Redirect back to the feed page after liking the post
    header('Location: index.php');
    exit();
}
