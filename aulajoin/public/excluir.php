<?php

/**
 * public/excluir.php
 * Remove um contato. Graças ao ON DELETE CASCADE,
 * os telefones são removidos automaticamente pelo banco.
 */

require_once '../config/database.php';
require_once '../src/contatos.php';

$id = (int)($_GET['id'] ?? 0);

if ($id > 0) {
    deletarContato(getConexao(), $id);
}

header('Location: index.php?msg=Contato+removido.');
exit;
