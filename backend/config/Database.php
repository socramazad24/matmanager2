<?php
namespace matmanager;

use mysqli;

class Database {
    private $host = "localhost";
    private $user = "root";
    private $pass = "";
    private $dbname = "mat-manager";
    private $conn;

    public function __construct() {
        $this->conn = new mysqli($this->host, $this->user, $this->pass, $this->dbname);
        if ($this->conn->connect_error) {
            die("Error de conexión: " . $this->conn->connect_error);
        }
    }

    public function getConnection() {
        return $this->conn;
    }
}
