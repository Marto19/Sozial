<?php
$pageTitle = 'Feed - PostJunkyard';
$activePage = 'home';
include(__DIR__ . '/../layouts/header.php');
?>

<div class="row">
    <div class="col-lg-8 mx-auto">
        <?php foreach ($posts as $row): ?>
            <div class="post">
                <h4><?php echo htmlspecialchars($row['user_name']); ?></h4>
                <p><?php echo htmlspecialchars($row['caption']); ?></p>

                <?php if (!empty($row['image_path'])): ?>
                    <img src="<?php echo htmlspecialchars($row['image_path']); ?>" class="img-fluid" alt="Post Image">
                <?php endif; ?>

                <p class="text-muted"><?php echo htmlspecialchars($row['created_at']); ?></p>
                <p>Likes: <span id="likeCount_<?php echo $row['id']; ?>"><?php echo $row['likes_count']; ?></span></p>

                <form method="post" action="like_post.php">
                    <input type="hidden" name="post_id" value="<?php echo $row['id']; ?>">
                    <button type="submit" class="btn btn-primary">Like</button>
                </form>

                <?php 
                $comments = $commentModel->getForPost($row['id']); 
                foreach ($comments as $comment): 
                ?>
                    <div class="comment">
                        <p><strong><?php echo htmlspecialchars($comment['user_name']); ?></strong>: 
                           <?php echo htmlspecialchars($comment['comment_text']); ?></p>
                    </div>
                <?php endforeach; ?>

                <form method="post" action="add_comment.php">
                    <div class="form-group row">
                        <div class="col-8">
                            <input type="hidden" name="post_id" value="<?php echo $row['id']; ?>">
                            <textarea class="form-control" name="comment_text" rows="2" placeholder="Add a comment"></textarea>
                        </div>
                        <div class="col-4">
                            <button type="submit" name="add_comment" class="btn btn-primary">Add Comment</button>
                        </div>
                    </div>
                </form>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<?php include(__DIR__ . '/../layouts/footer.php'); ?>
