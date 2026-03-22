// routes for login signup and alll 
<?php
// routes/auth.routes.php
require_once __DIR__ . '/../controller/auth.controller.php';

$method = $_SERVER['REQUEST_METHOD'];
$path   = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

switch ($path) {
    case '/api/auth/signup':
        if ($method === 'POST') signup();
        break;

    // case '/api/auth/login':
    //     if ($method === 'POST') login();
    //     break;

    // case '/api/auth/logout':
    //     if ($method === 'POST') logout();
    //     break;

    default:
        http_response_code(404);
        echo json_encode(['message' => 'Route not found']);
        break;
}