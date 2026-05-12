<?php
// ============================================================
// cadastrar.php — Formulário de cadastro de paciente
// ✔ ARQUIVO JÁ PRONTO — não altere este arquivo
// ============================================================
require_once 'auth.php';
require_once '_layout.php';
verificarAcesso();

cabecalho('Cadastrar Paciente');
?>

<div class="card">
  <h2>Cadastrar Novo Paciente</h2>

  <!-- Exibe erros vindos do salvar.php via query string (opcional) -->
  <?php if (!empty($_GET['erro'])): ?>
    <div class="alert alert-danger">
      <?= htmlspecialchars($_GET['erro'], ENT_QUOTES, 'UTF-8') ?>
    </div>
  <?php endif; ?>

  <form action="salvar.php" method="POST">

    <div class="form-group">
      <label for="nome">Nome completo <span style="color:red">*</span></label>
      <input type="text" id="nome" name="nome" placeholder="Ex: Maria da Silva" required>
    </div>

    <div class="form-group">
      <label for="cpf">CPF <span style="color:red">*</span></label>
      <input type="text" id="cpf" name="cpf" placeholder="000.000.000-00"
             maxlength="14" required>
    </div>

    <div class="form-group">
      <label for="data_nascimento">Data de Nascimento <span style="color:red">*</span></label>
      <input type="date" id="data_nascimento" name="data_nascimento" required>
    </div>

    <div class="form-group">
      <label for="telefone">Telefone <span style="color:#888">(opcional)</span></label>
      <input type="text" id="telefone" name="telefone" placeholder="(00) 00000-0000">
    </div>

    <div class="form-group">
      <label for="convenio">Convênio <span style="color:#888">(opcional)</span></label>
      <input type="text" id="convenio" name="convenio" placeholder="Ex: Unimed, SulAmérica...">
    </div>

    <div class="gap mt-2">
      <button type="submit" class="btn btn-success">Salvar Paciente</button>
      <a href="index.php" class="btn btn-primary">Cancelar</a>
    </div>

  </form>
</div>

<?php rodape(); ?>
