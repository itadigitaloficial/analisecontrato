<?php
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Método não permitido.']);
    exit;
}

if (!isset($_FILES['arquivo'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Arquivo não enviado.']);
    exit;
}

$allowed = ['pdf', 'doc', 'docx'];
$ext = strtolower(pathinfo($_FILES['arquivo']['name'], PATHINFO_EXTENSION));
if (!in_array($ext, $allowed, true)) {
    http_response_code(400);
    echo json_encode(['error' => 'Formato de arquivo inválido.']);
    exit;
}

$uploadsDir = __DIR__ . '/uploads/';
if (!is_dir($uploadsDir)) {
    mkdir($uploadsDir, 0755, true);
}

$filename = uniqid('contrato_', true) . '.' . $ext;
$destination = $uploadsDir . $filename;

if (!move_uploaded_file($_FILES['arquivo']['tmp_name'], $destination)) {
    http_response_code(500);
    echo json_encode(['error' => 'Falha ao salvar arquivo.']);
    exit;
}

$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
$host = $_SERVER['HTTP_HOST'] ?? 'localhost';
$basePath = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/\\');
$fileUrl = $protocol . '://' . $host . $basePath . '/uploads/' . $filename;

header('Content-Type: application/json');
echo json_encode(['file_url' => $fileUrl]);
