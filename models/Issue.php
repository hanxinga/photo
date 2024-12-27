<?php
require_once 'Database.php';

class Issue {
    private $db;
    private $config;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
        $this->config = require_once __DIR__ . '/../config/database.php';
    }

    public function createIssue($userId, $description, $imageFile) {
        try {
            // 处理图片上传
            $imagePath = $this->handleImageUpload($imageFile);
            
            $stmt = $this->db->prepare("
                INSERT INTO issues (user_id, description, image_path, status)
                VALUES (:user_id, :description, :image_path, '1')
            ");

            $stmt->execute([
                ':user_id' => $userId,
                ':description' => $description,
                ':image_path' => $imagePath
            ]);

            return $this->db->lastInsertId();
        } catch (PDOException $e) {
            throw new Exception("问题创建失败: " . $e->getMessage());
        }
    }

    private function handleImageUpload($imageFile) {
        $uploadDir = $_SERVER['DOCUMENT_ROOT'] . $this->config['upload']['image_path'];
        $fileName = uniqid() . '_' . $imageFile['name'];
        $targetPath = $uploadDir . $fileName;

        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        if ($imageFile['size'] > $this->config['upload']['max_size']) {
            throw new Exception("图片大小超出限制");
        }

        if (!in_array($imageFile['type'], $this->config['upload']['allowed_types'])) {
            throw new Exception("不支持的图片格式");
        }

        if (move_uploaded_file($imageFile['tmp_name'], $targetPath)) {
            return $this->config['upload']['image_path'] . $fileName;
        }

        throw new Exception("图片上传失败");
    }

    public function getIssuesByUserId($userId) {
        $stmt = $this->db->prepare("
            SELECT i.*, f.content as feedback_content 
            FROM issues i 
            LEFT JOIN feedbacks f ON i.issue_id = f.issue_id 
            WHERE i.user_id = :user_id 
            ORDER BY i.created_at DESC
        ");
        $stmt->execute([':user_id' => $userId]);
        return $stmt->fetchAll();
    }
} 