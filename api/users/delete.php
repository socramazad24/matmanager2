<?php
ob_start(); // 🔒 Captura cualquier salida inesperada
session_start();
require_once "../config/cors.php";
require_once "../config/Database.php";
require_once "../middleware/auth.php";

use API\Config\Database;

header('Content-Type: application/json; charset=utf-8');

$response = [];

try {
    // ✅ Verificación de sesión y rol
    checkRole(['administrador']);

    // ✅ Cambiamos DELETE → POST (los DELETE pierden el body en PHP)
    if ($_SERVER["REQUEST_METHOD"] !== "POST") {
        http_response_code(405);
        $response = ['success' => false, 'message' => 'Método no permitido'];
        echo json_encode($response);
        exit;
    }

    // 📥 Leer el body JSON
    $input = json_decode(file_get_contents('php://input'), true);

    if (empty($input['idEmployee'])) {
        http_response_code(400);
        $response = ['success' => false, 'message' => 'ID de empleado requerido'];
        echo json_encode($response);
        exit;
    }

    // 🔗 Conexión a la base de datos
    $db = new Database();
    $con = $db->getConnection();

    $stmt = $con->prepare("DELETE FROM users WHERE idEmployee = ?");
    $stmt->bind_param("i", $input['idEmployee']);

    if ($stmt->execute()) {
        if ($stmt->affected_rows > 0) {
            $response = ['success' => true, 'message' => 'Usuario eliminado exitosamente'];
        } else {
            http_response_code(404);
            $response = ['success' => false, 'message' => 'Usuario no encontrado'];
        }
    } else {
        http_response_code(500);
        $response = ['success' => false, 'message' => 'Error al eliminar usuario: ' . $stmt->error];
    }

    $stmt->close();
    $db->close();

} catch (Throwable $e) {
    http_response_code(500);
    $response = [
        'success' => false,
        'message' => 'Error del servidor: ' . $e->getMessage()
    ];
}

// 🧹 Si hubo salida previa, la limpiamos
$output = ob_get_clean();
if (!empty($output)) {
    $response['debug_output'] = $output;
}

// ✅ Devolvemos siempre JSON válido
echo json_encode($response, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);

