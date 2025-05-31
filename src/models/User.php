<?php
namespace Models;

use Utils\Security;

class User extends Model {
    protected $table = 'users';
    protected $primaryKey = 'user_id';

    public function __construct() {
        parent::__construct();
    }

    public function createUser(array $data): int {
        if (isset($data['password'])) {
            $data['password'] = Security::hashPassword($data['password']);
        }
        return $this->create($data);
    }

    public function findByUsername(string $username): ?array {
        $stmt = $this->db->query(
            "SELECT * FROM {$this->table} WHERE user_name = ? LIMIT 1",
            [$username]
        );
        return $stmt->fetch() ?: null;
    }

    public function updateProfile(int $userId, array $data): bool {
        if (isset($data['password'])) {
            $data['password'] = Security::hashPassword($data['password']);
        }
        return $this->update($userId, $data);
    }

    public function getUserPosts(int $userId, int $page = 1, int $perPage = 10): array {
        $offset = ($page - 1) * $perPage;
        
        $stmt = $this->db->query(
            "SELECT p.*, u.user_name, u.profile_pic 
             FROM posts p 
             JOIN users u ON p.user_id = u.user_id 
             WHERE p.user_id = ? 
             ORDER BY p.created_at DESC 
             LIMIT ? OFFSET ?",
            [$userId, $perPage, $offset]
        );
        
        return $stmt->fetchAll();
    }

    public function updateProfilePicture(int $userId, string $picturePath): bool {
        return $this->update($userId, ['profile_pic' => $picturePath]);
    }
}
