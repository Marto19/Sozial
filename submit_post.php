<?php
session_start();
include("connection.php");
include("functions.php");

// Check if the user is logged in
$user_data = check_login($con);

// Process the form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $caption = $_POST['caption'];
    // You may want to add validation and security measures here

    // Handle file upload
    $target_dir = "uploads/";
    $target_file = $target_dir . basename($_FILES["image"]["name"]);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Check if image file is a actual image or fake image
    $check = getimagesize($_FILES["image"]["tmp_name"]);
    if ($check !== false) {
        $uploadOk = 1;
    } else {
        $uploadOk = 0;
        echo "File is not an image.";
    }

    // // Check if file already exists
    // if (file_exists($target_file)) {
    //     $uploadOk = 0;
    //     echo "Sorry, file already exists.";
    // }

    // Check file size
    if ($_FILES["image"]["size"] > 900000) {
        $uploadOk = 0;
        echo "Sorry, your file is too large.";
    }

    // Allow certain file formats
    if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
        && $imageFileType != "gif") {
        $uploadOk = 0;
        echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
    }

    // If everything is ok, try to upload file
    if ($uploadOk == 1) {
        if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
            // Resize the image to a specific dimension
            $resized_image = resizeImage($target_file, 800, 600); // Change dimensions as needed

            // Insert the post into the 'posts' table
            $user_id = $user_data['user_id'];
            $query = "INSERT INTO posts (user_id, caption, image_path, created_at) 
                    VALUES ('$user_id', '$caption', '$target_file', NOW())";
            if (mysqli_query($con, $query)) {
                // Redirect to the feed page after submitting the post
                header('Location: index.php');
                exit();
            } else {
                echo "Error inserting into the database: " . mysqli_error($con);
            }
        } else {
            echo "Error moving file to target directory. Upload error code: " . $_FILES["image"]["error"];
        }
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Post</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>

<!-- Navbar (same as before) -->
<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
        <a class="navbar-brand" href="#">PostJunkyard</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarResponsive"
            aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarResponsive">
            <ul class="navbar-nav ml-auto">
                <<!-- Add any additional navigation links here -->
                <li class="nav-item">
                    <a class="nav-link" href="index.php">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="profile.php">Profile</a>
                </li>
                <li class="nav-item active">
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
            <h2 class="section-heading text-uppercase">Post</h2>
        </div>
    </div>

    <!-- Post Submission Form with Photo -->
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <form method="post" action="submit_post.php" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="caption">Caption:</label>
                    <textarea class="form-control" id="caption" name="caption" rows="3" required></textarea>
                </div>
                <div class="form-group">
                    <label for="image">Upload Photo:</label>
                    <input type="file" class="form-control-file" id="image" name="image" accept="image/*" required>
                </div>
                <button type="submit" class="btn btn-primary" formaction="submit_post.php">Submit</button>
            </form>
        </div>
    </div>
</div>

<!-- Bootstrap JS and dependencies -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>
</html>
