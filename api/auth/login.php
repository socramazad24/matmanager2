<?php
require_once __DIR__ . '/../config/headers.php';
require_once __DIR__ . '/../../backend/config/Database.php';
use matmanager\Database;

$db = new Database();
$con = $db->getConnection();

$input = json_decode(file_get_contents('php://input'), true);
$username = $input['username'] ?? '';
$password = $input['password'] ?? '';

$query = "SELECT * FROM users WHERE username = ? AND password = ?";
$stmt = $con->prepare($query);
$stmt->bind_param('ss', $username, $password);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
    $user = $result->fetch_assoc();

    $_SESSION['user'] = [
        'username' => $user['username'],
        'role' => $user['role']
    ];

    echo json_encode([
        'status' => 'success',
        'message' => 'Inicio de sesión exitoso',
        'user' => $_SESSION['user'],
        'session_id' => session_id()
    ]);
} else {
    http_response_code(401);
    echo json_encode([
        'status' => 'error',
        'message' => 'Credenciales inválidas'
    ]);
}
