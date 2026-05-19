<?php

/**
 * public/index.php
 * Listagem de contatos com telefones — com paginação.
 */

require_once '../config/database.php';
require_once '../src/contatos.php';

$pdo     = getConexao();
$pagina  = max(1, (int)($_GET['pagina'] ?? 1));
$result  = listarContatosPaginados($pdo, $pagina, porPagina: 3); // 3 por página para demo

$contatos     = $result['contatos'];
$totalPaginas = $result['totalPaginas'];
$paginaAtual  = $result['paginaAtual'];
$total        = $result['total'];
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agenda de Contatos</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
<div class="container">

    <div class="topo">
        <h1>Agenda de Contatos</h1>
        <a href="cadastro.php" class="btn-primario">+ Novo contato</a>
    </div>

    <p class="meta"><?= $total ?> contato(s) cadastrado(s) — página <?= $paginaAtual ?> de <?= $totalPaginas ?></p>

    <?php if (isset($_GET['msg'])): ?>
        <div class="alerta sucesso"><?= htmlspecialchars($_GET['msg']) ?></div>
    <?php endif; ?>

    <?php if (empty($contatos)): ?>
        <p class="vazio">Nenhum contato cadastrado ainda.</p>
    <?php endif; ?>

    <?php foreach ($contatos as $contato):
        // Para cada contato da página, busca os telefones via JOIN
        $detalhe = buscarContatoPorId($pdo, $contato['id']);
    ?>
    <div class="card-contato">
        <div class="contato-header">
            <div>
                <strong class="nome"><?= htmlspecialchars($contato['nome']) ?></strong>
                <span class="email"><?= htmlspecialchars($contato['email']) ?></span>
            </div>
            <div class="acoes">
                <a href="detalhe.php?id=<?= $contato['id'] ?>">Ver</a>
                <a href="excluir.php?id=<?= $contato['id'] ?>"
                   class="excluir"
                   onclick="return confirm('Excluir <?= htmlspecialchars($contato['nome']) ?>?')">Excluir</a>
            </div>
        </div>

        <div class="telefones">
            <?php if (empty($detalhe['telefones'])): ?>
                <span class="sem-tel">Sem telefone</span>
            <?php else: ?>
                <?php foreach ($detalhe['telefones'] as $tel): ?>
                    <span class="telefone">
                        <span class="badge-tipo"><?= htmlspecialchars($tel['tipo']) ?></span>
                        <?= htmlspecialchars($tel['numero']) ?>
                    </span>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
    <?php endforeach; ?>

    <!-- Paginação -->
    <?php if ($totalPaginas > 1): ?>
    <nav class="paginacao">
        <?php if ($paginaAtual > 1): ?>
            <a href="?pagina=<?= $paginaAtual - 1 ?>">← Anterior</a>
        <?php endif; ?>

        <?php for ($i = 1; $i <= $totalPaginas; $i++): ?>
            <a href="?pagina=<?= $i ?>"
               class="<?= $i === $paginaAtual ? 'ativa' : '' ?>">
                <?= $i ?>
            </a>
        <?php endfor; ?>

        <?php if ($paginaAtual < $totalPaginas): ?>
            <a href="?pagina=<?= $paginaAtual + 1 ?>">Próxima →</a>
        <?php endif; ?>
    </nav>
    <?php endif; ?>

</div>
</body>
</html>
