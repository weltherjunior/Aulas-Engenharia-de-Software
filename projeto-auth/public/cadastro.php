<?php

/**
 * public/cadastro.php
 * Cadastro de novo usuário com validação no servidor.
 */

session_start();
require_once '../config/database.php';
require_once '../src/auth.php';

// Redireciona se já estiver logado
if (isset($_SESSION['usuario_id'])) {
    header('Location: index.php');
    exit;
}

$erros  = [];
$nome   = '';
$email  = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    validarCsrf();

    $nome  = trim($_POST['nome']  ?? '');
    $email = trim($_POST['email'] ?? '');
    $senha = $_POST['senha']      ?? '';
    $conf  = $_POST['confirmar']  ?? '';

    // Validações
    if (empty($nome)) {
        $erros[] = 'O nome é obrigatório.';
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $erros[] = 'Informe um e-mail válido.';
    }

    if (strlen($senha) < 8) {
        $erros[] = 'A senha deve ter pelo menos 8 caracteres.';
    }

    if ($senha !== $conf) {
        $erros[] = 'As senhas não conferem.';
    }

    if (empty($erros)) {
        $pdo = getConexao();

        // Verifica e-mail duplicado
        $check = $pdo->prepare('SELECT id FROM usuarios WHERE email = ?');
        $check->execute([$email]);

        if ($check->fetch()) {
            $erros[] = 'Este e-mail já está cadastrado.';
        } else {
            $hash = password_hash($senha, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare(
                'INSERT INTO usuarios (nome, email, senha) VALUES (?, ?, ?)'
            );
            $stmt->execute([$nome, $email, $hash]);

            header('Location: login.php?cadastro=ok');
            exit;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
<div class="container">
    <div class="card">
        <h1>Criar conta</h1>

        <?php if (!empty($erros)): ?>
            <div class="alerta erro">
                <ul>
                    <?php foreach ($erros as $erro): ?>
                        <li><?= e($erro) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <form method="POST" action="cadastro.php">
            <input type="hidden" name="csrf_token" value="<?= gerarTokenCsrf() ?>">

            <div class="campo">
                <label for="nome">Nome completo</label>
                <input type="text" id="nome" name="nome"
                       value="<?= e($nome) ?>" required autocomplete="name">
            </div>

            <div class="campo">
                <label for="email">E-mail</label>
                <input type="email" id="email" name="email"
                       value="<?= e($email) ?>" required autocomplete="email">
            </div>

            <div class="campo">
                <label for="senha">Senha <small>(mínimo 8 caracteres)</small></label>
                <input type="password" id="senha" name="senha" required autocomplete="new-password">
            </div>

            <div class="campo">
                <label for="confirmar">Confirmar senha</label>
                <input type="password" id="confirmar" name="confirmar" required autocomplete="new-password">
            </div>

            <button type="submit" class="btn-primario">Cadastrar</button>
        </form>

        <p class="link-alt">Já tem conta? <a href="login.php">Entrar</a></p>
    </div>
</div>
</body>
</html>
