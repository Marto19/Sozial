<?php
session_start();

error_reporting(E_ALL);
ini_set('display_errors', 1);

include("connection.php");
include("functions.php");

// Check if the user is logged in
$user_data = check_login($con);

// // Debugging: Print user data to check if the user is logged in
// echo '<pre>';
// print_r($user_data);
// echo '</pre>';

if (!$con) {
    die("Connection failed: " . mysqli_connect_error());
}

// Assuming $user_data['user_id'] contains the user ID
$user_id = isset($user_data['id']) ? $user_data['id'] : null;

if ($user_id === null) {
    echo "Error: User ID is not set.";
    exit();
}
// Verify that the user exists in the users table
$user_verification_query = "SELECT * FROM users WHERE id = '$user_id'";
$user_verification_result = mysqli_query($con, $user_verification_query);

if (!$user_verification_result) {
    echo "Query failed: " . mysqli_error($con);
    exit();
}

$user_exists = mysqli_num_rows($user_verification_result) > 0;

if (!$user_exists) {
    // The user ID does not exist
    echo "Error: Invalid user ID.";
    exit();
}

// Process the form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Assuming you have $post_id available from the form submission
    $post_id = $_POST["post_id"];

    // Check if the user has already liked the post
    $like_check_query = "SELECT * FROM post_likes WHERE user_id = '$user_id' AND post_id = '$post_id'";
    $like_check_result = mysqli_query($con, $like_check_query);

    if (mysqli_num_rows($like_check_result) == 0) {
        // User hasn't liked the post yet, so add a like
        $insert_query = "INSERT INTO post_likes (user_id, post_id) VALUES ('$user_id', '$post_id')";
        if (mysqli_query($con, $insert_query)) {
            // Update the likes count in the posts table
            $update_query = "UPDATE posts SET likes_count = likes_count + 1 WHERE id = '$post_id'";
            mysqli_query($con, $update_query);

            // Redirect back to the index.php page
            header('Location: index.php');
            exit();
        } else {
            // Insert query failed
            echo "Error inserting like: " . mysqli_error($con);
        }
    } else {
        // User has already liked the post, handle accordingly (e.g., show a message)
        // echo "You have already liked this post.";
        
        header('Location: index.php');

        echo '<script>alert("You already like that post");</script>';
    }
}

// var_dump($_SESSION);
?>

//TODO: when pressing like again over and over - like-unlike
