# Sistema de Autenticação em PHP
## Projeto didático — Autenticação com Sessions

---

## Estrutura de arquivos

```
projeto-auth/
├── banco.sql                  ← Execute primeiro no MySQL
├── config/
│   └── database.php           ← Conexão PDO (configure aqui)
├── public/
│   ├── assets/
│   │   └── style.css
│   ├── index.php              ← Página inicial (protegida)
│   ├── login.php              ← Formulário de login
│   ├── cadastro.php           ← Formulário de cadastro
│   ├── logout.php             ← Encerra a sessão
│   └── admin.php              ← Painel admin (somente admins)
└── src/
    └── auth.php               ← Funções reutilizáveis
```

---

## Como rodar

### 1. Banco de dados
Abra o MySQL (phpMyAdmin ou terminal) e execute o arquivo `banco.sql`.
Ele cria o banco `auth_db` e a tabela `usuarios` com dois usuários de exemplo.

### 2. Configurar conexão
Edite `config/database.php` e ajuste as constantes:
```php
define('DB_HOST', 'localhost');
define('DB_NAME', 'auth_db');
define('DB_USER', 'root');
define('DB_PASS', '');   // sua senha do MySQL
```

### 3. Servidor local
Coloque a pasta `projeto-auth` dentro do `htdocs` (XAMPP) ou `www` (WAMP).
Acesse: http://localhost/projeto-auth/public/login.php

---

## Usuários de exemplo

| E-mail               | Senha        | Perfil  |
|----------------------|--------------|---------|
| admin@exemplo.com    | Admin@1234   | admin   |
| maria@exemplo.com    | Senha@1234   | usuario |

---

## Conceitos abordados

| Arquivo           | Conceito principal                          |
|-------------------|---------------------------------------------|
| `login.php`       | `session_start()`, `password_verify()`      |
| `cadastro.php`    | Validação no servidor, `password_hash()`    |
| `logout.php`      | `session_destroy()`, apagar cookie          |
| `auth.php`        | `verificarAcesso()`, CSRF, `e()` para XSS  |
| `admin.php`       | Controle de acesso por perfil               |

---

## Segurança implementada

- **Hash de senha** — `password_hash()` com bcrypt (nunca texto puro)
- **Prepared Statements** — proteção contra SQL Injection
- **Token CSRF** — em todos os formulários POST
- **XSS** — função `e()` (htmlspecialchars) em toda saída
- **Session fixation** — `session_regenerate_id(true)` no login
- **Logout seguro** — destroi sessão e apaga cookie

---

## Dica para a aula

Abra o DevTools do navegador → aba **Application** → **Cookies**.
Observe o cookie `PHPSESSID` sendo criado no login e desaparecendo no logout.
