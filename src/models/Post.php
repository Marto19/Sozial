<?php
namespace Models;

class Post extends Model {
    protected $table = 'posts';

    public function __construct() {
        parent::__construct();
    }

    public function getFeed(int $page = 1, int $perPage = 10): array {
        $offset = ($page - 1) * $perPage;
        
        $stmt = $this->db->query(
            "SELECT p.*, u.user_name, u.profile_pic,
                    (SELECT COUNT(*) FROM post_likes WHERE post_id = p.id) as likes_count,
                    (SELECT COUNT(*) FROM comments WHERE post_id = p.id) as comments_count
             FROM posts p 
             JOIN users u ON p.user_id = u.user_id 
             ORDER BY p.created_at DESC 
             LIMIT ? OFFSET ?",
            [$perPage, $offset]
        );
        
        return $stmt->fetchAll();
    }

    public function getWithDetails(int $postId): ?array {
        $stmt = $this->db->query(
            "SELECT p.*, u.user_name, u.profile_pic,
                    (SELECT COUNT(*) FROM post_likes WHERE post_id = p.id) as likes_count,
                    (SELECT COUNT(*) FROM comments WHERE post_id = p.id) as comments_count
             FROM posts p 
             JOIN users u ON p.user_id = u.user_id 
             WHERE p.id = ?",
            [$postId]
        );
        
        return $stmt->fetch() ?: null;
    }

    public function like(int $postId, int $userId): bool {
        $stmt = $this->db->query(
            "INSERT IGNORE INTO post_likes (post_id, user_id) VALUES (?, ?)",
            [$postId, $userId]
        );
        return $stmt->rowCount() > 0;
    }

    public function unlike(int $postId, int $userId): bool {
        $stmt = $this->db->query(
            "DELETE FROM post_likes WHERE post_id = ? AND user_id = ?",
            [$postId, $userId]
        );
        return $stmt->rowCount() > 0;
    }

    public function isLikedByUser(int $postId, int $userId): bool {
        $stmt = $this->db->query(
            "SELECT 1 FROM post_likes WHERE post_id = ? AND user_id = ?",
            [$postId, $userId]
        );
        return (bool) $stmt->fetch();
    }

    public function getLikeCount(int $postId): int {
        $stmt = $this->db->query(
            "SELECT COUNT(*) as count FROM post_likes WHERE post_id = ?",
            [$postId]
        );
        $result = $stmt->fetch();
        return (int) $result['count'];
    }
}
