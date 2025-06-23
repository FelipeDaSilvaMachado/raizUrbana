<?php
require '../conexao_produtos.php';

// Lista categorias para o <select>
$categorias = $pdo->query("SELECT id, nome_categoria FROM categoria")->fetchAll();

// Recebe filtros via GET
$filtro_categoria = $_GET['categoria'] ?? '';

// Base SQL
$sql = "SELECT p.id, p.nome_peca, p.cor, p.preco, g.nome_genero, p.imagem, c.nome_categoria
        FROM peca p
        JOIN genero g ON g.id = p.id_genero
        JOIN categoria c ON c.id = p.id_categoria";

$params = [];

if ($filtro_categoria !== '') {
    $sql .= " WHERE p.id_categoria = ?";
    $params[] = $filtro_categoria;
}

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$produtos = $stmt->fetchAll(PDO::FETCH_ASSOC);

include '../administrador/HTML_produtos_admin.php';
?>