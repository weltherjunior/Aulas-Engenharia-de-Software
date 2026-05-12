<?php
// ============================================================
// login.php — Autenticação do usuário
// ✏️  TAREFA 1: Implemente o bloco PHP abaixo (// TODO)
// ============================================================

session_start();

// Se já estiver logado, redireciona direto para o painel
if (isset($_SESSION['usuario_id'])) {
    header('Location: index.php');
    exit;
}

$erro = '';

// TODO: Implemente o processamento do formulário aqui.
// -------------------------------------------------------
// Passos:
// 1. Verifique se o método da requisição é POST
//    if ($_SERVER['REQUEST_METHOD'] === 'POST') { ... }
//
// 2. Receba os dados: $_POST['email'] e $_POST['senha']
//
// 3. Inclua a conexão:
//    require_once 'conexao.php';
//
// 4. Busque o usuário pelo e-mail com PDO:
//    prepare → bindValue → execute → fetch
//
// 5. Verifique a senha com password_verify()
//
// 6. Se válido:
//    - Armazene $_SESSION['usuario_id'] e $_SESSION['usuario_nome']
//    - Redirecione para index.php com header() + exit
//
// 7. Se inválido:
//    - Atribua uma mensagem à variável $erro
// -------------------------------------------------------

if ($_SERVER['REQUEST_METHOD'] === 'POST') { 
    $email = $_POST['email'] ?? '';
    $senha = $_POST['senha'] ?? '';

    require_once 'conexao.php';

    $stmt = $conn->prepare('SELECT id, nome, senha FROM usuarios WHERE email = :email');
    $stmt->bindValue(':email', $email);
    $stmt->execute();
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($usuario && password_verify($senha, $usuario['senha'])) {
        $_SESSION['usuario_id'] = $usuario['id'];
        $_SESSION['usuario_nome'] = $usuario['nome'];
        header('Location: index.php');
        exit;
    } else {
        $erro = 'E-mail ou senha inválidos.';
    }

}

?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <title>Login — Clínica</title>
  <style>
    * { box-sizing: border-box; margin: 0; padding: 0; }
    body { font-family: Arial, sans-serif; background: #f0f4f8; display: flex; align-items: center; justify-content: center; min-height: 100vh; }
    .login-box { background: white; padding: 40px; border-radius: 12px; box-shadow: 0 4px 20px rgba(0,0,0,0.1); width: 100%; max-width: 400px; }
    .login-box h1 { color: #1a3a5c; text-align: center; margin-bottom: 8px; font-size: 22px; }
    .login-box p  { color: #666; text-align: center; font-size: 14px; margin-bottom: 28px; }
    label { display: block; font-weight: bold; font-size: 14px; color: #1a3a5c; margin-bottom: 6px; }
    input { width: 100%; padding: 10px 12px; border: 1px solid #bdbdbd; border-radius: 6px; font-size: 14px; margin-bottom: 16px; outline: none; }
    input:focus { border-color: #2e6ea6; }
    button { width: 100%; padding: 11px; background: #1a3a5c; color: white; border: none; border-radius: 6px; font-size: 15px; font-weight: bold; cursor: pointer; }
    button:hover { background: #2e6ea6; }
    .alert { background: #fae0e0; border: 1px solid #9b1c1c; color: #9b1c1c; padding: 10px 14px; border-radius: 6px; font-size: 14px; margin-bottom: 16px; }
    .hint { margin-top: 16px; background: #f0f4f8; border-radius: 6px; padding: 10px 14px; font-size: 13px; color: #555; }
    .hint strong { color: #1a3a5c; }
  </style>
</head>
<body>
<div class="login-box">
  <h1>🏥 Clínica</h1>
  <p>Sistema de Gestão de Pacientes</p>

  <?php if (!empty($erro)): ?>
    <div class="alert"><?= htmlspecialchars($erro, ENT_QUOTES, 'UTF-8') ?></div>
  <?php endif; ?>

  <form action="login.php" method="POST">
    <label for="email">E-mail</label>
    <input type="email" id="email" name="email"
           placeholder="admin@clinica.com"
           value="<?= htmlspecialchars($_POST['email'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
           required autofocus>

    <label for="senha">Senha</label>
    <input type="password" id="senha" name="senha"
           placeholder="••••••••" required>

    <button type="submit">Entrar</button>
  </form>

  <div class="hint">
    <strong>Credenciais de teste:</strong><br>
    E-mail: admin@clinica.com<br>
    Senha: Admin@123
  </div>
</div>
</body>
</html>
