const loginForm = document.querySelector("#login-form");
const registerForm = document.querySelector("#register-form");
const apiMeta = document.querySelector("meta[name=\"api-base\"]");
const apiBase = apiMeta?.content?.replace(/\/$/, "") || "";
const buildApiUrl = (path) => (apiBase ? `${apiBase}${path}` : path);

const showAlert = (containerId, message, type) => {
  const container = document.querySelector(containerId);
  if (!container) return;
  container.innerHTML = `
    <div class="alert ${type === "success" ? "alert-success" : "alert-error"}">
      ${message}
    </div>
  `;
};

if (registerForm) {
  registerForm.addEventListener("submit", async (event) => {
    event.preventDefault();

    const payload = {
      name: registerForm.name.value.trim(),
      email: registerForm.email.value.trim(),
      whatsapp: registerForm.whatsapp.value.trim(),
      password: registerForm.password.value,
    };

    try {
      const response = await fetch(buildApiUrl("/api/register"), {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify(payload),
      });

      const data = await response.json();

      if (!response.ok) {
        showAlert("#register-alert", data.message || "Erro no cadastro.", "error");
        return;
      }

      localStorage.setItem("userName", data.name);
      showAlert("#register-alert", "Conta criada com sucesso!", "success");
      setTimeout(() => {
        window.location.href = "/dashboard.html";
      }, 800);
    } catch (error) {
      showAlert("#register-alert", "Erro ao conectar com o servidor.", "error");
    }
  });
}

if (loginForm) {
  loginForm.addEventListener("submit", async (event) => {
    event.preventDefault();

    const payload = {
      email: loginForm.email.value.trim(),
      password: loginForm.password.value,
    };

    try {
      const response = await fetch(buildApiUrl("/api/login"), {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify(payload),
      });

      const data = await response.json();

      if (!response.ok) {
        showAlert("#login-alert", data.message || "Erro no login.", "error");
        return;
      }

      localStorage.setItem("userName", data.name);
      localStorage.setItem("userId", data.id);
      showAlert("#login-alert", "Login realizado!", "success");
      setTimeout(() => {
        window.location.href = "/dashboard.html";
      }, 600);
    } catch (error) {
      showAlert("#login-alert", "Erro ao conectar com o servidor.", "error");
    }
  });
}
