<?php
// index.php
require_once __DIR__ . '/lib/env.php';
require_once __DIR__ . '/lib/db.php';

// Middleware - parse JSON body
$rawBody = file_get_contents('php://input');
$GLOBALS['body'] = json_decode($rawBody, true) ?? [];

// Headers (equivalent to express.json() + CORS)
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

// Handle preflight
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

// Connect to DB
connectDB();

// Routes
$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

if (str_starts_with($path, '/api/auth')) {
    require_once __DIR__ . '/routes/auth.routes.php';
} else {
    http_response_code(404);
    echo json_encode(['message' => 'Route not found']);
}