<?php
require_once __DIR__ . '/../config/headers.php';
echo json_encode($_SESSION, JSON_PRETTY_PRINT);
