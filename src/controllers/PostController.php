<?php
namespace Controllers;

use Utils\Auth;
use Utils\Security;
use Utils\FileUpload;
use Models\Post;
use Models\User;

class PostController {
    private $auth;
    private $post;
    private $user;
    private $fileUpload;

    public function __construct() {
        $this->auth = Auth::getInstance();
        $this->post = new Post();
        $this->user = new User();
        $this->fileUpload = new FileUpload(UPLOAD_DIR);
    }

    public function index() {
        $user_data = $this->auth->getCurrentUser();
        if (!$user_data) {
            header("Location: login.php");
            exit;
        }

        $posts = $this->post->getFeed();
          // Include the index view
        include(__DIR__ . '/../views/posts/index.php');
    }

    public function create() {
        $user_data = $this->auth->getCurrentUser();
        if (!$user_data) {
            header("Location: login.php");
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $caption = Security::sanitizeInput($_POST['caption']);
            
            if (isset($_FILES["image"]) && $_FILES["image"]["error"] == 0) {
                $this->fileUpload->setFile($_FILES["image"]);
                $errors = $this->fileUpload->validate();

                if (empty($errors)) {
                    $target_file = $this->fileUpload->upload();
                    
                    // Resize image if needed
                    $this->fileUpload->resizeImage($target_file, 800, 600);

                    $postData = [
                        'user_id' => $user_data['user_id'],
                        'caption' => $caption,
                        'image_path' => $target_file,
                        'created_at' => date('Y-m-d H:i:s')
                    ];

                    if ($this->post->create($postData)) {
                        header('Location: index.php');
                        exit;
                    }
                } else {
                    foreach ($errors as $error) {
                        echo $error . "<br>";
                    }
                }
            }
        }        // Include the create post view
        include(__DIR__ . '/../views/posts/create.php');
    }

    public function like() {
        $user_data = $this->auth->getCurrentUser();
        if (!$user_data) {
            header("Location: login.php");
            exit;
        }

        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["post_id"])) {
            $post_id = (int)$_POST["post_id"];
            $user_id = $user_data['user_id'];

            if (!$this->post->isLikedByUser($post_id, $user_id)) {
                $this->post->like($post_id, $user_id);
            }
        }

        header('Location: index.php');
        exit;
    }

    public function delete() {
        $user_data = $this->auth->getCurrentUser();
        if (!$user_data) {
            header("Location: login.php");
            exit;
        }

        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["post_id"])) {
            $post_id = (int)$_POST["post_id"];
            $post = $this->post->find($post_id);

            if ($post && $post['user_id'] === $user_data['user_id']) {
                $this->post->delete($post_id);
            }
        }

        header("Location: profile.php");
        exit;
    }
}
