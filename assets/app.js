const suppliers = Array.isArray(window.__SUPPLIERS__) ? window.__SUPPLIERS__ : [];

const grid = document.getElementById('suppliersGrid');
const emptyState = document.getElementById('emptyState');
const searchInput = document.getElementById('searchInput');
const ufFilter = document.getElementById('ufFilter');
const stats = document.getElementById('stats');
const refreshButton = document.getElementById('refreshButton');

function sanitize(value) {
  return (value ?? '').toString().trim();
}

function getUfOptions() {
  return [...new Set(suppliers.map(item => sanitize(item.endereco_uf)).filter(Boolean))].sort();
}

function renderStats(list) {
  const withCnpj = list.filter(item => sanitize(item.cnpj)).length;
  const withEmail = list.filter(item => sanitize(item.email)).length;

  stats.innerHTML = `
    <article class="stat card">
      <p class="label"><i class="bi bi-collection"></i>Total</p>
      <p class="value">${list.length}</p>
    </article>
    <article class="stat card">
      <p class="label"><i class="bi bi-card-text"></i>Com CNPJ</p>
      <p class="value">${withCnpj}</p>
    </article>
    <article class="stat card">
      <p class="label"><i class="bi bi-envelope-check"></i>Com e-mail</p>
      <p class="value">${withEmail}</p>
    </article>
  `;
}

function supplierCard(item) {
  const nome = sanitize(item.nome_razao_social) || 'Sem razão social';
  const fantasia = sanitize(item.nome_fantasia);
  const cnpj = sanitize(item.cnpj) || 'Não informado';
  const cidadeUf = [sanitize(item.cidade), sanitize(item.endereco_uf)].filter(Boolean).join(' / ') || 'Local não informado';
  const contato = sanitize(item.contato_nome) || sanitize(item.nome) || 'Não informado';
  const email = sanitize(item.email) || 'Não informado';

  return `
    <article class="supplier card">
      <h3>${nome}</h3>
      <p class="fantasy">${fantasia || 'Sem nome fantasia'}</p>

      <div class="meta">
        <p class="meta-item"><i class="bi bi-upc-scan"></i> <span>${cnpj}</span></p>
        <p class="meta-item"><i class="bi bi-geo-alt"></i> <span>${cidadeUf}</span></p>
        <p class="meta-item"><i class="bi bi-person-badge"></i> <span>${contato}</span></p>
        <p class="meta-item"><i class="bi bi-envelope"></i> <span>${email}</span></p>
      </div>

      <div class="badges">
        ${item.banco_conta_corrente ? '<span class="badge success">Conta corrente</span>' : '<span class="badge">Sem conta corrente</span>'}
        ${item.banco_poupanca ? '<span class="badge success">Poupança</span>' : '<span class="badge">Sem poupança</span>'}
      </div>
    </article>
  `;
}

function applyFilters() {
  const term = sanitize(searchInput?.value).toLowerCase();
  const uf = sanitize(ufFilter?.value).toLowerCase();

  const filtered = suppliers.filter(item => {
    const searchable = [
      item.nome_razao_social,
      item.nome_fantasia,
      item.cnpj,
      item.cidade,
      item.contato_nome,
      item.email
    ].map(sanitize).join(' ').toLowerCase();

    const ufValue = sanitize(item.endereco_uf).toLowerCase();
    const termMatch = !term || searchable.includes(term);
    const ufMatch = !uf || ufValue === uf;
    return termMatch && ufMatch;
  });

  renderStats(filtered);
  grid.innerHTML = filtered.map(supplierCard).join('');
  emptyState.classList.toggle('hidden', filtered.length > 0);
}

function init() {
  getUfOptions().forEach(uf => {
    const option = document.createElement('option');
    option.value = uf;
    option.textContent = uf;
    ufFilter.appendChild(option);
  });

  searchInput?.addEventListener('input', applyFilters);
  ufFilter?.addEventListener('change', applyFilters);
  refreshButton?.addEventListener('click', () => window.location.reload());

  applyFilters();
}

init();
