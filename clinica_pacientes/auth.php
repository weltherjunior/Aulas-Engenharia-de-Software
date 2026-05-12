<?php
// ============================================================
// auth.php — Controle de acesso por sessão
// ✔ ARQUIVO JÁ PRONTO — não altere este arquivo
// ============================================================

/**
 * Verifica se o usuário está autenticado.
 * Se não estiver, redireciona para login.php e encerra o script.
 *
 * Como usar nas páginas protegidas:
 *   require_once 'auth.php';
 *   verificarAcesso();
 */
function verificarAcesso(): void
{
    // Inicia a sessão apenas se ainda não estiver ativa
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    // Verifica se existe um ID de usuário na sessão
    if (!isset($_SESSION['usuario_id'])) {
        // Usuário não autenticado: redireciona para o login
        header('Location: login.php');
        exit; // IMPORTANTE: sempre use exit após header()
    }

    


}
