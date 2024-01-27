<?php
session_start();
include("connection.php");
include("functions.php");

// Check if the user is logged in
$user_data = check_login($con);

// Function to escape user input and prevent SQL injection
function escape_input($con, $input)
{
    return mysqli_real_escape_string($con, $input);
}

// Handle adding a new comment
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["add_comment"])) {
    $post_id = escape_input($con, $_POST["post_id"]);
    $comment_text = escape_input($con, $_POST["comment_text"]);
    $user_id = $user_data['user_id'];

    // Print the values for debugging
    echo "User ID: $user_id<br>";
    echo "Post ID: $post_id<br>";
    echo "Comment Text: $comment_text<br>";

    // Debugging statements
var_dump($user_id, $post_id, $comment_text);

// Insert comment into the database
$insert_comment_query = "INSERT INTO comments (user_id, post_id, COMMENT, created_at) 
                         VALUES ('$user_id', '$post_id', '$comment_text', current_timestamp())";



    if (mysqli_query($con, $insert_comment_query)) {
        // Comment added successfully, redirect to index.php
        header("Location: index.php");
        exit();
    } else {
        // Display an error message if the query fails
        echo "Error: " . mysqli_error($con);
    }
} else {
    // Redirect back to the index.php page if the form was not submitted properly
    header("Location: index.php");
    exit();
}
?>