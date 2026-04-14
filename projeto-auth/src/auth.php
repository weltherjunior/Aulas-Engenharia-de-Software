<?php

/**
 * src/auth.php
 * Funções reutilizáveis de autenticação, controle de acesso e segurança.
 */

// ─── Controle de acesso ────────────────────────────────────────────────────

/**
 * Garante que o usuário está logado.
 * Se $perfilNecessario for informado, exige aquele perfil específico.
 *
 * Uso:
 *   verificarAcesso();           // qualquer usuário logado
 *   verificarAcesso('admin');    // somente administradores
 */
function verificarAcesso(string $perfilNecessario = null): void
{
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    if (!isset($_SESSION['usuario_id'])) {
        header('Location: ../public/login.php');
        exit;
    }

    if ($perfilNecessario !== null && ($_SESSION['usuario_perfil'] ?? '') !== $perfilNecessario) {
        http_response_code(403);
        echo '<h2>Acesso negado.</h2><p>Você não tem permissão para acessar esta página.</p>';
        echo '<a href="../public/index.php">Voltar ao início</a>';
        exit;
    }
}

// ─── CSRF ──────────────────────────────────────────────────────────────────

/**
 * Gera (ou recupera) um token CSRF único para a sessão.
 * Use na tag hidden de cada formulário POST.
 */
function gerarTokenCsrf(): string
{
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }

    return $_SESSION['csrf_token'];
}

/**
 * Valida o token CSRF enviado via POST.
 * Encerra a execução com HTTP 403 se o token for inválido.
 */
function validarCsrf(): void
{
    $tokenRecebido = $_POST['csrf_token'] ?? '';
    $tokenSessao   = $_SESSION['csrf_token'] ?? '';

    if (!hash_equals($tokenSessao, $tokenRecebido)) {
        http_response_code(403);
        exit('Requisição inválida (token CSRF incorreto).');
    }
}

// ─── Helpers de saída ──────────────────────────────────────────────────────

/**
 * Escapa strings para exibição segura no HTML (previne XSS).
 */
function e(string $valor): string
{
    return htmlspecialchars($valor, ENT_QUOTES, 'UTF-8');
}
