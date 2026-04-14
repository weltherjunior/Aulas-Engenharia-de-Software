-- ============================================================
-- banco.sql — Script de criação do banco de dados
-- Execute este script antes de rodar o projeto
-- ============================================================

-- Cria o banco se não existir
CREATE DATABASE IF NOT EXISTS auth_db
    CHARACTER SET utf8
    COLLATE utf8_general_ci;

USE auth_db;

-- ─── Tabela de usuários ──────────────────────────────────────
CREATE TABLE IF NOT EXISTS usuarios (
    id         INT          AUTO_INCREMENT PRIMARY KEY,
    nome       VARCHAR(100) NOT NULL,
    email      VARCHAR(150) NOT NULL UNIQUE,
    senha      VARCHAR(255) NOT NULL,              -- sempre password_hash()
    perfil     ENUM('usuario', 'admin')            -- controle de acesso
               NOT NULL DEFAULT 'usuario',
    criado_em  TIMESTAMP   NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ─── Usuário admin de exemplo ────────────────────────────────
-- Senha: Admin@1234  (hash gerado com PASSWORD_DEFAULT / bcrypt)
INSERT INTO usuarios (nome, email, senha, perfil) VALUES
(
    'Administrador',
    'admin@exemplo.com',
    '$2y$12$KNbsKkC1Y5e8HOVZZcG8TOiUf7rL5TkCG.W/V/XQZD9T7EtyAPlGW',
    'admin'
);

-- ─── Usuário comum de exemplo ────────────────────────────────
-- Senha: Senha@1234
INSERT INTO usuarios (nome, email, senha, perfil) VALUES
(
    'Maria Silva',
    'maria@exemplo.com',
    '$2y$12$5Q8vMjLs4nCHLi1Xt7OUCuWtbqHIXG9A3VEg8dOlmZOWlgM4A9Dq.',
    'usuario'
);
