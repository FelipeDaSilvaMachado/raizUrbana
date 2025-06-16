<?php
$host = 'localhost';
$raizurbanabd = 'raizurbanabd';
$usuario = 'root';
$senha = '';
$porta = 3307; // Porta configurada no MySQL
global $pdo;
try {
    $pdo = new PDO("mysql:host=$host;port=$porta;dbname=$raizurbanabd;charset=utf8", $usuario, $senha);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // echo "Conexão bem-sucedida!";
} catch (PDOException $e) {
    error_log("Erro na conexao com o banco: " . $e->getMessage());
    $pdo = null; // Indica falha na conexão
}
?>