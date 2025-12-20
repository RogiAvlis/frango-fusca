CREATE TABLE unidade_medida (
    id INT AUTO_INCREMENT NOT NULL,
    status_registro TINYINT DEFAULT 1,
    sigla VARCHAR(5) NOT NULL,
    nome VARCHAR(50),
    criado_por INT NOT NULL,
    data_criacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    alterado_por INT DEFAULT NULL,
    data_alteracao TIMESTAMP DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    FOREIGN KEY (criado_por) REFERENCES usuario(id),
    FOREIGN KEY (alterado_por) REFERENCES usuario(id),
    UNIQUE (sigla)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;