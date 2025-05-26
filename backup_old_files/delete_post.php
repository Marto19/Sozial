<?php
session_start();
include("connection.php");
include("functions.php");

// Check if the user is logged in
$user_data = check_login($con);

// Check if a post deletion request is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["post_id"])) {
    $delete_post_id = $_POST["post_id"];

    // Implement logic to delete the post from the database based on $delete_post_id
    $delete_post_query = "DELETE FROM posts WHERE id = $delete_post_id";
    mysqli_query($con, $delete_post_query);

    // Redirect to the profile page after deletion
    header("Location: profile.php");
    exit();
} else {
    // If the request is not valid, redirect to the profile page
    header("Location: profile.php");
    exit();
}
?>
