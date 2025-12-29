<?php
$host = 'localhost';
$user = 'itaweb64_analiseuser';
$password = 'x-XLU}O#RKs0';
$database = 'itaweb64_analisecontrato';

$mysqli = new mysqli($host, $user, $password, $database);
if ($mysqli->connect_errno) {
    http_response_code(500);
    echo 'Erro ao conectar no banco de dados.';
    exit;
}

$mysqli->set_charset('utf8mb4');

function ensure_users_table(mysqli $mysqli): void
{
    $sql = "CREATE TABLE IF NOT EXISTS users (
        id INT AUTO_INCREMENT PRIMARY KEY,
        nome VARCHAR(120) NOT NULL,
        email VARCHAR(190) NOT NULL UNIQUE,
        senha VARCHAR(255) NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";
    $mysqli->query($sql);
}
