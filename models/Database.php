<?php
class Database {
    private static $instance = null;
    private $conn;
    private $config;

    private function __construct() {
        $this->config = require_once __DIR__ . '/../config/database.php';
        try {
            $dsn = "mysql:host={$this->config['database']['host']};dbname={$this->config['database']['dbname']};charset={$this->config['database']['charset']}";
            $this->conn = new PDO(
                $dsn,
                $this->config['database']['username'],
                $this->config['database']['password'],
                $this->config['database']['options']
            );
        } catch (PDOException $e) {
            throw new Exception("数据库连接失败: " . $e->getMessage());
        }
    }

    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function getConnection() {
        return $this->conn;
    }
} 