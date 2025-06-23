<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Holtwood+One+SC&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/4.6.0/remixicon.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../cssUsuarios/modaFeminina.css">
    <title>Moda Feminina - Raiz Urbana</title>
</head>
<body>
    <div class="header">
        <div class="cabeçalho">
            <h1>RAIZ</h1>
            <img src="../imagens/logo.png" class="logo">
            <h1>URBANA</h1>

        <div class="social-icons">
          <a href="https://br.linkedin.com/" target="_blank"><img src="../imagens/linkedin.png" alt="LinkedIn" /></a>
          <a href="https://www.facebook.com/?locale=pt_BR" target="_blank"><img src="../imagens/facebook.png" alt="Facebook"/></a>
          <a href="https://x.com/" target="_blank"><img src="../imagens/X.png" alt="X"/></a>
          <a href="https://www.instagram.com/" target="_blank"><img src="../imagens/instagram.png" alt="Instagram" /></a>
        </div>

        </div>
        <nav class="navegação">
            <div class="menu-centro">
                <ul>
                    <li><a href="../index.html">HOME</a></li>
                    <li>
                        <a href="produtos.php">VESTUÁRIO</a>
                    <ul class="dropdown">
                        <li><a href="modaMasculina.php">MASCULINO</a></li>
                    </ul>
                    </li>
                    <li><a href="../usuario/sobreNosQuemSomos.html">SOBRE NÓS</a></li>
                    <li><a href="cadLogUsuarios.html">CADASTRO/LOGIN</a></li>
                </ul>
            </div>
      
            <div class="carrinho">
                <a href="#"><img src="../imagens/icon-carrinho.png" alt="Carrinho" /></a>
            </div>
        </nav>
    </div>
    <br><br><br><br>
    <h1 class="titulo">MODA FEMININA</h1>

<!--------------------------------------filtro-------------------------------------------------------->
<form method="get" class="filtro">
    <label>Categoria:</label>
    <select name="categoria">
        <option value="">Todas</option>
        <?php foreach ($categorias as $cat): ?>
            <option value="<?= $cat['id'] ?>"
                <?= ($filtro_categoria == $cat['id']) ? 'selected' : '' ?>>
                <?= htmlspecialchars($cat['nome_categoria']) ?>
            </option>
        <?php endforeach; ?>
    </select>
    <button type="submit">Buscar</button>
</form>
<!-----------------------------------------filtro----------------------------------------------------->

    <main>
    <div class="container">
        <div class="card_container">
            <?php foreach ($produtos as $p): ?>
            <article class="card_article">
                <img src="../<?= htmlspecialchars($p['imagem']) ?>" alt="<?= htmlspecialchars($p['nome_peca']) ?>" class="card_img">
                <div class="card_data">
                    <h3 class="title"><?= htmlspecialchars($p['nome_peca']) ?></h3>
                    <span class="card_price">R$ <?= number_format($p['preco'], 2, ',', '.') ?></span>
                </div>
                <img src="../imagens/fundo.jpg" alt="fundo" class="card_bg">
                <a href="produto_info.php?id=<?= $p['id'] ?>" class="card_button">
                    Mais Informações <i class="ri-shopping-cart-fill"></i>
                </a>
            </article>
            <?php endforeach; ?>
        </div>
    </div>
</main>

    <main>
    <div class="container">
        <?php if (empty($produtos)): ?>
            <div class="no-products">
                <p>Não há produtos disponíveis nesta categoria.</p>
                <a href="modaFeminina.php" class="back-link">Voltar para todos os produtos</a>
            </div>
        <?php else: ?>
            <div class="card_container">
                <?php foreach ($produtos as $p): ?>
                <!-- código atual de cards aqui -->
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</main>

    <footer>
        <div class="conteudo_rodape">
            <div class="footer_sobre">
                <h1>SOBRE</h1>
                <a href="#" class="link-sobre">quem somos</a><br>
                <h5 class="footer-contato">onde nos encontrar</h5>
                
            </div>
            <ul class="footer-list">
                <li><h3>meus dados</h3></li>
                <li><a href="#" class="footer-link">minha conta</a></li>
                <li><a href="#" class="footer-link">meus pedidos</a></li>
            </ul>
            <ul class="footer-list">
                <li><h3>fale conosco</h3></li>
                <li><h5 class="footer-contato">SAC: WHATSAPP: (11)99999-9999</h5></li>
                <li>
                    <h5 class="footer-contato">HORÁRIOS DE ATENDIMENTO</h5>
                    <h5 class="footer-contato">SEG A SEX. DAS 09 ÀS 18H</h5>
                </li>
            </ul>
        </div>
        <div id="footer_copyright">
            &#169; todos os direitos reservados a Raiz Urbana
        </div>
    </footer>
</body>
</html>
