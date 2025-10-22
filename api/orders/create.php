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
        if (empty($input[$field])) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => "Campo requerido: $field"]);
            exit;
        }
    }

    $db = new Database();
    $con = $db->getConnection();

    // ✅ Incluir idMaterial en el INSERT
    $stmt = $con->prepare("
        INSERT INTO materiales (
            idMaterial, MaterialName, Description, costoUnitario, cantidadMaterial, idProveedor, idPedido, date_reg
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?)
    ");

    $stmt->bind_param(
        "sssdiiss",
        $input['idMaterial'],           // VARCHAR
        $input['MaterialName'],         // VARCHAR
        $input['Description'],          // VARCHAR
        $input['costoUnitario'],        // FLOAT o DECIMAL
        $input['cantidadMaterial'],     // INT
        $input['idProveedor'],          // VARCHAR o INT
        $input['idPedido'],             // VARCHAR o NULL
        $input['date_reg']              // DATE o DATETIME
    );

    if ($stmt->execute()) {
        $response = [
            'success' => true,
            'message' => 'Material creado exitosamente',
            'data' => ['idMaterial' => $input['idMaterial']]
        ];
    } else {
        http_response_code(500);
        $response = [
            'success' => false,
            'message' => 'Error al crear material: ' . $stmt->error
        ];
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

$output = ob_get_clean();
if (!empty($output)) {
    $response['debug_output'] = $output;
}

echo json_encode($response, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
