CREATE TABLE ambiente_venda (
    id INT AUTO_INCREMENT NOT NULL,
    status_registro TINYINT DEFAULT 1,
    nome VARCHAR(50) UNIQUE NOT NULL,
    descricao TEXT,
    taxa decimal(10,2) DEFAULT 0.00,
    criado_por INT NOT NULL,
    data_criacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    alterado_por INT DEFAULT NULL,
    data_alteracao TIMESTAMP DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    FOREIGN KEY (criado_por) REFERENCES usuario(id),
    FOREIGN KEY (alterado_por) REFERENCES usuario(id),
    UNIQUE (nome)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;