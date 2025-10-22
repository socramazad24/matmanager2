<?php
require_once __DIR__ . '/../config/headers.php';
require_once __DIR__ . '/../middleware/auth.php';
require_once __DIR__ . '/../../backend/config/Database.php';
use matmanager\Database;

error_reporting(E_ALL);
ini_set('display_errors', 1);

try {
    // 🔒 Solo administradores pueden listar usuarios
    checkRole(['administrador']);

    if ($_SERVER["REQUEST_METHOD"] !== "GET") {
        http_response_code(405);
        echo json_encode(['success' => false, 'message' => 'Método no permitido']);
        exit;
    }

    $db = new Database();
    $conex = $db->getConnection();

    // ✅ Campos reales según tu base de datos
    $query = "SELECT idEmployee, firstName, lastName, username, email, phone, role, date_reg FROM users ORDER BY idEmployee DESC";
    $result = $conex->query($query);

    if (!$result) {
        throw new Exception("Error en la consulta SQL: " . $conex->error);
    }

    $users = [];
    while ($row = $result->fetch_assoc()) {
        $users[] = $row;
    }

    echo json_encode([
        'success' => true,
        'data' => $users
    ], JSON_UNESCAPED_UNICODE);

    $conex->close();

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Error del servidor: ' . $e->getMessage()
    ], JSON_UNESCAPED_UNICODE);
}
