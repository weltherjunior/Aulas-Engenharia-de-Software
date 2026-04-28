<?php

/**
 * public/cadastro.php
 * Cadastro de novo usuário com validação no servidor.
 */

session_start();
require_once '../config/database.php';
require_once '../src/auth.php';
verificarAcesso('admin');

$erros  = [];
$nome   = '';
$email  = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    //validarCsrf();

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

    if($senha!=""){

        if (strlen($senha) < 8) {
            $erros[] = 'A senha deve ter pelo menos 8 caracteres.';
        }

        if ($senha !== $conf) {
            $erros[] = 'As senhas não conferem.';
        }
    }

    if (empty($erros)) {
        $pdo = getConexao();

        // Verifica e-mail duplicado
       
           if($senha!=""){
                $hash = password_hash($senha, PASSWORD_DEFAULT);
            }
            $stmt = $pdo->prepare(
                'UPDATE usuarios SET 
                nome = :nome, email = :email' . ($senha != "" ? ', senha = :senha' : '') . ' 
                WHERE id = :id'
            );
            $stmt->bindValue(':nome', $nome, PDO::PARAM_STR);
            $stmt->bindValue(':email', $email, PDO::PARAM_STR);
            if($senha!=""){
                $stmt->bindValue(':senha', $hash, PDO::PARAM_STR);
            }
            $stmt->bindValue(':id', $_POST['id'], PDO::PARAM_INT);
            $stmt->execute();

            header('Location: admin.php?cadastro=ok');
            exit;
       
    }
}else{

//Buscar usuario no banco ataves do ID
    $pdo = getConexao();
    $buscar = $pdo->prepare('SELECT * FROM usuarios WHERE id = :id');
    $buscar->bindValue(':id', $_GET['id'], PDO::PARAM_INT);
    $buscar->execute();
    $usuario = $buscar->fetch(PDO::FETCH_ASSOC);

    $nome = $usuario['nome'];
    $email = $usuario['email'];


    //print_r($usuario);

}

// consulta dados do usuario
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
        <h1>Editar conta</h1>

        <?php if (!empty($erros)): ?>
            <div class="alerta erro">
                <ul>
                    <?php foreach ($erros as $erro): ?>
                        <li><?= e($erro) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <form method="POST" action="editar_usuario.php">
            <input type="hidden" name="csrf_token" value="<?= gerarTokenCsrf() ?>">
            <input type="hidden" name="id" value="<?= e($usuario['id']) ?>">    
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
                <input type="password" id="senha" name="senha"  autocomplete="new-password">
            </div>
            
            <div class="campo">
                <label for="confirmar">Confirmar senha</label>
                <input type="password" id="confirmar" name="confirmar"  autocomplete="new-password">
            </div>
            
            <div class="campo">
                <select name="perfil" id="perfil" required>
                    <option value="">Selecione o perfil</option>
                    <option value="admin" <?= $usuario['perfil'] === 'admin' ? 'selected' : '' ?>>Admin</option>
                    <option value="usuario" <?= $usuario['perfil'] === 'usuario' ? 'selected' : '' ?>>Usuário</option>
                </select>
            </div>
                

            


            <button type="submit" class="btn-primario">Cadastrar</button>
        </form>

        <p class="link-alt">Já tem conta? <a href="login.php">Entrar</a></p>
    </div>
</div>
</body>
</html>
