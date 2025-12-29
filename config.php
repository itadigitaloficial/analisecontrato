<?php
$host = 'localhost';
$user = 'itaweb64_analiseuser';
$password = 'x-XLU}O#RKs0';
$database = 'itaweb64_analisecontrato';

$dbError = '';
mysqli_report(MYSQLI_REPORT_OFF);
$mysqli = @new mysqli($host, $user, $password, $database);
if ($mysqli->connect_errno) {
    $dbError = 'Erro ao conectar no banco de dados. Verifique as credenciais.';
} else {
    $mysqli->set_charset('utf8mb4');
}

function ensure_users_table(?mysqli $mysqli): void
{
    if (!$mysqli) {
        return;
    }
    $sql = "CREATE TABLE IF NOT EXISTS users (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(120) NOT NULL,
        email VARCHAR(160) NOT NULL UNIQUE,
        password VARCHAR(255) NOT NULL,
        whatsapp VARCHAR(30) NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";
    $mysqli->query($sql);
}
