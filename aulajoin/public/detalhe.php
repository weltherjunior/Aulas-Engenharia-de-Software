<?php

/**
 * public/detalhe.php
 * Exibe os detalhes completos de um contato com todos os telefones.
 * Demonstra: buscarContatoPorId() com JOIN e agrupamento.
 */

require_once '../config/database.php';
require_once '../src/contatos.php';

$id      = (int)($_GET['id'] ?? 0);
$pdo     = getConexao();
$contato = buscarContatoPorId($pdo, $id);

if ($contato === null) {
    header('Location: index.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($contato['nome']) ?></title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
<div class="container">
    <div class="topo">
        <h1>Detalhes do Contato</h1>
        <a href="index.php">← Voltar</a>
    </div>

    <div class="card-contato">
        <p><strong>Nome:</strong> <?= htmlspecialchars($contato['nome']) ?></p>
        <p><strong>E-mail:</strong> <?= htmlspecialchars($contato['email']) ?></p>
        <p><strong>Cadastrado em:</strong> <?= htmlspecialchars($contato['criado_em']) ?></p>

        <hr>
        <h3>Telefones (<?= count($contato['telefones']) ?>)</h3>

        <?php if (empty($contato['telefones'])): ?>
            <p class="sem-tel">Nenhum telefone cadastrado.</p>
        <?php else: ?>
            <table class="tabela">
                <thead>
                    <tr><th>#</th><th>Tipo</th><th>Número</th></tr>
                </thead>
                <tbody>
                    <?php foreach ($contato['telefones'] as $i => $tel): ?>
                    <tr>
                        <td><?= $i + 1 ?></td>
                        <td><?= htmlspecialchars($tel['tipo']) ?></td>
                        <td><?= htmlspecialchars($tel['numero']) ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</div>
</body>
</html>
