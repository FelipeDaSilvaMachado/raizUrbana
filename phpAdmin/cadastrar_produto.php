<?php
require '../conexao_produtos.php';

// Buscar dados para os selects
$categorias = $pdo->query("SELECT * FROM categoria")->fetchAll(PDO::FETCH_ASSOC);
$generos = $pdo->query("SELECT * FROM genero")->fetchAll(PDO::FETCH_ASSOC);

// Processar formulário
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Dados básicos
    $nome_peca = $_POST['nome_peca'];
    $id_genero = $_POST['id_genero'];
    $cor = $_POST['cor'];
    $preco = $_POST['preco'];
    $descricao = $_POST['descricao'];
    $id_categoria = $_POST['id_categoria'];

    // Processamento da imagem
    if(isset($_FILES['imagem']) && $_FILES['imagem']['error'] === UPLOAD_ERR_OK) {
        $diretorio_imagens = '../imagens/';
        
        // Verifica/Cria diretório se não existir
        if(!is_dir($diretorio_imagens)) {
            mkdir($diretorio_imagens, 0755, true);
        }
        
        // Gera nome único para a imagem
        $extensao = pathinfo($_FILES['imagem']['name'], PATHINFO_EXTENSION);
        $nome_imagem = uniqid('img_') . '.' . $extensao;
        $caminho_completo = $diretorio_imagens . $nome_imagem;
        
        // Move o arquivo
        if(move_uploaded_file($_FILES['imagem']['tmp_name'], $caminho_completo)) {
            // Insere no banco com caminho relativo
            $caminho_banco = 'imagens/' . $nome_imagem;
            
            $sql = "INSERT INTO peca (nome_peca, id_genero, cor, preco, descricao, imagem, id_categoria)
                    VALUES (:nome_peca, :id_genero, :cor, :preco, :descricao, :imagem, :id_categoria)";
            
            try {
                $stmt = $pdo->prepare($sql);
                $stmt->execute([
                    ':nome_peca' => $nome_peca,
                    ':id_genero' => $id_genero,
                    ':cor' => $cor,
                    ':preco' => $preco,
                    ':descricao' => $descricao,
                    ':imagem' => $caminho_banco,  // Caminho relativo
                    ':id_categoria' => $id_categoria
                ]);
                
                header("Location: cadastrar_produto.php?sucesso=1");
                exit();
            } catch(PDOException $e) {
                header("Location: cadastrar_produto.php?erro=db");
                exit();
            }
        } else {
            header("Location: cadastrar_produto.php?erro=upload");
            exit();
        }
    } else {
        header("Location: cadastrar_produto.php?erro=imagem");
        exit();
    }
}

// Inclui o template HTML
require '../administrador/HTML_cadastrar_produto.php';
?>
