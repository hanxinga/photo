<?php
return [
    'database' => [
        'host' => 'localhost',
        'dbname' => 'campus_snap',
        'username' => 'root',
        'password' => '',
        'charset' => 'utf8mb4',
        'options' => [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ]
    ],
    'upload' => [
        'image_path' => '/uploads/images/',
        'max_size' => 5242880,  // 5MB
        'allowed_types' => ['image/jpeg', 'image/png', 'image/gif'],
        'max_width' => 2048,
        'max_height' => 2048
    ]
]; 