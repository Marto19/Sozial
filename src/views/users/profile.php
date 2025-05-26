<?php
$pageTitle = 'Profile - PostJunkyard';
$activePage = 'profile';
include(__DIR__ . '/../layouts/header.php');
?>

<div class="row">
    <div class="col-lg-12 text-center">
        <h2 class="section-heading text-uppercase">Welcome, <?php echo htmlspecialchars($user_data['user_name']); ?>!</h2>
    </div>
</div>

<!-- Profile Picture Section -->
<div class="row mb-4">
    <div class="col-lg-4 mx-auto">
        <div class="card profile-card">
            <img src="<?php echo empty($user_data['profile_pic']) ? DEFAULT_PROFILE_PIC : htmlspecialchars($user_data['profile_pic']); ?>"
                class="card-img-top profile-pic" alt="Profile Picture">
            <div class="card-body">
                <h5 class="card-title"><?php echo htmlspecialchars($user_data['user_name']); ?></h5>
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

<!-- User Posts Section -->
<div class="row mt-5 mb-5">
    <?php foreach ($userPosts as $post): ?>
        <div class="col-lg-4 mb-4">
            <div class="post-container border rounded p-3">
                <div class="post">
                    <p><?php echo htmlspecialchars($post['caption']); ?></p>

                    <?php if (!empty($post['image_path'])): ?>
                        <img src="<?php echo htmlspecialchars($post['image_path']); ?>" class="img-fluid" alt="Post Image">
                    <?php endif; ?>

                    <p class="text-muted"><?php echo htmlspecialchars($post['created_at']); ?></p>

                    <form method="post" action="delete_post.php">
                        <input type="hidden" name="post_id" value="<?php echo $post['id']; ?>">
                        <button type="submit" class="btn btn-danger">Delete</button>
                    </form>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>

<script>
    function toggleChangePasswordForm() {
        var changePasswordForm = document.querySelector('.change-password-form');
        changePasswordForm.style.display = (changePasswordForm.style.display === 'none') ? 'block' : 'none';
    }
</script>

<?php include(__DIR__ . '/../layouts/footer.php'); ?>
