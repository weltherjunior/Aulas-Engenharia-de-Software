<?php
// ============================================================
// editar.php — Formulário de edição de paciente
// ✏️  TAREFA 5: Complete as partes marcadas com TODO
// ============================================================
require_once 'auth.php';
require_once 'conexao.php';
require_once '_layout.php';
verificarAcesso();

// TODO 1: Receba o id do paciente via GET
$id = 0; // substitua por intval($_GET['id'])


// TODO 2: Busque o paciente no banco pelo id

// Use: prepare → bindValue → execute → fetch(PDO::FETCH_ASSOC)
// Se não encontrado: exiba erro e encerre com exit
$pac = null; // substitua pela busca real




cabecalho('Editar Paciente');
?>

<div class="card">
  <h2>Editar Paciente</h2>

  <form action="atualizar.php" method="POST">

    <!-- TODO 3: Adicione um campo hidden com o id do paciente -->
    <!-- <input type="hidden" name="id" value="<?= $pac['id'] ?>"> -->


    <div class="form-group">
      <label for="nome">Nome completo <span style="color:red">*</span></label>
      <!-- TODO 4: Preencha o value com o dado do paciente -->
      <input type="text" id="nome" name="nome"
             value=""
             required>
    </div>

    <div class="form-group">
      <label for="cpf">CPF <span style="color:red">*</span></label>
      <!-- TODO 4: Preencha o value com o dado do paciente -->
      <input type="text" id="cpf" name="cpf"
             value=""
             maxlength="14" required>
    </div>

    <div class="form-group">
      <label for="data_nascimento">Data de Nascimento <span style="color:red">*</span></label>
      <!-- TODO 4: Preencha o value com o dado do paciente -->
      <input type="date" id="data_nascimento" name="data_nascimento"
             value=""
             required>
    </div>

    <div class="form-group">
      <label for="telefone">Telefone <span style="color:#888">(opcional)</span></label>
      <!-- TODO 4: Preencha o value com o dado do paciente -->
      <input type="text" id="telefone" name="telefone"
             value="">
    </div>

    <div class="form-group">
      <label for="convenio">Convênio <span style="color:#888">(opcional)</span></label>
      <!-- TODO 4: Preencha o value com o dado do paciente -->
      <input type="text" id="convenio" name="convenio"
             value="">
    </div>

    <div class="gap mt-2">
      <button type="submit" class="btn btn-warning">Salvar Alterações</button>
      <a href="index.php" class="btn btn-primary">Cancelar</a>
    </div>

  </form>
</div>

<?php rodape(); ?>
