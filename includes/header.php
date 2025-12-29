<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Analise Contrato</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
<header class="site-header">
    <div class="container header-content">
        <a class="logo" href="index.php">AnaliseContrato</a>
        <nav class="nav-links">
            <a href="index.php#solucao">Solução</a>
            <a href="index.php#beneficios">Benefícios</a>
            <a href="login.php" class="btn-outline">Entrar</a>
            <a href="register.php" class="btn-primary">Cadastrar</a>
        </nav>
    </div>
</header>
<main>
