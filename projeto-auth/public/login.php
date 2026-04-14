<?php

/**
 * public/login.php
 * Autenticação de usuário com verificação de senha (password_verify).
 */

session_start();
require_once '../config/database.php';
require_once '../src/auth.php';

// Redireciona se já estiver logado
if (isset($_SESSION['usuario_id'])) {
    header('Location: index.php');
    exit;
}

$erro  = '';
$email = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    validarCsrf();

    $email = trim($_POST['email'] ?? '');
    $senha = $_POST['senha']      ?? '';

    if (empty($email) || empty($senha)) {
        $erro = 'Preencha e-mail e senha.';
    } else {
        $pdo  = getConexao();
        $stmt = $pdo->prepare('SELECT * FROM usuarios WHERE email = ?');
        $stmt->execute([$email]);
        $usuario = $stmt->fetch();

        if ($usuario && password_verify($senha, $usuario['senha'])) {
            // Regenera o ID de sessão para prevenir session fixation
            session_regenerate_id(true);

            $_SESSION['usuario_id']     = $usuario['id'];
            $_SESSION['usuario_nome']   = $usuario['nome'];
            $_SESSION['usuario_perfil'] = $usuario['perfil'];

            header('Location: index.php');
            exit;
        }

        $erro = 'E-mail ou senha incorretos.';
    }
}

$cadastroOk = isset($_GET['cadastro']) && $_GET['cadastro'] === 'ok';
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
<div class="container">
    <div class="card">
        <h1>Entrar</h1>

        <?php if ($cadastroOk): ?>
            <div class="alerta sucesso">Cadastro realizado! Faça login para continuar.</div>
        <?php endif; ?>

        <?php if ($erro): ?>
            <div class="alerta erro"><?= e($erro) ?></div>
        <?php endif; ?>

        <form method="POST" action="login.php">
            <input type="hidden" name="csrf_token" value="<?= gerarTokenCsrf() ?>">

            <div class="campo">
                <label for="email">E-mail</label>
                <input type="email" id="email" name="email"
                       value="<?= e($email) ?>" required autocomplete="email">
            </div>

            <div class="campo">
                <label for="senha">Senha</label>
                <input type="password" id="senha" name="senha" required autocomplete="current-password">
            </div>

            <button type="submit" class="btn-primario">Entrar</button>
        </form>

        <p class="link-alt">Não tem conta? <a href="cadastro.php">Cadastrar-se</a></p>
    </div>
</div>
</body>
</html>
