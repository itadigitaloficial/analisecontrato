<?php
$endpoint = 'https://n8n.itadigital.com.br/webhook/ava-fornecedor';
$suppliers = [];
$error = null;

$context = stream_context_create([
    'http' => [
        'method' => 'GET',
        'timeout' => 20,
        'header' => "User-Agent: analisecontrato/1.0\r\nAccept: application/json\r\n"
    ]
]);

$response = @file_get_contents($endpoint, false, $context);
if ($response === false) {
    $error = 'Não foi possível consultar o endpoint de fornecedores no momento.';
} else {
    $decoded = json_decode($response, true);
    if (is_array($decoded)) {
        $suppliers = $decoded;
    } else {
        $error = 'Resposta inesperada do endpoint.';
    }
}
?>
<!doctype html>
<html lang="pt-BR">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Fornecedores | Ava</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
  <link rel="stylesheet" href="assets/styles.css">
</head>
<body>
  <main class="container">
    <header class="hero">
      <div>
        <p class="kicker"><i class="bi bi-building-check"></i> Cadastro de fornecedores</p>
        <h1>Lista de fornecedores</h1>
        <p class="subtitle">Visualização moderna para consultar, buscar e filtrar fornecedores do endpoint AVA.</p>
      </div>
      <div class="hero-actions">
        <button id="refreshButton" class="btn btn-primary"><i class="bi bi-arrow-clockwise"></i> Atualizar</button>
      </div>
    </header>

    <section class="stats" id="stats"></section>

    <section class="controls card">
      <label class="input-group">
        <i class="bi bi-search"></i>
        <input type="search" id="searchInput" placeholder="Buscar por razão social, fantasia, CNPJ ou cidade...">
      </label>
      <label class="select-group">
        <i class="bi bi-geo-alt"></i>
        <select id="ufFilter">
          <option value="">Todos os estados</option>
        </select>
      </label>
    </section>

    <?php if ($error): ?>
      <section class="empty card">
        <i class="bi bi-exclamation-triangle"></i>
        <h2>Falha ao carregar</h2>
        <p><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></p>
      </section>
    <?php else: ?>
      <section id="suppliersGrid" class="grid"></section>
      <section id="emptyState" class="empty card hidden">
        <i class="bi bi-inboxes"></i>
        <h2>Nenhum fornecedor encontrado</h2>
        <p>Tente ajustar os filtros para visualizar resultados.</p>
      </section>
    <?php endif; ?>
  </main>

  <script>
    window.__SUPPLIERS__ = <?= json_encode($suppliers, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) ?>;
  </script>
  <script src="assets/app.js"></script>
</body>
</html>
