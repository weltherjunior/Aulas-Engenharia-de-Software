<?php
// ============================================================
// conexao.php — Conexão com o banco de dados via PDO
// ✔ ARQUIVO JÁ PRONTO — não altere este arquivo
// ============================================================

$host  = 'localhost';
$banco = 'clinica_db';
$user  = 'root';
$pass  = '';          // altere se seu MySQL tiver senha

try {
    $conn = new PDO(
        "mysql:host=$host;dbname=$banco;charset=utf8mb4",
        $user,
        $pass,
        [
            // Lança exceções em caso de erro SQL
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            // Retorna resultados como array associativo por padrão
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            // Desativa emulação de prepared statements (mais seguro)
            PDO::ATTR_EMULATE_PREPARES   => false,
        ]
    );
} catch (PDOException $e) {
    // Em produção, nunca exiba detalhes do erro para o usuário
    die('Erro ao conectar com o banco de dados. Contate o administrador.');
}
