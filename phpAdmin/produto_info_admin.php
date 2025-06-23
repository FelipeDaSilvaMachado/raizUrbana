<?php
require '../conexao_produtos.php';

// Pega o ID da URL
$id = $_GET['id'] ?? null;

// Verifica se o ID existe
if (!$id) {
    die('ID do produto não fornecido.');
}

$sql = "SELECT p.*, c.nome_categoria, g.nome_genero
        FROM peca p
        JOIN categoria c ON c.id = p.id_categoria
        JOIN genero g ON g.id = p.id_genero
        WHERE p.id = :id";

$stmt = $pdo->prepare($sql);
$stmt->bindParam(':id', $id, PDO::PARAM_INT);
$stmt->execute();
$produto = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$produto) {
    die('Produto não encontrado.');
}

include '../administrador/HTML_produto_info_admin.php';

?>