
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title><?php echo htmlspecialchars($produto['nome_peca']); ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Holtwood+One+SC&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/4.6.0/remixicon.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../cssAdmin/produto_info_admin.css">
</head>
<body>

<div class="produto-container">
    <h1><?php echo htmlspecialchars($produto['nome_peca']); ?></h1>

    <img src="../<?php echo htmlspecialchars($produto['imagem']); ?>" alt="Imagem do Produto" class="produto-imagem">

    <p class="info"><strong>Categoria:</strong> <?php echo htmlspecialchars($produto['nome_categoria']); ?></p>
    <p class="info"><strong>Cor:</strong> <?php echo htmlspecialchars($produto['cor']); ?></p>
    <p class="info"><strong>Preço:</strong> R$ <?php echo number_format($produto['preco'], 2, ',', '.'); ?></p>
    <p class="info"><strong>Gênero:</strong> <?php echo htmlspecialchars($produto['nome_genero']); ?></p>
    <p class="info"><strong>Descrição:</strong> <?php echo htmlspecialchars($produto['descricao']); ?></p>

    <div class="botoes">
        <button id="retornar" onclick="window.history.back()">Retornar</button>
        <a href="editar_produto.php?id=<?php echo $produto['id']; ?>"><button>Editar</button></a>
        <a href="excluir_produto.php?id=<?php echo $produto['id']; ?>" onclick="return confirm('Tem certeza que deseja excluir?')">
            <button id="excluir">Excluir</button>
        </a>
    </div>
</div>

</body>
</html>
