<?php
namespace login;
session_start();

header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

require_once __DIR__ . "/../Database.php"; // ajusta la ruta si es necesario
use matmanager\Database;

class Login {
    private $conex;

    public function __construct() {
        $db = new Database();
        $this->conex = $db->getConnection();
    }

    public function login() {
        if ($_SERVER["REQUEST_METHOD"] === "POST") { 
            // Recibe los datos del body en JSON
            $data = json_decode(file_get_contents("php://input"), true);

            $username = $data["username"] ?? '';
            $password = $data["password"] ?? '';

            if (empty($username) || empty($password)) {
                echo json_encode([
                    "status" => "error",
                    "message" => "Faltan credenciales"
                ]);
                exit;
            }

            // Consulta segura con parámetros
            $query = "SELECT * FROM users WHERE username = ? AND password = ?";
            $stmt = $this->conex->prepare($query);
            $stmt->bind_param("ss", $username, $password);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result && $result->num_rows === 1) {
                $user = $result->fetch_assoc();
                $_SESSION['username'] = $user['username'];

                echo json_encode([
                    "status" => "success",
                    "message" => "Inicio de sesión exitoso",
                    "role" => $user['role'],
                    "username" => $user['username']
                ]);
            } else {
                echo json_encode([
                    "status" => "error",
                    "message" => "Credenciales incorrectas"
                ]);
            }
        } else {
            echo json_encode([
                "status" => "error",
                "message" => "Método no permitido"
            ]);
        }

        $this->conex->close();
    }
}

$main = new Login();
$main->login();
