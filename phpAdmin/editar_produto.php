<?php
require '../conexao_produtos.php';

$id = $_GET['id'] ?? null;
if (!$id) {
    header('Location: produtos_admin.php');
    exit;
}

$produto = $pdo->prepare("SELECT * FROM peca WHERE id = ?");
$produto->execute([$id]);
$produto = $produto->fetch();

if (!$produto) {
    header('Location: produtos_admin.php');
    exit;
}

$categorias = $pdo->query("SELECT id, nome_categoria FROM categoria")->fetchAll();
$generos = $pdo->query("SELECT id, nome_genero FROM genero")->fetchAll();

include '../administrador/HTML_editar_produto.php';
?>