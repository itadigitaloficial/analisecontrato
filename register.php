<?php
require_once 'config.php';
require_once 'includes/header.php';

$error = '';
$success = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = trim($_POST['nome'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $senha = $_POST['senha'] ?? '';

    if ($nome && $email && $senha) {
        ensure_users_table($mysqli);
        $hash = password_hash($senha, PASSWORD_DEFAULT);
        $stmt = $mysqli->prepare('INSERT INTO users (nome, email, senha) VALUES (?, ?, ?)');
        if ($stmt) {
            $stmt->bind_param('sss', $nome, $email, $hash);
            if ($stmt->execute()) {
                $success = 'Cadastro realizado! Faça login para continuar.';
            } else {
                $error = 'Não foi possível cadastrar. Verifique se o email já existe.';
            }
        } else {
            $error = 'Erro ao preparar o cadastro.';
        }
    } else {
        $error = 'Preencha todos os campos.';
    }
}
?>
<section class="auth-section">
    <div class="container auth-card">
        <div class="auth-header">
            <h2>Crie sua conta</h2>
            <p>Comece agora a analisar contratos com agilidade.</p>
        </div>
        <?php if ($error): ?>
            <div class="alert"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>
        <?php if ($success): ?>
            <div class="alert success"><?php echo htmlspecialchars($success); ?></div>
        <?php endif; ?>
        <form method="POST" class="auth-form">
            <label>Nome completo</label>
            <input type="text" name="nome" placeholder="Seu nome" required>
            <label>Email</label>
            <input type="email" name="email" placeholder="voce@email.com" required>
            <label>Senha</label>
            <input type="password" name="senha" placeholder="Crie uma senha" required>
            <button class="btn-primary" type="submit">Cadastrar</button>
        </form>
        <p class="auth-footer">Já tem conta? <a href="login.php">Entrar</a></p>
    </div>
</section>
<?php
require_once 'includes/footer.php';
?>
