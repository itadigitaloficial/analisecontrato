<?php
require_once 'includes/header.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}
?>
<section class="dashboard">
    <div class="container">
        <div class="dashboard-header">
            <div>
                <span class="badge">Dashboard</span>
                <h2>Olá, <?php echo htmlspecialchars($_SESSION['user_name']); ?></h2>
                <p>Envie um contrato para análise e acompanhe o status em tempo real.</p>
            </div>
            <a href="logout.php" class="btn-outline">Sair</a>
        </div>

        <div class="grid two-columns">
            <div class="card">
                <h3>Nova análise</h3>
                <p>Preencha os dados abaixo e envie o arquivo do contrato.</p>
                <form id="analysis-form" class="analysis-form">
                    <label>Nome</label>
                    <input type="text" name="nome" placeholder="Nome completo" required>
                    <label>WhatsApp</label>
                    <input type="text" name="whatsapp" placeholder="(00) 00000-0000" required>
                    <label>Arquivo do contrato</label>
                    <input type="file" name="arquivo" accept=".pdf,.doc,.docx" required>
                    <button class="btn-primary" type="submit">Enviar para análise</button>
                </form>
                <div id="analysis-feedback" class="alert hidden"></div>
            </div>
            <div class="card">
                <h3>Status & indicadores</h3>
                <div class="status-grid">
                    <div class="status-item">
                        <span class="icon">📄</span>
                        <div>
                            <strong>Contratos enviados</strong>
                            <span>Organize todas as análises em um só lugar.</span>
                        </div>
                    </div>
                    <div class="status-item">
                        <span class="icon">✅</span>
                        <div>
                            <strong>Checklist inteligente</strong>
                            <span>Alertas sobre riscos e pendências em tempo real.</span>
                        </div>
                    </div>
                    <div class="status-item">
                        <span class="icon">📈</span>
                        <div>
                            <strong>Insights de negócio</strong>
                            <span>Relatórios para decisões estratégicas.</span>
                        </div>
                    </div>
                </div>
                <div class="cta minor">
                    <p>Precisa de suporte imediato?</p>
                    <a href="https://wa.me/5500000000000" class="btn-secondary" target="_blank" rel="noopener">Falar com especialista</a>
                </div>
            </div>
        </div>
    </div>
</section>
<?php
require_once 'includes/footer.php';
?>
