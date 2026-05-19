-- ============================================================
-- banco.sql — Aula 1: PDO Avançado e Relacionamentos 1:N
-- Execute este script antes de rodar o projeto
-- ============================================================

CREATE DATABASE IF NOT EXISTS agenda_db
    CHARACTER SET utf8
    COLLATE utf8_general_ci;

USE agenda_db;

-- ── Tabela principal ─────────────────────────────────────────
CREATE TABLE IF NOT EXISTS contatos (
    id        INT          AUTO_INCREMENT PRIMARY KEY,
    nome      VARCHAR(100) NOT NULL,
    email     VARCHAR(150) NOT NULL UNIQUE,
    criado_em TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ── Tabela dependente: N telefones por contato ───────────────
CREATE TABLE IF NOT EXISTS telefones (
    id         INT         AUTO_INCREMENT PRIMARY KEY,
    contato_id INT         NOT NULL,
    tipo       VARCHAR(20) NOT NULL DEFAULT 'celular',  -- celular | fixo | trabalho
    numero     VARCHAR(20) NOT NULL,

    FOREIGN KEY (contato_id)
        REFERENCES contatos(id)
        ON DELETE CASCADE   -- deletar contato = deletar telefones
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ── Dados de teste ───────────────────────────────────────────
INSERT INTO contatos (nome, email) VALUES
    ('Maria Silva',    'maria@exemplo.com'),
    ('João Pereira',   'joao@exemplo.com'),
    ('Ana Souza',      'ana@exemplo.com'),
    ('Carlos Lima',    'carlos@exemplo.com'),   -- sem telefone (para testar LEFT JOIN)
    ('Beatriz Nunes',  'beatriz@exemplo.com');

INSERT INTO telefones (contato_id, tipo, numero) VALUES
    -- Maria: 2 telefones
    (1, 'celular',   '(11) 99999-0001'),
    (1, 'fixo',      '(11) 3333-0002'),

    -- João: 1 telefone
    (2, 'celular',   '(21) 98888-0003'),

    -- Ana: 3 telefones
    (3, 'celular',   '(31) 97777-0004'),
    (3, 'trabalho',  '(31) 3210-0005'),
    (3, 'fixo',      '(31) 2222-0006'),

    -- Beatriz: 1 telefone
    (5, 'celular',   '(41) 96666-0007');

-- Carlos (id=4) não tem telefone — ideal para demonstrar LEFT JOIN vs INNER JOIN
