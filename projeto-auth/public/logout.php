<?php

/**
 * public/logout.php
 * Encerra a sessão do usuário de forma segura.
 */

session_start();

// 1. Remove todas as variáveis de sessão
session_unset();

// 2. Destrói a sessão no servidor
session_destroy();

// 3. Apaga o cookie de sessão no navegador
if (ini_get('session.use_cookies')) {
    $params = session_get_cookie_params();
    setcookie(
        session_name(),
        '',
        time() - 42000,
        $params['path'],
        $params['domain'],
        $params['secure'],
        $params['httponly']
    );
}

header('Location: login.php');
exit;
