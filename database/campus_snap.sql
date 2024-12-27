-- 创建数据库
CREATE DATABASE IF NOT EXISTS campus_snap DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

USE campus_snap;

-- 用户表
CREATE TABLE users (
    user_id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(20) NOT NULL COMMENT '用户姓名',
    role ENUM('1', '2') NOT NULL DEFAULT '1' COMMENT '用户角色：1师生，2管理员',
    phone VARCHAR(20) COMMENT '用户手机号',
    password VARCHAR(100) COMMENT '用户登录密码',
    wechat_id VARCHAR(50) UNIQUE COMMENT '微信ID',
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_wechat_id (wechat_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='用户表';

-- 问题表
CREATE TABLE issues (
    issue_id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL COMMENT '上传者ID',
    description TEXT NOT NULL COMMENT '问题描述',
    image_path VARCHAR(255) COMMENT '问题图片存储路径',
    status ENUM('1', '2', '3') NOT NULL DEFAULT '1' COMMENT '问题状态：1未处理，2处理中，3已处理',
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(user_id),
    INDEX idx_status (status),
    INDEX idx_user_id (user_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='问题表';

-- 反馈表
CREATE TABLE feedbacks (
    feedback_id INT PRIMARY KEY AUTO_INCREMENT,
    issue_id INT NOT NULL COMMENT '关联问题ID',
    admin_id INT NOT NULL COMMENT '管理员ID',
    content TEXT NOT NULL COMMENT '反馈内容',
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (issue_id) REFERENCES issues(issue_id),
    FOREIGN KEY (admin_id) REFERENCES users(user_id),
    INDEX idx_issue_id (issue_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='反馈表'; 