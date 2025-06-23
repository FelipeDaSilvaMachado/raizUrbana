<?php
require '../conexao_produtos.php';

// Verifica se foi enviado um filtro de categoria
$filtro_categoria = isset($_GET['categoria']) ? (int)$_GET['categoria'] : null;

// Consulta para obter todas as categorias (para o dropdown)
$sql_categorias = "SELECT id, nome_categoria FROM categoria";
$stmt_categorias = $pdo->prepare($sql_categorias);
$stmt_categorias->execute();
$categorias = $stmt_categorias->fetchAll(PDO::FETCH_ASSOC);

// Consulta principal com filtros
$sql = "SELECT p.id, p.nome_peca, p.cor, p.preco, g.nome_genero, p.imagem, c.nome_categoria
        FROM peca p
        JOIN genero g ON g.id = p.id_genero
        JOIN categoria c ON c.id = p.id_categoria
        WHERE (g.nome_genero = 'Masculino' OR g.nome_genero = 'Unissex')";

// Adiciona filtro de categoria se foi selecionado
if ($filtro_categoria) {
    $sql .= " AND c.id = :categoria_id";
}

$stmt = $pdo->prepare($sql);

// Se houver filtro de categoria, vincula o parâmetro
if ($filtro_categoria) {
    $stmt->bindParam(':categoria_id', $filtro_categoria, PDO::PARAM_INT);
}

$stmt->execute();
$produtos = $stmt->fetchAll(PDO::FETCH_ASSOC);

include '../usuario/HTML_modaMaculina.php';

?>
