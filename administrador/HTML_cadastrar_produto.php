<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Cadastro de Produtos</title>
    <link rel="stylesheet" href="../cssAdmin/cadastrar_produto.css">
</head>
<body>
    <header>
        <div class="logo-container">
            <img src="../imagens/logoMain.png" alt="Logo" style="max-height: 50px;"> 
        </div>
    </header>

    <div class="main-container">
        <div class="title-box">
            <h1>Cadastro de Produtos</h1>

            <?php if (isset($_GET['sucesso'])): ?>
                <div class="alert success">Produto cadastrado com sucesso!</div>
            <?php endif; ?>

            <?php if (isset($_GET['erro'])): ?>
                <div class="alert error">Erro ao cadastrar produto</div>
            <?php endif; ?>

            <form method="POST" enctype="multipart/form-data">
                <div class="form-group">
                    <label>Nome do Produto</label>
                    <input type="text" name="nome_peca" required>
                </div>

                <div class="form-group">
                    <label>Descrição</label>
                    <input type="text" name="descricao" required>
                </div>

                <div class="form-group">
                    <label>Cor</label>
                    <input type="text" name="cor" required>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label>Gênero</label>
                        <select name="id_genero" required>
                            <option value="">Selecione</option>
                            <?php foreach ($generos as $genero): ?>
                                <option value="<?= $genero['id'] ?>"><?= htmlspecialchars($genero['nome_genero']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Categoria</label>
                        <select name="id_categoria" required>
                            <option value="">Selecione</option>
                            <?php foreach ($categorias as $categoria): ?>
                                <option value="<?= $categoria['id'] ?>"><?= htmlspecialchars($categoria['nome_categoria']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label>Preço (R$)</label>
                        <input type="number" step="0.01" name="preco" required>
                    </div>

                    <div class="form-group">
                        <label>Imagem</label>
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
            if (confirm('Tem certeza que deseja sair?')) {
                window.location.href = 'produtos_admin.php';
            }
        }
    </script>
</body>
</html>