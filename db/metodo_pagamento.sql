CREATE TABLE metodo_pagamento (
    id INT AUTO_INCREMENT NOT NULL,
    nome VARCHAR(50) NOT NULL,
    banco VARCHAR(100) DEFAULT NULL,
    agencia VARCHAR(20) DEFAULT NULL,
    conta VARCHAR(20) DEFAULT NULL,
    criado_por INT NOT NULL,
    data_criacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    alterado_por INT DEFAULT NULL,
    data_alteracao TIMESTAMP DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    FOREIGN KEY (criado_por) REFERENCES usuario(id),
    FOREIGN KEY (alterado_por) REFERENCES usuario(id),
    UNIQUE (nome, banco, agencia, conta)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;