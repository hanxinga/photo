<?php
require_once 'Database.php';

class Feedback {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function createFeedback($issueId, $adminId, $content) {
        try {
            $this->db->beginTransaction();

            // 创建反馈
            $stmt = $this->db->prepare("
                INSERT INTO feedbacks (issue_id, admin_id, content)
                VALUES (:issue_id, :admin_id, :content)
            ");

            $stmt->execute([
                ':issue_id' => $issueId,
                ':admin_id' => $adminId,
                ':content' => $content
            ]);

            // 更新问题状态
            $stmt = $this->db->prepare("
                UPDATE issues 
                SET status = '3' 
                WHERE issue_id = :issue_id
            ");

            $stmt->execute([':issue_id' => $issueId]);

            $this->db->commit();
            return true;
        } catch (PDOException $e) {
            $this->db->rollBack();
            throw new Exception("反馈创建失败: " . $e->getMessage());
        }
    }

    public function getFeedbacksByIssueId($issueId) {
        $stmt = $this->db->prepare("
            SELECT f.*, u.name as admin_name 
            FROM feedbacks f 
            JOIN users u ON f.admin_id = u.user_id 
            WHERE f.issue_id = :issue_id 
            ORDER BY f.created_at DESC
        ");
        $stmt->execute([':issue_id' => $issueId]);
        return $stmt->fetchAll();
    }
} 