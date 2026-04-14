<?php

/**
 * public/admin.php
 * Painel do administrador — somente perfil 'admin' pode acessar.
 */

session_start();
require_once '../config/database.php';
require_once '../src/auth.php';

verificarAcesso('admin'); // bloqueia qualquer um que não seja admin

// Lista todos os usuários cadastrados
$pdo   = getConexao();
$stmt  = $pdo->query('SELECT id, nome, email, perfil, criado_em FROM usuarios ORDER BY criado_em DESC');
$usuarios = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel Admin</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
<div class="container pagina">

    <header class="topo">
        <h1>Painel do Administrador</h1>
        <div class="topo-usuario">
            Olá, <strong><?= e($_SESSION['usuario_nome']) ?></strong>
            &nbsp;|&nbsp;
            <a href="index.php">Início</a>
            &nbsp;|&nbsp;
            <a href="logout.php">Sair</a>
        </div>
    </header>

    <main>
        <div class="card">
            <h2>Usuários cadastrados</h2>

            <?php if (empty($usuarios)): ?>
                <p>Nenhum usuário encontrado.</p>
            <?php else: ?>
                <table class="tabela">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Nome</th>
                            <th>E-mail</th>
                            <th>Perfil</th>
                            <th>Cadastrado em</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($usuarios as $u): ?>
                            <tr>
                                <td><?= e($u['id']) ?></td>
                                <td><?= e($u['nome']) ?></td>
                                <td><?= e($u['email']) ?></td>
                                <td><span class="badge"><?= e($u['perfil']) ?></span></td>
                                <td><?= e($u['criado_em']) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
    </main>

</div>
</body>
</html>
