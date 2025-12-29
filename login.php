<?php
require_once 'config.php';
require_once 'includes/header.php';

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($dbError) {
        $error = $dbError;
    }
    $email = trim($_POST['email'] ?? '');
    $senha = $_POST['senha'] ?? '';

    if (!$error && $email && $senha) {
        ensure_users_table($mysqli);
        $stmt = $mysqli->prepare('SELECT id, nome, senha FROM users WHERE email = ? LIMIT 1');
        if ($stmt) {
            $stmt->bind_param('s', $email);
            $stmt->execute();
            $result = $stmt->get_result();
            $user = $result->fetch_assoc();
            if ($user && password_verify($senha, $user['senha'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_name'] = $user['nome'];
                header('Location: dashboard.php');
                exit;
            }
            $error = 'Email ou senha inválidos.';
        } else {
            $error = 'Erro ao acessar o banco de dados.';
        }
    } elseif (!$error) {
        $error = 'Preencha todos os campos.';
    }
}
?>
<section class="auth-section">
    <div class="container auth-card">
        <div class="auth-header">
            <h2>Bem-vindo de volta</h2>
            <p>Entre para acompanhar seus contratos.</p>
        </div>
        <?php if ($error): ?>
            <div class="alert"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>
        <form method="POST" class="auth-form">
            <label>Email</label>
            <input type="email" name="email" placeholder="voce@email.com" required>
            <label>Senha</label>
            <input type="password" name="senha" placeholder="Sua senha" required>
            <button class="btn-primary" type="submit">Entrar</button>
        </form>
        <p class="auth-footer">Ainda não tem conta? <a href="register.php">Criar agora</a></p>
    </div>
</section>
<?php
require_once 'includes/footer.php';
?>
