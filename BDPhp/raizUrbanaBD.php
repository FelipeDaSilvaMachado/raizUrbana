<?php
// --- Supressao de Erros e Avisos ---
error_reporting(E_ALL);
ini_set("display_errors", 1);

session_start();

// Garante que o script so sera executado se incluido corretamente
// E que a conexao seja incluida apenas uma vez
if (!defined("CONEXAO_RAIZ_URBANA_INCLUIDA")) {
    require_once "conexRaizUrb.php";
    define("CONEXAO_RAIZ_URBANA_INCLUIDA", true);
}
global $pdo; // Torna a conexao PDO disponivel

// Verifica se a conexao PDO foi estabelecida com sucesso em conexRaizUrb.php
if (!isset($pdo) || $pdo === null) { // Verifica se $pdo existe e nao é null
    echo (json_encode(["sucesso" => false, "mensagem" => "Erro critico: Falha ao conectar ao banco de dados."]));
    exit; // Encerra o script
}

// --- Logica Principal --- 
$acao = $_POST["acao"] ?? ""; // Usar "acao" para determinar a operaçao para POST

// Logica para verificar sessao (GET request)
if ($_SERVER["REQUEST_METHOD"] === "GET" && isset($_GET["acao"]) && $_GET["acao"] === "verificar_sessao") {
    if (isset($_SESSION["usuario"]) && !empty($_SESSION["usuario"]["id"])) {
        echo (json_encode(["logado" => true, "usuario" => $_SESSION["usuario"]]));
    } else {
        echo (json_encode(["logado" => false, "message" => "Voce nao esta logado."]));
    }
    exit;
}

// Logica para logout (POST request)
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["acao"]) && $_POST["acao"] === "logout") {
    session_destroy();
    echo (json_encode(["sucesso" => true, "mensagem" => "Logout realizado com sucesso."]));
    exit;
}

// Logica para POST requests (cadastro e login)
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // $acao = $_POST["acao"] ?? "";
    if ($acao === "cadastrar") {
        // Coleta e limpeza de dados do POST
        $nomeUsuario = trim($_POST["nomeUsuario"] ?? "");
        $email = trim($_POST["email"] ?? "");
        $senha = $_POST["senha"] ?? ""; // Senha nao deve ter trim()
        $confirmarSenha = $_POST["confirmarSenha"] ?? "";
        $cpf = trim($_POST["cpf"] ?? "");
        $genero = trim($_POST["genero"] ?? "");
        $telefone = trim($_POST["telefone"] ?? ""); // Telefone fixo pode ser opcional
        $celular = trim($_POST["celular"] ?? "");
        $dataNasc = trim($_POST["dataNasc"] ?? "");
        $rua = trim($_POST["rua"] ?? "");
        $numero = trim($_POST["numero"] ?? "");
        $bairro = trim($_POST["bairro"] ?? "");
        $cidade = trim($_POST["cidade"] ?? "");
        $uf = trim($_POST["uf"] ?? "");
        $cep = trim($_POST["cep"] ?? "");

        // Validações robustas no backend
        if (empty($nomeUsuario) || empty($email) || empty($senha) || empty($confirmarSenha) || empty($cpf) || empty($genero) || empty($celular) || empty($dataNasc) || empty($rua) || empty($numero) || empty($bairro) || empty($cidade) || empty($uf) || empty($cep)) {
            header("Location: ../usuario/cadLogUsuarios.html?erro=" . urlencode("Todos os campos marcados como obrigatorios (*) devem ser preenchidos."));
            exit;
        }

        if ($senha !== $confirmarSenha) {
            header("Location: ../usuario/cadLogUsuarios.html?erro=" . urlencode("As senhas nao coincidem."));
            exit;
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            header("Location: ../usuario/cadLogUsuarios.html?erro=" . urlencode("Formato de e-mail invalido."));
            exit;
        }

        if (strlen($senha) < 6) {
            header("Location: ../usuario/cadLogUsuarios.html?erro=" . urlencode("A senha deve ter pelo menos 6 caracteres."));
            exit;
        }

        try {
            // 1. Verificar duplicaçao de email
            $stmtEmail = $pdo->prepare("SELECT idUsuario FROM cadusuarios WHERE email = :email");
            $stmtEmail->bindParam(":email", $email, PDO::PARAM_STR);
            $stmtEmail->execute();
            if ($stmtEmail->rowCount() > 0) {
                header("Location: ../usuario/cadLogUsuarios.html?erro=" . urlencode("Este e-mail ja esta cadastrado."));
                exit;
            }

            // 2. Verificar duplicaçao de CPF (se for único)
            $stmtCpf = $pdo->prepare("SELECT idUsuario FROM cadusuarios WHERE cpf = :cpf");
            $stmtCpf->bindParam(":cpf", $cpf, PDO::PARAM_STR);
            $stmtCpf->execute();
            if ($stmtCpf->rowCount() > 0) {
                header("Location: ../usuario/cadLogUsuarios.html?erro=" . urlencode("Este CPF ja esta cadastrado."));
                exit;
            }

            // Hash seguro da senha usando password_hash()
            $senha_hash = password_hash($senha, PASSWORD_DEFAULT);

            // Iniciar transaçao
            $pdo->beginTransaction();

            try {
                // 3. Inserir em cadusuarios
                $sqlCadUsuarios = "INSERT INTO cadusuarios (nomeUsuario, email, cpf, genero, dataNasc, senha) VALUES (:nomeUsuario, :email, :cpf, :genero, :dataNasc, :senha)";
                $stmtCadUsuarios = $pdo->prepare($sqlCadUsuarios);
                // Bind dos parâmetros
                $stmtCadUsuarios->bindParam(":nomeUsuario", $nomeUsuario, PDO::PARAM_STR);
                $stmtCadUsuarios->bindParam(":email", $email, PDO::PARAM_STR);
                $stmtCadUsuarios->bindParam(":cpf", $cpf, PDO::PARAM_STR);
                $stmtCadUsuarios->bindParam(":genero", $genero, PDO::PARAM_STR);
                $stmtCadUsuarios->bindParam(":dataNasc", $dataNasc, PDO::PARAM_STR);
                $stmtCadUsuarios->bindParam(":senha", $senha_hash, PDO::PARAM_STR);
                $stmtCadUsuarios->execute();

                $idUsuario = $pdo->lastInsertId();

                // 4. Inserir em endereco
                $sqlEndereco = "INSERT INTO endereco (fk_idUsuario, rua, numero, bairro, cidade, uf, cep) VALUES (:fk_idUsuario, :rua, :numero, :bairro, :cidade, :uf, :cep)";
                $stmtEndereco = $pdo->prepare($sqlEndereco);
                // Bind dos parâmetros
                $stmtEndereco->bindParam(":fk_idUsuario", $idUsuario, PDO::PARAM_INT);
                $stmtEndereco->bindParam(":rua", $rua, PDO::PARAM_STR);
                $stmtEndereco->bindParam(":numero", $numero, PDO::PARAM_STR);
                $stmtEndereco->bindParam(":bairro", $bairro, PDO::PARAM_STR);
                $stmtEndereco->bindParam(":cidade", $cidade, PDO::PARAM_STR);
                $stmtEndereco->bindParam(":uf", $uf, PDO::PARAM_STR);
                $stmtEndereco->bindParam(":cep", $cep, PDO::PARAM_STR);
                $stmtEndereco->execute();

                // 5. Inserir em telefones
                $sqlTelefones = "INSERT INTO telefones (fk_idUsuario, telefone, celular) VALUES (:fk_idUsuario, :telefone, :celular)";
                $stmtTelefones = $pdo->prepare($sqlTelefones);
                // Bind dos parâmetros
                $stmtTelefones->bindParam(":fk_idUsuario", $idUsuario, PDO::PARAM_INT);
                $stmtTelefones->bindParam(":telefone", $telefone, PDO::PARAM_STR);
                $stmtTelefones->bindParam(":celular", $celular, PDO::PARAM_STR);
                $stmtTelefones->execute();

                // Commit da transaçao
                $pdo->commit();

                // Resposta de sucesso
                header("Location: ../usuario/cadLogUsuarios.html?sucesso=" . urlencode("Cadastro realizado com sucesso!"));
                exit;
            } catch (Exception $e) {
                // Rollback em caso de erro na transaçao
                $pdo->rollBack();
                error_log("Erro durante a transaçao de cadastro: " . $e->getMessage());
                header("Location: ../usuario/cadLogUsuarios.html?erro=" . urlencode("Erro ao salvar os dados. Tente novamente."));
                exit;
            }
        } catch (PDOException $e) {
            // Erro geral de PDO (conexao, preparaçao inicial)
            error_log("Erro de PDO no cadastro: " . $e->getMessage());
            header("Location: ../usuario/cadLogUsuarios.html?erro=" . urlencode("Erro interno no servidor ao processar o cadastro."));
            exit;
        }
    } else if ($acao === "logar") {
        $email = trim($_POST["email"] ?? "");
        $senha = $_POST["senha"] ?? "";

        if (empty($email) || empty($senha)) {
            // $myeCode = $pdo->errorCode(400);
            header("Location: ../usuario/cadLogUsuarios.html?erro=" . urlencode("Email e senha sao obrigatorios."));
            exit;
        }

        $stmtLogin = $pdo->prepare("SELECT nomeUsuario, email, senha FROM cadusuarios WHERE email = :email");
        $stmtLogin->bindParam(":email", $email, PDO::PARAM_STR);
        $stmtLogin->execute();

        if ($stmtLogin->rowCount() === 0) {
            header("Location: ../usuario/cadLogUsuarios.html?erro=" . urlencode("Email ou senha incorretos."));
            exit;
        }

        $usuario = $stmtLogin->fetch(PDO::FETCH_ASSOC);

        if (!password_verify($senha, $usuario["senha"])) {
            header("Location: ../usuario/cadLogUsuarios.html?erro=" . urlencode("Email ou senha incorretos."));
            exit;
        }

        $_SESSION["usuario"] = [
            "nome" => $usuario["nomeUsuario"],
            "email" => $usuario["email"]
        ];

        // Redireciona para a página inicial após o login
        header("Location: ../../index.html?sucesso=" . urlencode("Login realizado com sucesso!"));
        exit;
    } else {
        // Açao invalida para POST
        header("Location: ../usuario/cadLogUsuarios.html?erro=" . urlencode("Açao POST invalida."));
        exit;
    }
} else {
    header("Location: ../usuario/cadLogUsuarios.html?erro=" . urlencode("Metodo nao permitido. Use POST para cadastro ou login e GET para verificaçao de sessao."));
    exit;
}
