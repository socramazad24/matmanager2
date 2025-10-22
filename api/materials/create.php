<?php
ob_start(); // Captura cualquier salida inesperada
session_start();
require_once "../config/cors.php";
require_once "../config/Database.php";
require_once "../middleware/auth.php";

use API\Config\Database;

header('Content-Type: application/json; charset=utf-8');

$response = [];

try {
    checkRole(['administrador', 'gerente']);

    if ($_SERVER["REQUEST_METHOD"] !== "POST") {
        http_response_code(405);
        echo json_encode(['success' => false, 'message' => 'Método no permitido']);
        exit;
    }

    $input = json_decode(file_get_contents('php://input'), true);

    // ✅ Validar campos requeridos
    $required = ['idMaterial', 'MaterialName', 'Description', 'costoUnitario', 'cantidadMaterial', 'idProveedor'];
    foreach ($required as $field) {
        if (!isset($input[$field]) || $input[$field] === '') {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => "Campo requerido o vacío: $field"]);
            exit;
        }
    }

    // 🔗 Conexión a la base de datos
    $db = new Database();
    $con = $db->getConnection();

    // 🚫 Verificar duplicados
    $check = $con->prepare("SELECT 1 FROM materiales WHERE idMaterial = ?");
    $check->bind_param("s", $input['idMaterial']);
    $check->execute();
    $check->store_result();

    if ($check->num_rows > 0) {
        http_response_code(409);
        $response = ['success' => false, 'message' => 'El ID de material ya existe'];
        echo json_encode($response);
        exit;
    }
    $check->close();

    // 🧩 Preparar inserción
    $stmt = $con->prepare("
        INSERT INTO materiales (
            idMaterial, MaterialName, Description, costoUnitario,
            cantidadMaterial, idProveedor, idPedido, date_reg
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?)
    ");

    $stmt->bind_param(
        "sssdiiss",
        $input['idMaterial'],
        $input['MaterialName'],
        $input['Description'],
        $input['costoUnitario'],
        $input['cantidadMaterial'],
        $input['idProveedor'],
        $input['idPedido'],
        $input['date_reg']
    );

    if ($stmt->execute()) {
        $response = [
            'success' => true,
            'message' => 'Material creado exitosamente',
            'data' => ['idMaterial' => $input['idMaterial']]
        ];
    } else {
        http_response_code(500);
        $response = ['success' => false, 'message' => 'Error al crear material: ' . $stmt->error];
    }

    $stmt->close();
    $db->close();

} catch (Throwable $e) {
    http_response_code(500);
    $response = ['success' => false, 'message' => 'Error del servidor: ' . $e->getMessage()];
}

// 🧹 Limpiar salida accidental
$output = ob_get_clean();
if (!empty($output)) {
    $response['debug_output'] = $output;
}

// ✅ Respuesta final
echo json_encode($response, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);

