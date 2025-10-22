<?php
// Middleware to check authentication
function checkAuth() {
    if (!isset($_SESSION['user'])) {
        http_response_code(401);
        echo json_encode([
            'status' => 'error',
            'message' => 'No autenticado. Por favor inicie sesión.'
        ]);
        exit;
    }
}

function checkRole(array $rolesPermitidos) {
    checkAuth(); // primero aseguramos que haya sesión

    $user = $_SESSION['user'] ?? null;

    // Asegurar formato limpio del rol
    $rol = isset($user['role']) ? strtolower(trim($user['role'])) : '';

    // Convertir roles permitidos a minúsculas
    $rolesPermitidos = array_map('strtolower', $rolesPermitidos);

    if (!in_array($rol, $rolesPermitidos)) {
        http_response_code(403);
        echo json_encode([
            'status' => 'error',
            'message' => 'Acceso denegado. No tiene permisos suficientes.'
        ]);
        exit;
    }
}
?>
