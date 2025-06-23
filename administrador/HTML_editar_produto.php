
<!DOCTYPE html>
<html>
    <link rel="stylesheet" href="../cssAdmin/editar_produto.css">
<body>
<h1>Editar <?php echo htmlspecialchars($produto['nome_peca']); ?></h1>

<form action="salvar_edicao.php" method="post">
<input type="hidden" name="id" value="<?php echo $id; ?>">

<label>Categoria:</label><br>
<select name="id_categoria"><br>
<?php foreach ($categorias as $categoria): ?>
    <option value="<?php echo $categoria['id']; ?>" <?php echo $categoria['id'] == $produto['id_categoria'] ? 'selected' : ''; ?>>
        <?php echo htmlspecialchars($categoria['nome_categoria']); ?>
    </option>
    <?php endforeach; ?>
</select><br>

<label>Nome da peça:</label><br>
<input type="text" name="nome_peca" value="<?php echo htmlspecialchars($produto['nome_peca']); ?>"><br>

<label>Gênero:</label><br>
<select name="id_genero"><br>
<?php foreach ($generos as $genero): ?>
    <option value="<?php echo $genero['id']; ?>" <?php echo $genero['id'] == $produto['id_genero'] ? 'selected' : ''; ?>>
        <?php echo htmlspecialchars($genero['nome_genero']); ?>
    </option>
    <?php endforeach; ?>
</select><br>

<label>Cor:</label><br>
<input type="text" name="cor" value="<?php echo htmlspecialchars($produto['cor']); ?>"><br>

<label>Preço:</label><br>
<input type="text" name="preco" value="<?php echo htmlspecialchars($produto['preco']); ?>"><br>

<label>Descrição:</label><br>
<textarea name="descricao"><?php echo htmlspecialchars($produto['descricao']); ?></textarea><br>

<input type="submit" value="Salvar">
<a href="produto_info_admin.php?id=<?php echo $id; ?>">
    <button>Cancelar</button></a>
</form>
</body>
</html>