<?php
// lib/db.php
require_once __DIR__ . '/env.php';

function connectDB() {
    static $pdo = null;
    if ($pdo === null) {
        try {
            $pdo = new PDO(
                'mysql:host=' . ENV['DB_HOST'] . ';dbname=' . ENV['DB_NAME'] . ';charset=utf8mb4',
                ENV['DB_USER'],
                ENV['DB_PASS'],
                [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
            );
        } catch (PDOException $e) {
            die('DB Connection failed: ' . $e->getMessage());
        }
    }
    return $pdo;
}