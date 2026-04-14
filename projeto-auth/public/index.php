<?php

/**
 * public/index.php
 * Página inicial — acessível apenas por usuários logados.
 */

session_start();
require_once '../src/auth.php';


verificarAcesso(); // qualquer usuário logado
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Início</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
<div class="container pagina">

    <header class="topo">
        <h1>Sistema de Autenticação PHP</h1>
        <div class="topo-usuario">
            Olá, <strong><?= e($_SESSION['usuario_nome']) ?></strong>
            &nbsp;|&nbsp;
            Perfil: <span class="badge"><?= e($_SESSION['usuario_perfil']) ?></span>
            &nbsp;|&nbsp;
            <a href="logout.php">Sair</a>
        </div>
    </header>

    <main>
        <div class="card">
            <h2>Área geral</h2>
            <p>Esta página é acessível por qualquer usuário autenticado.</p>
            <p>Sua sessão está ativa. O cookie <code>PHPSESSID</code> identifica sua sessão no servidor.</p>
            <p>
            // DEBUG: exibe conteúdo da sessão no início da página    
            <? print_r($_SESSION); ?></p>
            <?php if ($_SESSION['usuario_perfil'] === 'admin'): ?>
                <hr>
                <p><a href="admin.php" class="btn-primario">Acessar painel do administrador →</a></p>
            <?php endif; ?>
        </div>
    </main>

</div>
</body>
</html>
