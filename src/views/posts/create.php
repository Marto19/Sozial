<?php
$pageTitle = 'Create Post - PostJunkyard';
$activePage = 'post';
include(__DIR__ . '/../layouts/header.php');
?>

<div class="row">
    <div class="col-lg-12 text-center">
        <h2 class="section-heading text-uppercase">Create Post</h2>
    </div>
</div>

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
            <button type="submit" class="btn btn-primary">Submit</button>
        </form>
    </div>
</div>

<?php include(__DIR__ . '/../layouts/footer.php'); ?>
