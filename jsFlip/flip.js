// Função para alternar entre tabs
function showTab(tabName) {
  // Remove active de todos os botões e conteúdos
  document.querySelectorAll(".tab-button").forEach((btn) => btn.classList.remove("active"))
  document.querySelectorAll(".tab-content").forEach((content) => content.classList.remove("active"))

  // Adiciona active no botão clicado
  event.target.classList.add("active")

  // Mostra o conteúdo correspondente
  document.getElementById(tabName + "Tab").classList.add("active")
}

// Validações e máscaras (mesmo da versão anterior)
document.addEventListener("DOMContentLoaded", () => {
  // Máscara para CPF
  const cpfInput = document.getElementById("cpf")
  if (cpfInput) {
    cpfInput.addEventListener("input", (e) => {
      let value = e.target.value.replace(/\D/g, "")
      value = value.replace(/(\d{3})(\d)/, "$1.$2")
      value = value.replace(/(\d{3})(\d)/, "$1.$2")
      value = value.replace(/(\d{3})(\d{1,2})$/, "$1-$2")
      e.target.value = value
    })
  }

  // Máscara para CEP
  const cepInput = document.getElementById("cep")
  if (cepInput) {
    cepInput.addEventListener("input", (e) => {
      let value = e.target.value.replace(/\D/g, "")
      value = value.replace(/(\d{5})(\d)/, "$1-$2")
      e.target.value = value
    })
  }

  // Lidar com o clique do botão "Cancelar Login"
  const btnCancelarLogin = document.getElementById("btnCancelarLogin");
  if (btnCancelarLogin) {
    btnCancelarLogin.addEventListener("click", () => {
      window.location.href = "../usuario/cadLogUsuarios.html";
    });
  }

  // Lidar com o clique do botão "Cancelar Cadastro"
  const btnCancelar = document.getElementById("btnCancelar");
  if (btnCancelar) {
    btnCancelar.addEventListener("click", () => {
      window.location.href = "../index.html";
    });
  }
});