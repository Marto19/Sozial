<?php
namespace Controllers;

use Utils\Auth;
use Utils\Security;
use Models\Comment;

class CommentController {
    private $auth;
    private $comment;

    public function __construct() {
        $this->auth = Auth::getInstance();
        $this->comment = new Comment();
    }

    public function add() {
        $user_data = $this->auth->getCurrentUser();
        if (!$user_data) {
            header("Location: login.php");
            exit;
        }

        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["add_comment"])) {
            $post_id = (int)$_POST["post_id"];
            $comment_text = Security::sanitizeInput($_POST["comment_text"]);
            $user_id = $user_data['user_id'];

            if ($this->comment->addComment($post_id, $user_id, $comment_text)) {
                header("Location: index.php");
                exit;
            } else {
                echo "Error adding comment";
            }
        }
        
        header("Location: index.php");
        exit;
    }

    public function delete() {
        $user_data = $this->auth->getCurrentUser();
        if (!$user_data) {
            header("Location: login.php");
            exit;
        }

        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["delete_comment"])) {
            $comment_id = (int)$_POST["comment_id"];
            $this->comment->deleteComment($comment_id, $user_data['user_id']);
        }

        header("Location: index.php");
        exit;
    }
}
