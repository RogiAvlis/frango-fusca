CREATE TABLE receita (
    id INT AUTO_INCREMENT NOT NULL,
    status_registro TINYINT DEFAULT 1,
    produto_principal_id INT, 
    produto_ingrediente_id INT, 
    quantidade_necessaria DECIMAL(10,3),
    unidade_medida_id INT NOT NULL,
    criado_por INT NOT NULL,
    data_criacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    alterado_por INT DEFAULT NULL,
    data_alteracao TIMESTAMP DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    FOREIGN KEY (criado_por) REFERENCES usuario(id),
    FOREIGN KEY (alterado_por) REFERENCES usuario(id),
    FOREIGN KEY (produto_principal_id) REFERENCES produto(id),
    FOREIGN KEY (produto_ingrediente_id) REFERENCES produto(id),
    FOREIGN KEY (unidade_medida_id) REFERENCES unidade_medida(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;