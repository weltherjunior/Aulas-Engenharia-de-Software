<?php
// ============================================================
// salvar.php — Validação e inserção de paciente
// ✏️  TAREFA 4: Complete as partes marcadas com TODO
// ============================================================
require_once 'conexao.php';
require_once 'auth.php';
require_once '_layout.php';
verificarAcesso();

$erros = [];

// TODO 1: Receba os dados enviados pelo formulário (POST)
// Campos: $nome, $cpf, $data_nascimento, $telefone, $convenio



// TODO 2: Valide os campos obrigatórios com empty()
// nome: obrigatório
// cpf: obrigatório
// data_nascimento: obrigatório
// Adicione a mensagem de erro ao array $erros
// Exemplo: $erros[] = 'O campo nome é obrigatório.';




// TODO 3: Se não houver erros, execute o INSERT com PDO
// Use: prepare → bindValue → execute
// SQL: INSERT INTO pacientes 
// Após o INSERT: redirecione para index.php com header() + exit
if (empty($erros)) {
    // Escreva o INSERT aqui




}

// Se chegou aqui, há erros — exibe na tela
cabecalho('Erro ao Salvar');
?>
<div class="card">
  <h2>Erros no Cadastro</h2>
  <div class="alert alert-danger">
    <ul style="padding-left:18px;">
      <?php foreach ($erros as $e): ?>
        <li><?= htmlspecialchars($e, ENT_QUOTES, 'UTF-8') ?></li>
      <?php endforeach; ?>
    </ul>
  </div>
  <a href="cadastrar.php" class="btn btn-primary">Voltar ao formulário</a>
</div>
<?php rodape(); ?>
