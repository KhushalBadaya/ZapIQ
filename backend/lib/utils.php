// code for the cokkies generation 
<?php
// lib/utils.php
require_once __DIR__ . '/env.php';
use Firebase\JWT\JWT;

function generateToken($userId, $res) {
    $secret  = ENV['JWT_SECRET'];
    $payload = [
        'userId' => $userId,
        'exp'    => time() + (7 * 24 * 60 * 60), // 7 days
        'iat'    => time()
    ];

    $token = JWT::encode($payload, $secret, 'HS256');

    $isSecure = ENV['NODE_ENV'] !== 'development';

    setcookie('jwt', $token, [
        'expires'  => time() + (7 * 24 * 60 * 60),
        'httponly' => true,
        'samesite' => 'None',
        'secure'   => $isSecure,
        'path'     => '/',
    ]);

    return $token;
}