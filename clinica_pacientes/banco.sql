-- ============================================================
-- BANCO DE DADOS: clinica_db
-- Sistema de Gestão de Pacientes
-- Avaliação Prática — Desenvolvimento de Sistemas Web
-- ============================================================

CREATE DATABASE IF NOT EXISTS clinica_db
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE clinica_db;

-- ------------------------------------------------------------
-- TABELA: usuarios
-- Armazena os usuários do sistema (administradores)
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS usuarios (
  id       INT          AUTO_INCREMENT PRIMARY KEY,
  nome     VARCHAR(100) NOT NULL,
  email    VARCHAR(100) NOT NULL UNIQUE,
  senha    VARCHAR(255) NOT NULL   -- hash bcrypt gerado com password_hash()
);

-- ------------------------------------------------------------
-- TABELA: pacientes
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS pacientes (
  id              INT          AUTO_INCREMENT PRIMARY KEY,
  nome            VARCHAR(100) NOT NULL,
  cpf             VARCHAR(14)  NOT NULL UNIQUE,
  data_nascimento DATE         NOT NULL,
  telefone        VARCHAR(20)  DEFAULT NULL,
  convenio        VARCHAR(80)  DEFAULT NULL,
  criado_em       TIMESTAMP    DEFAULT CURRENT_TIMESTAMP
);

-- ------------------------------------------------------------
-- DADOS INICIAIS
-- Usuário admin: email = admin@clinica.com / senha = Admin@123
-- Hash gerado com: password_hash('Admin@123', PASSWORD_DEFAULT)
-- ------------------------------------------------------------
INSERT INTO usuarios (nome, email, senha) VALUES
(
  'Administrador',
  'admin@clinica.com',
  '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2uheWG/igi.'
  -- ATENÇÃO: se este hash não funcionar no seu ambiente,
  -- execute no PHP: echo password_hash('Admin@123', PASSWORD_DEFAULT);
  -- e substitua o valor acima pelo hash gerado.
);

-- Pacientes de exemplo para testes
INSERT INTO pacientes (nome, cpf, data_nascimento, telefone, convenio) VALUES
('Ana Paula Ferreira',  '123.456.789-00', '1990-03-15', '(62) 99999-1111', 'Unimed'),
('Carlos Eduardo Lima', '987.654.321-00', '1985-07-22', '(62) 98888-2222', 'SulAmérica'),
('Márcia dos Santos',   '111.222.333-44', '2000-11-08', '(62) 97777-3333', NULL),
('João Victor Alves',   '555.666.777-88', '1978-01-30', NULL,              'Bradesco Saúde');
