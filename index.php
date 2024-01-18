<?php
session_start();
include("connection.php");
include("functions.php");

// Check if the user is logged in
$user_data = check_login($con);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>PostJunkyard</title>
    
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
    /* Add your custom styles here */
    body {
        padding-top: 56px;
    }

    .post {
        margin-bottom: 20px;
        padding: 20px; /* Add padding to increase the size of the post */
        border: 1px solid #ccc; /* Add a border for a neat appearance */
        border-radius: 10px; /* Add border-radius for rounded corners */
        text-align: center; /* Center the content */
    }

    .post img {
        max-width: 100%; /* Make sure images don't overflow the post container */
        border-radius: 5px; /* Optional: Add border-radius for rounded image corners */
    }

    .comment {
        margin-top: 10px;
    }
</style>

</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
    <a class="navbar-brand" href="index.php">PostJunkyard</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarResponsive"
            aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarResponsive">
        <ul class="navbar-nav ml-auto">
            <!-- Add any additional navigation links here -->
            <li class="nav-item active">
                <a class="nav-link" href="index.php">Home</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="profile.php">Profile</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="submit_post.php">Post</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="logout.php">Logout</a>
            </li>
        </ul>
    </div>
</nav>

<!-- Page Content -->
<div class="container">
    <div class="row">
        <div class="col-lg-12 text-center">
            <h2 class="section-heading text-uppercase">         </h2>
        </div>
    </div>

    <!-- Feed Section -->
<div class="row">
        <div class="col-lg-8 mx-auto">
            <?php
            $query = "SELECT posts.*, users.user_name 
                      FROM posts 
                      JOIN users ON posts.user_id = users.user_id 
                      ORDER BY posts.created_at DESC";
            $result = mysqli_query($con, $query);

            while ($row = mysqli_fetch_assoc($result)) {
                echo '<div class="post">';
                echo '<h4>' . $row['user_name'] . '</h4>';
                echo '<p>' . $row['caption'] . '</p>';

                if (!empty($row['image_path'])) {
                    echo '<img src="' . $row['image_path'] . '" class="img-fluid" alt="Post Image">';
                }

                echo '<p class="text-muted">' . $row['created_at'] . '</p>';
                echo '<p>Likes: <span id="likeCount_' . $row['id'] . '">' . $row['likes_count'] . '</span></p>';

                // Display like button
                echo '<form method="post" action="like_post.php">';
                echo '<input type="hidden" name="post_id" value="' . $row['id'] . '">';
                echo '<button type="submit" class="btn btn-primary">Like</button>';
                echo '</form>';

                // Display comments
                $post_id = $row['id'];
                $comments_query = "SELECT * FROM comments WHERE post_id = $post_id ORDER BY created_at DESC";
                $comments_result = mysqli_query($con, $comments_query);

                while ($comment = mysqli_fetch_assoc($comments_result)) {
                    // Fetch the username associated with the comment
                    $comment_user_id = $comment['user_id'];
                    $username_query = "SELECT user_name FROM users WHERE user_id = '$comment_user_id'";
                    $username_result = mysqli_query($con, $username_query);
                    $username_row = mysqli_fetch_assoc($username_result);
                    
                    // Display the comment with the username
                    echo '<div class="comment">';
                    echo '<p><strong>' . $username_row['user_name'] . '</strong>: ' . $comment['comment_text'] . '</p>';
                    echo '</div>';
                }

                // Add a form for adding comments with responsive sizing
                echo '<form method="post" action="add_comment.php">';
                echo '<div class="form-group row">';
                echo '<div class="col-8">';
                echo '<input type="hidden" name="post_id" value="' . $row['id'] . '">';
                echo '<textarea class="form-control" name="comment_text" rows="2" placeholder="Add a comment"></textarea>';
                echo '</div>';
                echo '<div class="col-4">';
                echo '<button type="submit" name="add_comment" class="btn btn-primary">Add Comment</button>';
                echo '</div>';
                echo '</div>';
                echo '</form>';

                echo '</div>';
            }
            ?>
        </div>
    </div>
</div>

<!-- Bootstrap JS and dependencies -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>
</html>
