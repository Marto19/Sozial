<?php
session_start();
include("connection.php");
include("functions.php");

// Check if the user is logged in
$user_data = check_login($con);

// Handle deleting a comment
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["delete_comment"])) {
    $comment_id = $_POST["comment_id"];

    // Delete the comment from the database
    $delete_comment_query = "DELETE FROM comments WHERE comment_id = $comment_id";
    mysqli_query($con, $delete_comment_query);
}

// Redirect back to the index.php page
header("Location: index.php");
exit();
?>
