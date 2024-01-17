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
    <title>Your Social Media Site</title>
    
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
</style>

</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
    <a class="navbar-brand" href="#">Your Social Media</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarResponsive"
            aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarResponsive">
        <ul class="navbar-nav ml-auto">
            <!-- Add any additional navigation links here -->
            <li class="nav-item active">
                <a class="nav-link" href="#">Home</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#">Profile</a>
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
            <h2 class="section-heading text-uppercase">Shitpost freely, this is for us...</h2>
        </div>
    </div>

    <!-- Feed Section -->
<div class="row">
    <div class="col-lg-8 mx-auto">
        <?php
        // Fetch posts from the database (assuming you have a 'posts' table)
        $query = "SELECT posts.*, users.user_name 
                  FROM posts 
                  JOIN users ON posts.user_id = users.user_id 
                  ORDER BY posts.created_at DESC";
        $result = mysqli_query($con, $query);

        // Display posts
        while ($row = mysqli_fetch_assoc($result)) {
          echo '<div class="post">';
          echo '<h4>' . $row['user_name'] . '</h4>';
          echo '<p>' . $row['caption'] . '</p>';
          // Display the image if available
          if (!empty($row['image_path'])) {
              echo '<img src="' . $row['image_path'] . '" class="img-fluid" alt="Post Image">';
          }
          echo '<p class="text-muted">' . $row['created_at'] . '</p>';
          echo '<p>Likes: ' . $row['likes_count'] . '</p>'; // Display the like count
          // Add a form for liking a post
          echo '<form method="post" action="like_post.php">';
          echo '<input type="hidden" name="post_id" value="' . $row['id'] . '">';
          echo '<button type="submit" class="btn btn-primary">Like</button>';
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
