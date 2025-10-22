<?php
require_once __DIR__ . '/../config/headers.php';

header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: http://localhost:3000');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');
header('Access-Control-Allow-Credentials: true');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

if (isset($_SESSION['user'])) {
    echo json_encode([
        "status" => "success",
        "user" => $_SESSION['user']
    ]);
} else {
    http_response_code(401);
    echo json_encode([
        "status" => "error",
        "message" => "No hay sesión activa"
    ]);
}
