// document.addEventListener('DOMContentLoaded', function () {
//     verificarConexao();
// });

function cadUsuario() {
    document.getElementById('btnCadastrar').addEventListener('submit', function (e) {
        e.preventDefault();
        const formCadastro = new formCadastro();
        formCadastro.append('acao', 'cadUsuario'); // Define que é CADASTRO 
        formCadastro.append('nomeUsuario', document.getElementById('nomeUsuario').value);
        formCadastro.append('email', document.getElementById('email').value);
        formCadastro.append('senha', document.getElementById('senhaCadastro').value);
        formCadastro.append('confirmarSenha', document.getElementById('confirmarSenha').value);
        formCadastro.append(document.getElementById('cpf').value);
        formCadastro.append(document.getElementById('telefone').value);
        formCadastro.append(document.getElementById('celular').value);
        formCadastro.append(document.getElementById('dataNasc').value);
        formCadastro.append(document.getElementById('rua').value);
        formCadastro.append(document.getElementById('numero').value);
        formCadastro.append(document.getElementById('bairro').value);
        formCadastro.append(document.getElementById('cidade').value);
        formCadastro.append(document.getElementById('uf').value);
        formCadastro.append(document.getElementById('cep').value);

        // Envia para processar.php fetch('processar.php', { method: 'POST', body: formData })
    });
    cadastro = 
    {
        nome: 'nomeUsuario',
        email: 'email',
        senha: 'senha',
        confirmarSenha: 'confirmarSenha',
        cpf: 'cpf',
        telefone: 'telefone',
        celular: 'celular',
        dataNasc: 'dataNasc',
        rua: 'rua',
        numero: 'numero',
        bairro: 'bairro',
        cidade: 'cidade',
        uf: 'uf',
        cep: 'cep'
    }

    formCadastro.append(document.ElementById(cadastro).value);
}

function validaSenha() {
    cadUsuario(senha, confirmarSenha);
    do {
        formCadastro.append('senha', document.getElementById('senhaCadastro').value);
        formCadastro.append('confirmarSenha', document.getElementById('confirmarSenha').value);
        if (senha === confirmarSenha) {
            alert("Login efetuado com sucesso!")
        } else {
            alert("Senhas não conferem!");
        }
    } while (senha != confirmarSenha);
}

// Função para verificar se usuário está logado
// function verificarSessao() {
//     const formLogin = new FormLogin();
//     formLogin.append('acao', 'verificar_sessao');

//     fetch('raizUrbanaBD.php', {
//         method: 'POST',
//         body: formLogin
//     })
//     .then(response => response.json())
//     .then(data => {
//         if (data.logado) {
//             mostrarDashboard(data.usuario);
//         }
//     })
//     .catch(error => {
//         console.error('Erro ao verificar sessão:', error);
//     });
// }