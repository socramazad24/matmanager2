<?php
require_once __DIR__ . '/../config/headers.php';
require_once __DIR__ . '/../middleware/auth.php';
require_once __DIR__ . '/../../backend/config/Database.php';
use matmanager\Database;

error_reporting(E_ALL);
ini_set('display_errors', 1);

try {
    // 🔒 Requiere usuario autenticado
    checkAuth();

    if ($_SERVER["REQUEST_METHOD"] !== "GET") {
        http_response_code(405);
        echo json_encode(['success' => false, 'message' => 'Método no permitido']);
        exit;
    }

    $db = new Database();
    $conex = $db->getConnection();

    // ⚙️ Ajusta al nombre real de tu columna
    $query = "SELECT * FROM Proovedores ORDER BY idProveedor DESC";
    $result = $conex->query($query);

    if (!$result) {
        throw new Exception("Error en la consulta SQL: " . $conex->error);
    }

    $providers = [];
    while ($row = $result->fetch_assoc()) {
        $providers[] = $row;
    }

    echo json_encode([
        'success' => true,
        'data' => $providers
    ], JSON_UNESCAPED_UNICODE);

    $conex->close();

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Error del servidor: ' . $e->getMessage()
    ], JSON_UNESCAPED_UNICODE);
}
