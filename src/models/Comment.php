<?php
namespace Models;

class Comment extends Model {
    protected $table = 'comments';

    public function __construct() {
        parent::__construct();
    }

    public function getForPost(int $postId, int $page = 1, int $perPage = 10): array {
        $offset = ($page - 1) * $perPage;
        
        $stmt = $this->db->query(
            "SELECT c.*, u.user_name, u.profile_pic 
             FROM comments c 
             JOIN users u ON c.user_id = u.user_id 
             WHERE c.post_id = ? 
             ORDER BY c.created_at DESC 
             LIMIT ? OFFSET ?",
            [$postId, $perPage, $offset]
        );
        
        return $stmt->fetchAll();
    }

    public function addComment(int $postId, int $userId, string $text): int {
        return $this->create([
            'post_id' => $postId,
            'user_id' => $userId,
            'comment_text' => $text,
            'created_at' => date('Y-m-d H:i:s')
        ]);
    }

    public function deleteComment(int $commentId, int $userId): bool {
        $stmt = $this->db->query(
            "DELETE FROM {$this->table} WHERE comment_id = ? AND user_id = ?",
            [$commentId, $userId]
        );
        return $stmt->rowCount() > 0;
    }

    public function getCommentCount(int $postId): int {
        $stmt = $this->db->query(
            "SELECT COUNT(*) as count FROM {$this->table} WHERE post_id = ?",
            [$postId]
        );
        $result = $stmt->fetch();
        return (int) $result['count'];
    }
}
