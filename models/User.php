<?php
require_once 'Database.php';

class User {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function createOrUpdateUser($wechatData) {
        try {
            $stmt = $this->db->prepare("
                INSERT INTO users (name, wechat_id) 
                VALUES (:name, :wechat_id)
                ON DUPLICATE KEY UPDATE 
                name = :update_name
            ");

            $stmt->execute([
                ':name' => $wechatData['nickName'],
                ':wechat_id' => $wechatData['openId'],
                ':update_name' => $wechatData['nickName']
            ]);

            return $this->db->lastInsertId() ?: $this->getUserByWechatId($wechatData['openId'])['user_id'];
        } catch (PDOException $e) {
            throw new Exception("用户创建失败: " . $e->getMessage());
        }
    }

    public function getUserByWechatId($wechatId) {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE wechat_id = :wechat_id");
        $stmt->execute([':wechat_id' => $wechatId]);
        return $stmt->fetch();
    }
} 