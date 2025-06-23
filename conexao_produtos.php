<?php
$host = 'localhost';
$db = 'produtos';
$user = 'root';
$pass = '';
    
    try {
        $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user,$pass);
        } catch (PDOException $e) {
            echo "Erro na conexão: " . $e->getMessage();
            exit;
        }
