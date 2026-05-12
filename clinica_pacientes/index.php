<?php
// ============================================================
// index.php — Listagem de pacientes
// ✏️  TAREFA 3: Complete as partes marcadas com TODO
// ============================================================
require_once 'auth.php';
require_once 'conexao.php';
require_once '_layout.php';

// TODO 1: Chame verificarAcesso() para proteger esta página



// TODO 2: Busque todos os pacientes do banco de dados
// Dica: SELECT * FROM pacientes ORDER BY nome ASC
// Use: prepare → execute → fetchAll(PDO::FETCH_ASSOC)
$pacientes = []; // substitua esta linha pela busca real



cabecalho('Pacientes');
?>

<div class="card">
  <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:20px;">
    <h2>Lista de Pacientes</h2>
    <a href="cadastrar.php" class="btn btn-success">+ Cadastrar Paciente</a>
  </div>

  <?php if (empty($pacientes)): ?>
    <p style="color:#888; text-align:center; padding:30px 0;">Nenhum paciente cadastrado.</p>
  <?php else: ?>
    <table>
      <thead>
        <tr>
          <th>Nome</th>
          <th>CPF</th>
          <th>Data Nasc.</th>
          <th>Telefone</th>
          <th>Convênio</th>
          <th>Ações</th>
        </tr>
      </thead>
      <tbody>
        <!-- TODO 3: Percorra $pacientes com foreach e exiba cada linha -->
        <!-- Exemplo de uma linha (remova este comentário e complete):  -->
        <!--
        <?php foreach ($pacientes as $pac): ?>
          <tr>
            <td><?= e($pac['nome']) ?></td>
            <td><?= e($pac['cpf']) ?></td>
            <td><?= e($pac['data_nascimento']) ?></td>
            <td><?= e($pac['telefone'] ?? '—') ?></td>
            <td><?= e($pac['convenio'] ?? '—') ?></td>
            <td class="gap">
              <a href="editar.php?id=<?= $pac['id'] ?>" class="btn btn-warning">Editar</a>
              <a href="excluir.php?id=<?= $pac['id'] ?>"
                 class="btn btn-danger"
                 onclick="return confirm('Excluir este paciente?')">Excluir</a>
            </td>
          </tr>
        <?php endforeach; ?>
        -->
      </tbody>
    </table>
  <?php endif; ?>
</div>

<?php rodape(); ?>
