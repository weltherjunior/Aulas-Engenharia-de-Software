<?php
// ============================================================
// excluir.php — Exclusão de paciente
// ✏️  TAREFA 7: Implemente o DELETE abaixo
// ============================================================
require_once 'conexao.php';
require_once 'auth.php';
verificarAcesso();

// TODO: Implemente a exclusão seguindo os passos:
// ------------------------------------------------
// 1. Receba o id via GET: intval($_GET['id'])
//
// 2. Monte o SQL:
//    DELETE FROM pacientes WHERE id = :id
//
// 3. Execute com PDO:
//    prepare → bindValue(":id", $id) → execute
//
// 4. Redirecione para index.php com header() + exit
// ------------------------------------------------
