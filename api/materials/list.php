<?php
error_reporting(0);
ini_set('display_errors', 0);

require_once __DIR__ . '/../config/headers.php';
require_once __DIR__ . '/../middleware/auth.php';
require_once __DIR__ . '/../../backend/config/Database.php';
use matmanager\Database;

checkAuth(); // 🔒 Requiere usuario autenticado

if ($_SERVER["REQUEST_METHOD"] !== "GET") {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Método no permitido']);
    exit();
}

$db = new Database();
$conex = $db->getConnection();

$query = "SELECT * FROM Materiales ORDER BY date_reg DESC";
$result = $conex->query($query);

if ($result) {
    $materials = [];
    while ($row = $result->fetch_assoc()) {
        $materials[] = $row;
    }

    echo json_encode([
        'success' => true,
        'data' => $materials
    ]);
} else {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Error al obtener materiales'
    ]);
}

$conex->close();
