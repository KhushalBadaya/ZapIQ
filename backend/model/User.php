<?php
// models/User.php
require_once __DIR__ . '/../lib/db.php';

class User {

    public static function create($data) {
        $pdo = connectDB();

        // Validate
        if (empty($data['email']) || empty($data['fullName']) || empty($data['password'])) {
            throw new Exception('All fields are required');
        }
        if (strlen($data['password']) < 6) {
            throw new Exception('Password must be at least 6 characters');
        }

        $stmt = $pdo->prepare('
            INSERT INTO users (email, fullName, password, profilePic, createdAt, updatedAt)
            VALUES (:email, :fullName, :password, :profilePic, NOW(), NOW())
        ');

        $stmt->execute([
            ':email'      => $data['email'],
            ':fullName'   => $data['fullName'],
            ':password'   => $data['password'],
            ':profilePic' => $data['profilePic'] ?? '',
        ]);

        return self::findById($pdo->lastInsertId());
    }

    public static function findOne($filter) {
        $pdo = connectDB();

        $field = array_key_first($filter);
        $value = $filter[$field];

        $stmt = $pdo->prepare("SELECT * FROM users WHERE $field = :value LIMIT 1");
        $stmt->execute([':value' => $value]);
        $user = $stmt->fetch(PDO::FETCH_OBJ);
        return $user ?: null;
    }

    public static function findById($id, $exclude = []) {
        $pdo = connectDB();

        $columns = '*';
        if (!empty($exclude)) {
            $allColumns = ['id', 'email', 'fullName', 'password', 'profilePic', 'createdAt', 'updatedAt'];
            $excluded   = array_map(fn($f) => ltrim($f, '-'), $exclude);
            $selected   = array_diff($allColumns, $excluded);
            $columns    = implode(', ', $selected);
        }

        $stmt = $pdo->prepare("SELECT $columns FROM users WHERE id = :id LIMIT 1");
        $stmt->execute([':id' => $id]);
        $user = $stmt->fetch(PDO::FETCH_OBJ);
        return $user ?: null;
    }

    public static function updateById($id, $data) {
        $pdo = connectDB();

        $fields = implode(', ', array_map(fn($k) => "$k = :$k", array_keys($data)));
        $data['id'] = $id;

        $stmt = $pdo->prepare("UPDATE users SET $fields, updatedAt = NOW() WHERE id = :id");
        $stmt->execute($data);
        return self::findById($id);
    }
}