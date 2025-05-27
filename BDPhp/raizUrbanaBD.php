<?php
session_start();
require_once 'conexRaizUrb.php';
header('Content-Type: application/json');

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $cadastrando = $_POST['cadUsuario'] ?? '';

    if($cadastrando === 'cadUsuario'){
        $nomeUsuario = $_POST['nomeUsuario'] ?? '';
        $email = $_POST['email'] ?? '';
        $senha = $_POST['senha'] ?? '';
        $confirmarSenha = $_POST['confirmarSenha'] ?? '';
        $cpf = $_POST['cpf'] ?? '';
        $telefone = $_POST['telefone'] ?? '';
        $celular = $_POST['celular'] ?? '';
        $dataNasc = $_POST['dataNasc'] ?? '';
        $rua = $_POST['rua'] ?? '';
        $numero = $_POST['numero'] ?? '';
        $bairro = $_POST['bairro'] ?? '';
        $cidade = $_POST['cidade'] ?? '';
        $uf = $_POST['uf'] ?? '';
        $cep = $_POST['cep'] ?? '';

        $formulario = [$nomeUsuario, $email, $senha, $confirmarSenha, $cpf, $telefone,
            $celular, $dataNasc, $rua, $numero, $bairro, $cidade, $uf, $cep];

        // Validações básicas
        if (empty($formulario)) {
            echo json_encode(['sucesso' => false, 'mensagem' => 'Todos os campos são obrigatórios']);
            exit;
        }
        
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            echo json_encode(['sucesso' => false, 'mensagem' => 'Email inválido']);
            exit;
        }
        
        if (strlen($senha) < 6) {
            echo json_encode(['sucesso' => false, 'mensagem' => 'Senha deve ter pelo menos 6 caracteres']);
            exit;
        }

        try {
            // Verificar se email já existe
            $stmtEmail = $pdo->prepare("SELECT idUsuario FROM cadusuarios WHERE email = ?");
            $stmtEmail->execute([$email]);
            
            if ($stmtEmail->rowCount() > 0) {
                echo json_encode(['sucesso' => false, 'mensagem' => 'Este email já está cadastrado']);
                exit;
            }
            
            // Inserir novo usuário
            $senha_hash = password_hash($senha, PASSWORD_DEFAULT);
            $stmtEmail = $pdo->prepare("INSERT INTO usuarios (nome, email, senha) VALUES (?, ?, ?)");
            $stmt->execute([$nome, $email, $senha_hash]);
            
            echo json_encode(['sucesso' => true, 'mensagem' => 'Cadastro realizado com sucesso!']);
            
        } catch(PDOException $e) {
            echo json_encode(['sucesso' => false, 'mensagem' => 'Erro ao cadastrar usuário']);
        }
    }    
}
