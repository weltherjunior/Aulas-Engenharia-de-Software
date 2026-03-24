CREATE TABLE IF NOT EXISTS cliente (
        id INT AUTO_INCREMENT PRIMARY KEY,
        nome VARCHAR(255) NOT NULL,
        cpf VARCHAR(14) NOT NULL,
        data_nascimento DATE NOT NULL,
        telefone VARCHAR(20),
        email VARCHAR(255),
        logradouro VARCHAR(255),
        cep VARCHAR(9),
        bairro VARCHAR(255),
        cidade VARCHAR(255),
        estado VARCHAR(2)
    )