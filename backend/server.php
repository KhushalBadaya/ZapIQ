<?php

// Get the request URI and method
$requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$requestMethod = $_SERVER['REQUEST_METHOD'];

// Simple router function
function route($method, $path, $callback) {
    global $requestUri, $requestMethod;

    if ($requestMethod === $method && $requestUri === $path) {
        $callback();
        exit;
    }
}

// Set response to JSON/plain text helper
function send($message) {
    echo $message;
}

// ─── Routes ───────────────────────────────────────────────

route('GET', '/api/auth/signup', function() {
    send("SignUp endpoint");
});

route('GET', '/api/auth/login', function() {
    send("login endpoint");
});

route('GET', '/api/auth/logout', function() {
    send("logout endpoint");
});

// ─── 404 Fallback ─────────────────────────────────────────
http_response_code(404);
send("404 Not Found");