<?php
require '../conexao_produtos.php';

// Verifica se o ID foi recebido
if (!isset($_GET['id'])) {
    header('Location: produtos_admin.php?erro=id_nao_informado');
    exit;
}

$id = $_GET['id'];

try {
    // 1. Remove o produto da tabela
    $stmt = $pdo->prepare("DELETE FROM peca WHERE id = ?");
    $stmt->execute([$id]);
    
    // 2. Atualiza imediatamente a sessão/cache se estiver usando
    if (session_status() === PHP_SESSION_ACTIVE) {
        if (isset($_SESSION['produtos_lista'])) {
            unset($_SESSION['produtos_lista'][$id]);
        }
    }
    
    // 3. Redireciona com mensagem de sucesso
    header('Location: produtos_admin.php?status=excluido_sucesso');
    exit;

} catch (PDOException $e) {
    header('Location: produtos_admin.php?erro=' . urlencode($e->getMessage()));
    exit;
}