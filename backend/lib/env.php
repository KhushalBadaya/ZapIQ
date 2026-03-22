<?php
// env.php
$dotenv = parse_ini_file(__DIR__ . '/../.env');

define('ENV', [
    'PORT'         => $dotenv['PORT']         ?? null,
    'MONGO_URI'    => $dotenv['MONGO_URI']    ?? null,
    'NODE_ENV'     => $dotenv['NODE_ENV']     ?? null,
    'MONGODB_PASS' => $dotenv['MONGODB_PASS'] ?? null,
    'JWT_SECRET'   => $dotenv['JWT_SECRET']   ?? null,
]);