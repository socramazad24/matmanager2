<?php
ob_start();
session_start();
require_once "../config/cors.php";
require_once "../config/Database.php";
require_once "../middleware/auth.php";

use API\Config\Database;

header('Content-Type: application/json; charset=utf-8');

$response = [];

try {
    checkRole(['administrador']);

    if ($_SERVER["REQUEST_METHOD"] !== "POST") {
        http_response_code(405);
        $response = ['success' => false, 'message' => 'Método no permitido'];
        echo json_encode($response);
        exit;
    }

    $input = json_decode(file_get_contents('php://input'), true);

    if (empty($input['idProveedor'])) {
        http_response_code(400);
        $response = ['success' => false, 'message' => 'ID del proveedor requerido'];
        echo json_encode($response);
        exit;
    }

    $db = new Database();
    $con = $db->getConnection();

    $stmt = $con->prepare("DELETE FROM proveedores WHERE idProveedor = ?");
    $stmt->bind_param("i", $input['idProveedor']);

    if ($stmt->execute()) {
        if ($stmt->affected_rows > 0) {
            $response = ['success' => true, 'message' => 'Proveedor eliminado exitosamente'];
        } else {
            http_response_code(404);
            $response = ['success' => false, 'message' => 'Proveedor no encontrado'];
        }
    } else {
        http_response_code(500);
        $response = ['success' => false, 'message' => 'Error al eliminar proveedor: ' . $stmt->error];
    }

    $stmt->close();
    $db->close();

} catch (Throwable $e) {
    http_response_code(500);
    $response = ['success' => false, 'message' => 'Error del servidor: ' . $e->getMessage()];
}

$output = ob_get_clean();
if (!empty($output)) {
    $response['debug_output'] = $output;
}

echo json_encode($response, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
