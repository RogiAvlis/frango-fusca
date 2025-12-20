CREATE TABLE item_venda (
    id INT AUTO_INCREMENT NOT NULL,
    status_registro TINYINT DEFAULT 1,
    venda_id INT,
    produto_id INT,
    quantidade DECIMAL(10,2) NOT NULL,
    preco_venda DECIMAL(10,2) NOT NULL, 
    criado_por INT NOT NULL,
    data_criacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    alterado_por INT DEFAULT NULL,
    data_alteracao TIMESTAMP DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    FOREIGN KEY (criado_por) REFERENCES usuario(id),
    FOREIGN KEY (alterado_por) REFERENCES usuario(id),
    FOREIGN KEY (venda_id) REFERENCES venda(id),
    FOREIGN KEY (produto_id) REFERENCES produto(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;