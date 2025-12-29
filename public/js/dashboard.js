const userName = localStorage.getItem("userName");
const userId = localStorage.getItem("userId");
const userNameEl = document.querySelector("#user-name");
const apiMeta = document.querySelector("meta[name=\"api-base\"]");
const apiBase = apiMeta?.content?.replace(/\/$/, "") || "";
const buildApiUrl = (path) => (apiBase ? `${apiBase}${path}` : path);

if (userNameEl && userName) {
  userNameEl.textContent = userName;
}

const contractForm = document.querySelector("#contract-form");

const showAlert = (message, type) => {
  const container = document.querySelector("#contract-alert");
  if (!container) return;
  container.innerHTML = `
    <div class="alert ${type === "success" ? "alert-success" : "alert-error"}">
      ${message}
    </div>
  `;
};

if (contractForm) {
  contractForm.addEventListener("submit", async (event) => {
    event.preventDefault();

    const payload = {
      nome: contractForm.nome.value.trim(),
      whatsapp: contractForm.whatsapp.value.trim(),
      file_url: contractForm.file_url.value.trim(),
      userId: userId ? Number(userId) : null,
    };

    try {
      const response = await fetch(buildApiUrl("/api/contract"), {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify(payload),
      });

      const data = await response.json();

      if (!response.ok) {
        showAlert(data.message || "Erro ao enviar contrato.", "error");
        return;
      }

      showAlert("Solicitação enviada com sucesso!", "success");
      contractForm.reset();
    } catch (error) {
      showAlert("Erro ao conectar com o servidor.", "error");
    }
  });
}
