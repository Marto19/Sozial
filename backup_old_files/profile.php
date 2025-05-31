<?php
session_start();
include("connection.php");
include("functions.php");

// Default profile picture path
define('DEFAULT_PROFILE_PIC', 'uploads/profile_pics/default_ image.png');


// Check if the user is logged in
$user_data = check_login($con);

// Check if the form for updating the password is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["new_password"])) {
    $new_password = $_POST["new_password"];

    // Hash the new password before updating the database
    $hashed_password = password_hash($new_password, PASSWORD_BCRYPT);

    $update_password_query = "UPDATE users SET password = '$hashed_password' WHERE id = " . $user_data['id'];
    mysqli_query($con, $update_password_query);
}

// Check if the form for updating the profile picture is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES["profile_picture"])) {
    $target_dir = "uploads/profile_pics/";
    $target_file = $target_dir . basename($_FILES["profile_picture"]["name"]);
    move_uploaded_file($_FILES["profile_picture"]["tmp_name"], $target_file);

    $update_profile_pic_query = "UPDATE users SET profile_pic = '$target_file' WHERE id = " . $user_data['id'];
    mysqli_query($con, $update_profile_pic_query);

    // Update the user_data to reflect the new profile picture
    $user_data['profile_pic'] = $target_file;
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Your Social Media Site - Profile</title>

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        /* Add your custom styles here */
        body {
            padding-top: 56px;
        }

        .profile-card {
            max-width: 300px;
        }

        .profile-pic {
            max-width: 100%;
            border-radius: 50%;
        }

        .post img {
            max-width: 100%;
            height: auto;
        }

        .change-password-form {
            display: none; /* Initially hide the change password form */
        }
    </style>
</head>

<body>

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
                <li class="nav-item active">
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
                <h2 class="section-heading text-uppercase">Welcome, <?php echo $user_data['user_name']; ?>!</h2>
            </div>
        </div>

        <!-- Profile Picture Section -->
<div class="row mb-4">
    <div class="col-lg-4 mx-auto">
        <div class="card profile-card">
            <img src="<?php echo (empty($user_data['profile_pic']) ? DEFAULT_PROFILE_PIC : $user_data['profile_pic']); ?>"
                class="card-img-top profile-pic" alt="Profile Picture">
            <div class="card-body">
                <h5 class="card-title"><?php echo $user_data['user_name']; ?></h5>
                <form method="post" action="profile.php" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="profile_picture">Change Profile Picture:</label>
                        <input type="file" class="form-control-file" name="profile_picture">
                    </div>
                    <button type="submit" class="btn btn-primary">Update Profile Picture</button>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- TODO:FIX DEFAULT IMAGE -->

        <!-- Change Password Section -->
        <div class="row">
            <div class="col-lg-6 mx-auto">
                <button class="btn btn-primary" onclick="toggleChangePasswordForm()">Change Password</button>
                <form method="post" action="profile.php" class="change-password-form">
                    <div class="form-group">
                        <label for="new_password">New Password:</label>
                        <input type="password" class="form-control" name="new_password" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Update Password</button>
                </form>
            </div>
        </div>

<!-- View Photos and Edit Captions Section -->
<div class="row mt-5 mb-5">
    <?php
    // Fetch posts from the database
    $user_id = $user_data['user_id'];
    $posts_query = "SELECT posts.*, users.user_id as post_user_id, users.user_name, users.profile_pic
                    FROM posts
                    JOIN users ON posts.user_id = users.user_id
                    WHERE posts.user_id = $user_id
                    ORDER BY posts.created_at DESC";
    $posts_result = mysqli_query($con, $posts_query);

    // Display posts
    while ($post_row = mysqli_fetch_assoc($posts_result)) {
        echo '<div class="col-lg-4 mb-4">';
        echo '<div class="post-container border rounded p-3">';
        echo '<div class="post">';
        echo '<p>' . $post_row['caption'] . '</p>';

        // Display the image if available
        if (!empty($post_row['image_path'])) {
            echo '<img src="' . $post_row['image_path'] . '" class="img-fluid" alt="Post Image">';
        }

        echo '<p class="text-muted">' . $post_row['created_at'] . '</p>';

        // Add a form for deleting the post
        echo '<form method="post" action="delete_post.php">';
        echo '<input type="hidden" name="post_id" value="' . $post_row['id'] . '">';
        echo '<button type="submit" class="btn btn-danger">Delete</button>';
        echo '</form>';

        echo '</div>';
        echo '</div>';
        echo '</div>';
    }
    ?>
</div>


    </div>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <script>
        function toggleChangePasswordForm() {
            var changePasswordForm = document.querySelector('.change-password-form');
            changePasswordForm.style.display = (changePasswordForm.style.display === 'none') ? 'block' : 'none';
        }
    </script>
</body>

</html>
