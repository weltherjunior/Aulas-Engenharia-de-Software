<?php
// ============================================================
// atualizar.php — Validação e atualização de paciente
// ✏️  TAREFA 6: Complete as partes marcadas com TODO
// ============================================================
require_once 'conexao.php';
require_once 'auth.php';
require_once '_layout.php';
verificarAcesso();

$erros = [];

// TODO 1: Receba os dados enviados pelo formulário (POST)
// Campos: id, nome, cpf, data_nascimento, telefone, convenio
$id              = 0;  // substitua por intval($_POST['id'])
$nome            = ''; // substitua por $_POST['nome']
$cpf             = ''; // substitua por $_POST['cpf']
$data_nascimento = ''; // substitua por $_POST['data_nascimento']
$telefone        = ''; // substitua por $_POST['telefone']
$convenio        = ''; // substitua por $_POST['convenio']


// TODO 2: Valide os campos obrigatórios (mesmas regras do salvar.php)
// nome: obrigatório — empty()
// cpf: obrigatório — empty()
// data_nascimento: obrigatório — empty()




// TODO 3: Se não houver erros, execute o UPDATE com PDO
// ⚠️  ATENÇÃO: use obrigatoriamente WHERE id = :id !
// SQL: UPDATE pacientes
// Após o UPDATE: redirecione para index.php com header() + exit
if (empty($erros)) {
    // Escreva o UPDATE aqui




}

// Se chegou aqui, há erros — exibe na tela
cabecalho('Erro ao Atualizar');
?>
<div class="card">
  <h2>Erros na Atualização</h2>
  <div class="alert alert-danger">
    <ul style="padding-left:18px;">
      <?php foreach ($erros as $e): ?>
        <li><?= htmlspecialchars($e, ENT_QUOTES, 'UTF-8') ?></li>
      <?php endforeach; ?>
    </ul>
  </div>
  <a href="index.php" class="btn btn-primary">Voltar à listagem</a>
</div>
<?php rodape(); ?>
