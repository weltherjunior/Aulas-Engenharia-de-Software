<?php
// ============================================================
// _layout.php — Funções de layout compartilhadas
// ✔ ARQUIVO JÁ PRONTO — não altere este arquivo
// ============================================================

/**
 * Escapa strings para exibição segura no HTML (previne XSS).
 * Use sempre que exibir dados vindos do banco ou do usuário.
 */
function e(string $valor): string
{
    return htmlspecialchars($valor, ENT_QUOTES, 'UTF-8');
}

/**
 * Imprime o cabeçalho HTML padrão do sistema.
 */
function cabecalho(string $titulo): void
{
    echo '<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>' . e($titulo) . ' — Clínica</title>
  <style>
    * { box-sizing: border-box; margin: 0; padding: 0; }
    body { font-family: Arial, sans-serif; background: #f0f4f8; color: #1a1a2e; }
    .topbar {
      background: #1a3a5c; color: white; padding: 14px 30px;
      display: flex; justify-content: space-between; align-items: center;
    }
    .topbar h1 { font-size: 18px; }
    .topbar a  { color: #a8c8e8; text-decoration: none; font-size: 14px; }
    .topbar a:hover { color: white; }
    .container { max-width: 1100px; margin: 30px auto; padding: 0 20px; }
    .card {
      background: white; border-radius: 10px; padding: 28px;
      box-shadow: 0 2px 8px rgba(0,0,0,0.08); margin-bottom: 24px;
    }
    .card h2 { color: #1a3a5c; margin-bottom: 18px; font-size: 20px; border-bottom: 2px solid #d6e8f7; padding-bottom: 10px; }
    table { width: 100%; border-collapse: collapse; }
    th { background: #1a3a5c; color: white; padding: 10px 12px; text-align: left; font-size: 14px; }
    td { padding: 10px 12px; border-bottom: 1px solid #e0e0e0; font-size: 14px; }
    tr:hover td { background: #f5f9ff; }
    .btn {
      display: inline-block; padding: 8px 16px; border-radius: 6px;
      text-decoration: none; font-size: 13px; font-weight: bold; border: none; cursor: pointer;
    }
    .btn-primary { background: #2e6ea6; color: white; }
    .btn-success { background: #1e6b3c; color: white; }
    .btn-warning { background: #d97706; color: white; }
    .btn-danger  { background: #9b1c1c; color: white; }
    .btn:hover   { opacity: 0.85; }
    .form-group { margin-bottom: 16px; }
    .form-group label { display: block; font-weight: bold; margin-bottom: 6px; font-size: 14px; color: #1a3a5c; }
    .form-group input, .form-group select {
      width: 100%; padding: 9px 12px; border: 1px solid #bdbdbd;
      border-radius: 6px; font-size: 14px; outline: none;
    }
    .form-group input:focus { border-color: #2e6ea6; }
    .alert { padding: 12px 16px; border-radius: 6px; margin-bottom: 16px; font-size: 14px; }
    .alert-danger  { background: #fae0e0; border: 1px solid #9b1c1c; color: #9b1c1c; }
    .alert-success { background: #e8f5e9; border: 1px solid #388e3c; color: #1e6b3c; }
    .mt-2 { margin-top: 16px; }
    .gap { display: flex; gap: 8px; }
  </style>
</head>
<body>
<div class="topbar">
  <h1>🏥 Clínica — Gestão de Pacientes</h1>
  <div>
    Olá, <strong>' . e($_SESSION['usuario_nome'] ?? 'Usuário') . '</strong>
    &nbsp;|&nbsp;
    <a href="index.php">Pacientes</a>
    &nbsp;|&nbsp;
    <a href="logout.php">Sair</a>
  </div>
</div>
<div class="container">';
}

/**
 * Imprime o rodapé HTML padrão do sistema.
 */
function rodape(): void
{
    echo '</div><!-- /container -->
</body>
</html>';
}
