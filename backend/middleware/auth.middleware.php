<?php
// middleware/protectedRoute.php
require_once __DIR__ . '/../lib/env.php';
require_once __DIR__ . '/../models/User.php';
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

function protectedRoute() {
    try {
        $token = $_COOKIE['jwt'] ?? null;

        if (!$token) {
            http_response_code(401);
            echo json_encode(['message' => 'Unauthorized - no token provided']);
            exit;
        }

        $decoded = JWT::decode($token, new Key(ENV['JWT_SECRET'], 'HS256'));

        if (!$decoded) {
            http_response_code(401);
            echo json_encode(['message' => 'Unauthorized - invalid token']);
            exit;
        }

        $user = User::findById($decoded->userId, ['-password']);
        if (!$user) {
            http_response_code(404);
            echo json_encode(['message' => 'User not found']);
            exit;
        }

        // Attach user to request context
        $GLOBALS['authUser'] = $user;

    } catch (Exception $e) {
        error_log('Error in protectedRoute middleware: ' . $e->getMessage());
        http_response_code(500);
        echo json_encode(['message' => 'Internal server error']);
        exit;
    }
}