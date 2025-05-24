<?php
$host = 'localhost';
$usuarios = 'raizUrbana';
$usuario = 'root';
// $email = '';
$senha = '';
$porta = 3307; // Porta correta configurada no MySQL

try {
    $pdo = new PDO("mysql:host=$host;port=$porta;dbname=$usuarios;charset=utf8", $nome, $senha);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Erro na conexao com o banco: " . $e->getMessage();
    exit;
}