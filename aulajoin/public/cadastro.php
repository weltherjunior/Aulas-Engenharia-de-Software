<?php

/**
 * public/cadastro.php
 * Formulário de cadastro de contato com múltiplos telefones.
 * Demonstra: inserção em duas tabelas + transação PDO.
 */

require_once '../config/database.php';
require_once '../src/contatos.php';

$erros = [];
$nome  = '';
$email = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome  = trim($_POST['nome']  ?? '');
    $email = trim($_POST['email'] ?? '');

    // Telefones: cada linha do formulário é um par tipo+numero
    $telefones = [];
    $numeros   = $_POST['numero'] ?? [];
    $tipos     = $_POST['tipo']   ?? [];

    foreach ($numeros as $i => $numero) {
        if (!empty(trim($numero))) {
            $telefones[] = [
                'tipo'   => $tipos[$i] ?? 'celular',
                'numero' => trim($numero),
            ];
        }
    }

    // Validação
    if (empty($nome)) {
        $erros[] = 'O nome é obrigatório.';
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $erros[] = 'Informe um e-mail válido.';
    }

    if (empty($erros)) {
        $pdo = getConexao();

        // Verifica duplicidade de e-mail
        $check = $pdo->prepare('SELECT id FROM contatos WHERE email = ?');
        $check->execute([$email]);
        if ($check->fetch()) {
            $erros[] = 'Este e-mail já está cadastrado.';
        } else {
            try {
                inserirContato($pdo, ['nome' => $nome, 'email' => $email], $telefones);
                header('Location: index.php?msg=Contato+cadastrado+com+sucesso!');
                exit;
            } catch (Exception $e) {
                $erros[] = 'Erro ao salvar: ' . $e->getMessage();
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Novo Contato</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
<div class="container">
    <div class="topo">
        <h1>Novo Contato</h1>
        <a href="index.php">← Voltar</a>
    </div>

    <?php if (!empty($erros)): ?>
        <div class="alerta erro">
            <ul>
                <?php foreach ($erros as $e): ?>
                    <li><?= htmlspecialchars($e) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <div class="card-contato">
        <form method="POST" action="cadastro.php">

            <div class="campo">
                <label for="nome">Nome completo</label>
                <input type="text" id="nome" name="nome"
                       value="<?= htmlspecialchars($nome) ?>" required>
            </div>

            <div class="campo">
                <label for="email">E-mail</label>
                <input type="email" id="email" name="email"
                       value="<?= htmlspecialchars($email) ?>" required>
            </div>

            <hr>
            <h3>Telefones</h3>
            <p class="meta">Você pode adicionar quantos telefones quiser.</p>

            <!-- Linhas de telefone (dinâmicas via JS) -->
            <div id="telefones-container">
                <div class="linha-telefone">
                    <select name="tipo[]">
                        <option value="celular">Celular</option>
                        <option value="fixo">Fixo</option>
                        <option value="trabalho">Trabalho</option>
                    </select>
                    <input type="text" name="numero[]" placeholder="(XX) XXXXX-XXXX">
                    <button type="button" class="btn-remover" onclick="removerLinha(this)">✕</button>
                </div>
            </div>

            <button type="button" class="btn-secundario" onclick="adicionarTelefone()">
                + Adicionar telefone
            </button>

            <hr>
            <button type="submit" class="btn-primario">Salvar contato</button>
        </form>
    </div>
</div>

<script>
function adicionarTelefone() {
    const container = document.getElementById('telefones-container');
    const div = document.createElement('div');
    div.className = 'linha-telefone';
    div.innerHTML = `
        <select name="tipo[]">
            <option value="celular">Celular</option>
            <option value="fixo">Fixo</option>
            <option value="trabalho">Trabalho</option>
        </select>
        <input type="text" name="numero[]" placeholder="(XX) XXXXX-XXXX">
        <button type="button" class="btn-remover" onclick="removerLinha(this)">✕</button>
    `;
    container.appendChild(div);
}

function removerLinha(btn) {
    const container = document.getElementById('telefones-container');
    if (container.children.length > 1) {
        btn.parentElement.remove();
    }
}
</script>
</body>
</html>
