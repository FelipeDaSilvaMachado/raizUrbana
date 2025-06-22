<?php
require 'conexao_produtos.php';

// Buscar todas as categorias
$categorias = $pdo->query("SELECT * FROM categoria")->fetchAll(PDO::FETCH_ASSOC);$categorias = $pdo->query("SELECT * FROM categoria")->fetchAll(PDO::FETCH_ASSOC);
$generos = $pdo->query("SELECT * FROM genero")->fetchAll(PDO::FETCH_ASSOC);

// Cadastrar peca ao enviar o form
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nome_peca = $_POST['nome_peca'];
    $id_genero = $_POST['id_genero'];
    $cor = $_POST['cor'];
    $preco = $_POST['preco'];
    $descricao = $_POST['descricao'];
    $id_categoria = $_POST['id_categoria'];

    // Processar a imagem
    $imagem = $_FILES['imagem']['name'];
    $caminho_imagem = 'imagens/' . basename($imagem);

if (move_uploaded_file($_FILES['imagem']['tmp_name'], $caminho_imagem)) {
    //Inserir no banco de dados
    if (empty($id_categoria)) {
    die("Selecione uma categoria válida.");
}

    $sql = "INSERT INTO peca (nome_peca, id_genero, cor, preco, descricao, imagem, id_categoria)
            VALUES (:nome_peca, :id_genero, :cor, :preco, :descricao, :imagem, :id_categoria)";
    $stmt = $pdo->prepare($sql);

    $stmt->bindParam(':nome_peca', $nome_peca);
    $stmt->bindParam(':id_genero', $id_genero, PDO::PARAM_INT);
    $stmt->bindParam(':cor', $cor);
    $stmt->bindParam(':preco', $preco);
    $stmt->bindParam(':descricao', $descricao);
    $stmt->bindParam(':imagem', $caminho_imagem);
    $stmt->bindParam(':id_categoria', $id_categoria, PDO::PARAM_INT);
        
        if ($stmt->execute()) {
    header("Location: cadastrar_produto.php?sucesso=peca");
    exit();
    } else {
    header("Location: cadastrar_produto.php?erro=peca");
    exit();
}

    }
    else {
        echo "Falha ao enviar a imagem";
    }
}
?>
<link rel="stylesheet" href="css/cadastrar_produto.css">
   
<header>
    <div class="logo-container">
       <img src="imagens/logo.jpg" alt="Logo" style="max-height: 50px;"> 
    </div>
</header>

<div class="main-container">
    <div class="title-box">
        <h1>Cadastro de Produtos</h1>

        <!-- FORMULÁRIO DENTRO DA DIV DO TÍTULO -->
        <form method="POST" action="cadastrar_produto.php" enctype="multipart/form-data">
            <div class="form-group">
                <label>Nome do Produto</label>
                <input type="text" name="nome_peca" required>
            </div>

            <div class="form-group">
                <label>Descrição</label>
                <input type="text" name="descricao" required>
            </div>

            <div class="form-group">
                <label>Cor do Produto</label>
                <input type="text" name="cor" required>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>Gênero</label>
                    <select name="id_genero" required>
                        <option value="">Selecione</option>
                        <option value="1">Masculino</option>
                        <option value="2">Feminino</option>
                        <option value="3">Unissex</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Categoria</label>
                    <select name="id_categoria" required>
                        <option value="">Selecione</option>
                        <option value="1">Camiseta</option>
                        <option value="2">Camisa</option>
                        <option value="3">Bermuda</option>
                        <option value="4">Shorts</option>
                        <option value="5">Calça</option>
                        <option value="6">Moletom</option>
                        <option value="7">Blusa</option>
                        <option value="8">Jaqueta</option>
                        <option value="9">Calçado</option>
                        <option value="10">Acessório</option>
                    </select>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>Preço (R$)</label>
                    <input type="number" step="0.01" name="preco" required>
                </div>

                <div class="form-group">
                    <label>Imagem do Produto</label>
                    <input type="file" name="imagem" required>
                </div>
            </div>

            <div class="button-row">
                <button type="submit" class="btn-cadastrar">Cadastrar</button>
                <button type="button" class="btn-excluir" onclick="confirmarSaida()">Voltar</button>
            </div>
        </form>
    </div>
</div>

<script>
    function confirmarSaida() {
        if (confirm('Tem certeza que deseja sair? As alterações não salvas serão perdidas.')) {
            window.location.href = 'produtos_admin.php';
        }
    }
</script>
