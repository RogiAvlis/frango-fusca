CREATE TABLE fornecedor (
    id INT AUTO_INCREMENT NOT NULL,
    status_registro TINYINT DEFAULT 1,
    nome VARCHAR(100) NOT NULL,
    cnpj_cpf VARCHAR(20) UNIQUE,
    email VARCHAR(100),
    telefone VARCHAR(20),
    endereco TEXT,
    criado_por INT NOT NULL,
    data_criacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    alterado_por INT DEFAULT NULL,
    data_alteracao TIMESTAMP DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    FOREIGN KEY (criado_por) REFERENCES usuario(id),
    FOREIGN KEY (alterado_por) REFERENCES usuario(id),
    UNIQUE (cnpj_cpf)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;