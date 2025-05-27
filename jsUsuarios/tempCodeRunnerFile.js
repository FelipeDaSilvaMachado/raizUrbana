function validaSenha() {
    cadUsuario(senha, confirmarSenha);
    const senha = '';
    const confirmarSenha = '';
    do {
        senha = document.getElementById(cadUsuario()).value(cadastro.senha);
        confirmarSenha = document.getElementById(cadUsuario()).value(cadastro.confirmarSenha);
        if (senha === confirmarSenha) {
            alert("Login efetuado com sucesso!")
        } else {
            alert("Senhas não conferem!");
        }
    } while (senha != confirmarSenha);
}