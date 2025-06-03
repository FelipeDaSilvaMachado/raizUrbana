<?php
// --- Supressão de Erros e Avisos ---
// Idealmente, isso deve ser configurado no php.ini em produção
error_reporting(0);
ini_set("display_errors", 0);

session_start();

// Garante que o script só será executado se incluído corretamente
// E que a conexão seja incluída apenas uma vez
if (!defined("CONEXAO_RAIZ_URBANA_INCLUIDA")) {
    require_once "conexRaizUrb.php"; // Assume que conexRaizUrb.php está no mesmo diretório
    define("CONEXAO_RAIZ_URBANA_INCLUIDA", true);
}
global $pdo; // Torna a conexão PDO disponível

// --- Headers HTTP --- 
// Devem ser definidos ANTES de qualquer saída
header("Content-Type: application/json");
// Considerar restringir a origem em produção para maior segurança
header("Access-Control-Allow-Origin: *"); 
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");

// Verifica se a conexão PDO foi estabelecida com sucesso em conexRaizUrb.php
if (!isset($pdo) || $pdo === null) { // Verifica se $pdo existe e não é null
    // Envia uma resposta JSON indicando o erro crítico de conexão
    echo json_encode(["sucesso" => false, "mensagem" => "Erro crítico: Falha ao conectar ao banco de dados."]);
    exit; // Encerra o script
}

// --- Lógica Principal --- 
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $acao = $_POST["acao"] ?? ""; // Usar "acao" para determinar a operação

    if ($acao === "cadastrar") {
        // Coleta e limpeza de dados do POST
        $nomeUsuario = trim($_POST["nomeUsuario"] ?? "");
        $email = trim($_POST["email"] ?? "");
        $senha = $_POST["senha"] ?? ""; // Senha não deve ter trim()
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
            echo json_encode(["sucesso" => false, "mensagem" => "Todos os campos marcados como obrigatórios devem ser preenchidos."]);
            exit;
        }

        if ($senha !== $confirmarSenha) {
            echo json_encode(["sucesso" => false, "mensagem" => "As senhas não coincidem."]);
            exit;
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            echo json_encode(["sucesso" => false, "mensagem" => "Formato de e-mail inválido."]);
            exit;
        }

        if (strlen($senha) < 6) {
            echo json_encode(["sucesso" => false, "mensagem" => "A senha deve ter pelo menos 6 caracteres."]);
            exit;
        }
        
        // Adicionar outras validações (CPF, CEP, etc.) se necessário

        try {
            // 1. Verificar duplicação de email
            $stmtEmail = $pdo->prepare("SELECT idUsuario FROM cadusuarios WHERE email = :email");
            $stmtEmail->bindParam(":email", $email, PDO::PARAM_STR);
            $stmtEmail->execute();
            if ($stmtEmail->rowCount() > 0) {
                echo json_encode(["sucesso" => false, "mensagem" => "Este e-mail já está cadastrado."]);
                exit;
            }
            
            // 2. Verificar duplicação de CPF (se for único)
            $stmtCpf = $pdo->prepare("SELECT idUsuario FROM cadusuarios WHERE cpf = :cpf");
            $stmtCpf->bindParam(":cpf", $cpf, PDO::PARAM_STR);
            $stmtCpf->execute();
            if ($stmtCpf->rowCount() > 0) {
                echo json_encode(["sucesso" => false, "mensagem" => "Este CPF já está cadastrado."]);
                exit;
            }

            // Hash da senha
            $senha_hash = password_hash($senha, PASSWORD_DEFAULT);

            // Iniciar transação
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

                // Commit da transação
                $pdo->commit();

                // Iniciar sessão (opcional)
                $_SESSION["usuario_logado"] = [
                    "id" => $idUsuario,
                    "nome" => $nomeUsuario,
                    "email" => $email
                ];

                // Resposta de sucesso
                echo json_encode(["sucesso" => true, "mensagem" => "Cadastro realizado com sucesso!"]);

            } catch (Exception $e) {
                // Rollback em caso de erro na transação
                $pdo->rollBack();
                error_log("Erro durante a transação de cadastro: " . $e->getMessage());
                echo json_encode(["sucesso" => false, "mensagem" => "Erro ao salvar os dados. Tente novamente."]);
                exit;
            }

        } catch (PDOException $e) {
            // Erro geral de PDO (conexão, preparação inicial)
            error_log("Erro de PDO no cadastro: " . $e->getMessage());
            echo json_encode(["sucesso" => false, "mensagem" => "Erro interno no servidor ao processar o cadastro."]);
            exit;
        }

    } elseif ($acao === "verificar_sessao") {
        // Lógica para verificar sessão...
        if (isset($_SESSION["usuario_logado"]) && !empty($_SESSION["usuario_logado"]["id"])) {
            echo json_encode(["logado" => true, "usuario" => $_SESSION["usuario_logado"]]);
        } else {
            echo json_encode(["logado" => false]);
        }
    } else {
        // Ação inválida
        echo json_encode(["sucesso" => false, "mensagem" => "Ação inválida."]);
    }
} else {
    // Método inválido
    echo json_encode(["sucesso" => false, "mensagem" => "Método não permitido. Use POST."]);
}
?>