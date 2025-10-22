<?php
require_once __DIR__ . '/../config/headers.php';
require_once __DIR__ . '/../middleware/auth.php';
require_once __DIR__ . '/../../backend/config/Database.php';
use matmanager\Database;

error_reporting(E_ALL);
ini_set('display_errors', 1);

try {
    // 🔒 Verifica sesión
    checkAuth();

    if ($_SERVER["REQUEST_METHOD"] !== "GET") {
        http_response_code(405);
        echo json_encode(['success' => false, 'message' => 'Método no permitido']);
        exit;
    }

    $db = new Database();
    $conex = $db->getConnection();

    // ⚙️ Usa una columna que sí exista en tu tabla (ajústala si es necesario)
    $query = "SELECT * FROM Pedidos ORDER BY idPedido DESC";
    $result = $conex->query($query);

    if (!$result) {
        throw new Exception("Error en la consulta SQL: " . $conex->error);
    }

    $orders = [];
    while ($row = $result->fetch_assoc()) {
        $orders[] = $row;
    }

    echo json_encode([
        'success' => true,
        'data' => $orders
    ], JSON_UNESCAPED_UNICODE);

    $conex->close();

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Error del servidor: ' . $e->getMessage()
    ], JSON_UNESCAPED_UNICODE);
}
