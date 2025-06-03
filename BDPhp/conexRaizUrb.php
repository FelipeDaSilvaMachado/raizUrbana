<?php
$host = 'localhost';
$raizurbanabd = 'raizurbanabd';
$usuario = 'root';
$senha = '';
$porta = 3307; // Porta configurada no MySQL

try {
    $pdo = new PDO("mysql:host=$host;port=$porta;dbname=$raizurbanabd;charset=utf8", $usuario, $senha);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // echo "Conexão bem-sucedida!"; // Comentado para não interferir no JSON
} catch (PDOException $e) {
    // Em produção, logar o erro em vez de exibir diretamente
    error_log("Erro na conexao com o banco: " . $e->getMessage()); 
    // Retornar um erro genérico para o cliente em caso de falha na conexão
    // Para uma API, seria melhor retornar um JSON de erro aqui também
    // Mas como o script principal já faz isso, podemos manter o die() por enquanto
    // No entanto, o die() também imprime texto, o que pode causar problemas se este arquivo for incluído antes do header JSON.
    // Vamos modificar para garantir que nada seja impresso em caso de erro aqui, o script principal tratará.
    // die("Erro ao conectar ao banco de dados. Tente novamente mais tarde."); 
    // Em vez de die(), apenas definimos $pdo como null ou lançamos a exceção para o script principal tratar
     $pdo = null; // Indica falha na conexão
     // Ou: throw $e; // Re-lança a exceção para ser capturada no script principal
}
?>