<?php
$host = 'localhost';
$raizurbanabd = 'raizurbanabd';
$usuario = 'root';
// $email = '';
$senha = '';
$porta = 3307; // Porta correta configurada no MySQL

try {
    $pdo = new PDO("mysql:host=$host;port=$porta;dbname=$raizurbanabd;charset=utf8", $usuario, $senha);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erro na conexao com o banco: " . $e->getMessage());
    exit;
}