<?php
require '../conexao_produtos.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: produtos_admin.php');
    exit;
}

$id = $_POST['id'] ?? null;
if (!$id) {
    header('Location: produtos_admin.php?erro=id_nao_informado');
    exit;
}

try {
    // Prepara os dados para atualização
    $dados = [
        'id' => $id,
        'nome_peca' => $_POST['nome_peca'],
        'id_categoria' => $_POST['id_categoria'],
        'cor' => $_POST['cor'],
        'preco' => $_POST['preco'],
        'id_genero' => $_POST['id_genero'],
        'descricao' => $_POST['descricao']
    ];

    // Atualiza no banco de dados
    $stmt = $pdo->prepare("UPDATE peca SET 
                          nome_peca = :nome_peca,
                          id_categoria = :id_categoria,
                          cor = :cor,
                          preco = :preco,
                          id_genero = :id_genero,
                          descricao = :descricao
                          WHERE id = :id");

    $stmt->execute($dados);
    
    // Redireciona com mensagem de sucesso
    header('Location: produto_info_admin.php?id=' . $id . '&status=editado_sucesso');
    exit;

} catch (PDOException $e) {
    header('Location: editar_produto.php?id=' . $id . '&erro=' . urlencode($e->getMessage()));
    exit;
}