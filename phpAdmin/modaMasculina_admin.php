<?php
require '../conexao_produtos.php';

// Processa filtro
$filtro_categoria = $_GET['categoria'] ?? null;

// Busca categorias
$categorias = $pdo->query("SELECT id, nome_categoria FROM categoria")->fetchAll();

// Consulta produtos
$sql = "SELECT p.*, g.nome_genero, c.nome_categoria 
        FROM peca p
        JOIN genero g ON g.id = p.id_genero
        JOIN categoria c ON c.id = p.id_categoria
        WHERE g.nome_genero IN ('Masculino', 'Unissex')" . 
        ($filtro_categoria ? " AND c.id = " . (int)$filtro_categoria : "");

$produtos = $pdo->query($sql)->fetchAll();

// Inclui o HTML
include '../administrador/HTML_modaMasculina_admin.php';
?>