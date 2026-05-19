<?php

/**
 * config/database.php
 * Conexão PDO centralizada e reutilizável.
 * Altere as constantes conforme seu ambiente.
 */

define('DB_HOST', 'localhost');
define('DB_NAME', 'agenda_db');
define('DB_USER', 'root');
define('DB_PASS', '');       // <- coloque sua senha aqui
define('DB_CHARSET', 'utf8');

function getConexao(): PDO
{
    // static: mantém a mesma conexão entre chamadas (Singleton simples)
    static $pdo = null;

    if ($pdo === null) {
        $dsn = sprintf(
            'mysql:host=%s;dbname=%s;charset=%s',
            DB_HOST, DB_NAME, DB_CHARSET
        );

        $pdo = new PDO($dsn, DB_USER, DB_PASS, [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,   // lança exceções
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,          // array associativo
            PDO::ATTR_EMULATE_PREPARES   => false,                     // prepared reais
        ]);
    }

    return $pdo;
}
