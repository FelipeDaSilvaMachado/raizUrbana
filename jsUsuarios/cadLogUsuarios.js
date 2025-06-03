// Espera o DOM carregar completamente
document.addEventListener("DOMContentLoaded", function () {
    // Tenta verificar se já existe uma sessão ativa ao carregar a página
    // verificarSessao(); // Descomentado se a intenção for verificar login ao entrar na pág de cadastro

    const form = document.getElementById("cadUsuarioForm");
    const btnCadastrar = document.getElementById("btnCadastrar");
    const btnCancelar = document.getElementById("btnCancelar");
    const cepInput = document.getElementById("cep"); // Adicionado para validação no submit

    if (form && btnCadastrar) {
        form.addEventListener("submit", function (event) {
            event.preventDefault(); // Impede o envio padrão do formulário
            console.log("Formulário submetido via JS");
            // Chama a função de cadastro que agora inclui a validação do CEP
            cadastrarUsuario(form);
        });
    }

    if (btnCancelar) {
        btnCancelar.addEventListener("click", function () {
            console.log("Botão Cancelar clicado");
            cancelarCadastro(form);
        });
    }
    
    // Mantém o botão opcional, mas agora ele chama validaCep
    const btnValidarCep = document.getElementById("btnValidarCep"); // ID alterado no HTML também
    if(btnValidarCep) {
        btnValidarCep.addEventListener("click", validaCep);
    }
});

// Função para validar o CEP (apenas 8 dígitos)
function validaCep() {
    const cepInput = document.getElementById("cep");
    const cep = cepInput.value.replace(/\D/g, ''); // Remove não dígitos
    let isValid = true; // Assume que é válido inicialmente

    if (cep.length !== 8) {
        alert("CEP inválido. Deve conter exatamente 8 dígitos.");
        cepInput.focus(); // Foca no campo CEP
        isValid = false;
    } else {
        // Opcional: Poderia dar um feedback visual que o formato está correto
        console.log("Formato do CEP (8 dígitos) é válido.");
    }
    return isValid; // Retorna true se válido, false caso contrário
}

// Função para cadastrar o usuário
function cadastrarUsuario(form) {
    console.log("Iniciando cadastroUsuario...");
    const senha = form.senha.value;
    const confirmarSenha = form.confirmarSenha.value;

    // --- Validações Client-Side Essenciais ---
    if (senha.length < 6) {
        alert("A senha deve ter pelo menos 6 caracteres.");
        form.senha.focus();
        return; // Interrompe o envio
    }

    if (senha !== confirmarSenha) {
        alert("As senhas não coincidem. Por favor, verifique.");
        form.confirmarSenha.focus();
        return; // Interrompe o envio
    }
    
    // **Nova Validação**: Chama a função validaCep antes de prosseguir
    if (!validaCep()) {
        // A função validaCep já exibe o alerta e foca no campo
        return; // Interrompe o envio se o CEP for inválido
    }
    
    // Validar outros campos obrigatórios
    let camposObrigatorios = form.querySelectorAll("[required]");
    let algumVazio = false;
    camposObrigatorios.forEach(campo => {
        // Verifica se o campo está visível antes de validar (boa prática)
        if (campo.offsetParent !== null && !campo.value.trim()) { 
             console.warn(`Campo obrigatório vazio: ${campo.name}`);
             algumVazio = true;
        }
    });

    if (algumVazio) {
        alert("Por favor, preencha todos os campos obrigatórios.");
        // Focar no primeiro campo vazio encontrado
        for(let campo of camposObrigatorios) {
             if (campo.offsetParent !== null && !campo.value.trim()) {
                campo.focus();
                break;
            }
        }
        return;
    }
    // --- Fim Validações Client-Side ---

    // Prepara os dados para envio
    const formData = new FormData(form);
    formData.append("acao", "cadastrar"); // Adiciona a ação esperada pelo PHP

    console.log("Enviando dados para o backend...");
    // Desabilitar botão de submit para evitar cliques duplos
    const btnCadastrar = document.getElementById("btnCadastrar");
    if(btnCadastrar) btnCadastrar.disabled = true;

    // Envia para o PHP via Fetch API
    fetch("../BDPhp/raizUrbanaBD.php", { // Caminho relativo corrigido
        method: "POST",
        body: formData,
    })
    .then(response => {
        console.log("Resposta recebida do backend.");
        if (!response.ok) {
            throw new Error(`Erro HTTP: ${response.status} ${response.statusText}`);
        }
        return response.json();
    })
    .then(data => {
        console.log("Dados JSON recebidos:", data);
        if (data.sucesso) {
            alert("Cadastro realizado com sucesso!");
            form.reset();
        } else {
            alert(`Erro no cadastro: ${data.mensagem}`);
        }
    })
    .catch(error => {
        console.error("Erro durante o fetch:", error);
        alert(`Ocorreu um erro ao processar o cadastro. Verifique sua conexão ou tente novamente mais tarde. Detalhe: ${error.message}`);
    })
    .finally(() => {
        // Reabilitar botão de submit independentemente do resultado
        if(btnCadastrar) btnCadastrar.disabled = false;
    });
}

// Função para limpar o formulário
function cancelarCadastro(form) {
    if (confirm("Tem certeza que deseja limpar o formulário?")) {
        form.reset();
        console.log("Formulário resetado.");
    }
}

// Função para verificar a sessão do usuário (exemplo)
function verificarSessao() {
    console.log("Verificando sessão...");
    const formData = new FormData();
    formData.append("acao", "verificar_sessao"); // Ação correta

    fetch("../BDPhp/raizUrbanaBD.php", { // Caminho relativo corrigido
        method: "POST",
        body: formData,
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`Erro HTTP: ${response.status}`);
        }
        return response.json();
    })
    .then(data => {
        console.log("Resposta da verificação de sessão:", data);
        if (data.logado) {
            console.log("Usuário já está logado:", data.usuario);
        } else {
            console.log("Nenhum usuário logado na sessão.");
        }
    })
    .catch(error => {
        console.error("Erro ao verificar sessão:", error);
    });
}