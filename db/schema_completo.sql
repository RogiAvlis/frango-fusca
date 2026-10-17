-- ============================================================
-- BANCO DE DADOS UNIFICADO - FRANGO FUSCA
-- ============================================================

CREATE DATABASE IF NOT EXISTS `frango_fusca` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `frango_fusca`;

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- 1. Tabela ESTABELECIMENTO (Loja / Unidade Comercial)
DROP TABLE IF EXISTS `estabelecimento`;
CREATE TABLE `estabelecimento` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `status_registro` TINYINT DEFAULT 1,
    `nome` VARCHAR(255) NOT NULL,
    `cnpj` VARCHAR(20) DEFAULT NULL,
    `endereco` TEXT DEFAULT NULL,
    `telefone_contato` VARCHAR(20) DEFAULT NULL,
    `nome_responsavel` VARCHAR(255) DEFAULT NULL,
    `telefone_responsavel` VARCHAR(20) DEFAULT NULL,
    `email` VARCHAR(255) DEFAULT NULL,
    `criado_por` INT NULL,
    `data_criacao` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `alterado_por` INT DEFAULT NULL,
    `data_alteracao` TIMESTAMP DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 2. Tabela USUARIO (Profissionais / Equipe)
DROP TABLE IF EXISTS `usuario`;
CREATE TABLE `usuario` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `estabelecimento_id` INT DEFAULT NULL,
    `status_registro` TINYINT DEFAULT 1,
    `nome` VARCHAR(255) NOT NULL,
    `email` VARCHAR(255) NOT NULL UNIQUE,
    `senha` VARCHAR(255) NOT NULL,
    `perfil` ENUM('admin', 'vendedor', 'caixa', 'gerente') DEFAULT 'vendedor',
    `criado_por` INT DEFAULT NULL,
    `data_criacao` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `alterado_por` INT DEFAULT NULL,
    `data_alteracao` TIMESTAMP DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT `fk_usuario_estabelecimento` FOREIGN KEY (`estabelecimento_id`) REFERENCES `estabelecimento` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 3. Tabela CONVITE_USUARIO (Fluxo de Onboarding por E-mail + Código)
DROP TABLE IF EXISTS `convite_usuario`;
CREATE TABLE `convite_usuario` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `estabelecimento_id` INT NOT NULL,
    `email` VARCHAR(255) NOT NULL,
    `codigo_convite` VARCHAR(50) NOT NULL UNIQUE,
    `perfil` ENUM('admin', 'vendedor', 'caixa', 'gerente') DEFAULT 'vendedor',
    `status_convite` ENUM('pendente', 'aceito', 'expirado') DEFAULT 'pendente',
    `criado_por` INT NOT NULL,
    `data_criacao` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `data_aceite` TIMESTAMP NULL DEFAULT NULL,
    CONSTRAINT `fk_convite_estabelecimento` FOREIGN KEY (`estabelecimento_id`) REFERENCES `estabelecimento` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 4. Tabela BANCO (Instituições Financeiras)
DROP TABLE IF EXISTS `banco`;
CREATE TABLE `banco` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `status_registro` TINYINT DEFAULT 1,
    `codigo` VARCHAR(10) DEFAULT NULL,
    `nome` VARCHAR(255) NOT NULL,
    `criado_por` INT NOT NULL,
    `data_criacao` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `alterado_por` INT DEFAULT NULL,
    `data_alteracao` TIMESTAMP DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 5. Tabela METODO_PAGAMENTO
DROP TABLE IF EXISTS `metodo_pagamento`;
CREATE TABLE `metodo_pagamento` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `estabelecimento_id` INT NOT NULL,
    `status_registro` TINYINT DEFAULT 1,
    `nome` VARCHAR(255) NOT NULL,
    `banco_id` INT DEFAULT NULL,
    `agencia` VARCHAR(20) DEFAULT NULL,
    `conta` VARCHAR(30) DEFAULT NULL,
    `criado_por` INT NOT NULL,
    `data_criacao` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `alterado_por` INT DEFAULT NULL,
    `data_alteracao` TIMESTAMP DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT `fk_metodo_pagamento_estabelecimento` FOREIGN KEY (`estabelecimento_id`) REFERENCES `estabelecimento` (`id`) ON DELETE CASCADE,
    CONSTRAINT `fk_metodo_pagamento_banco` FOREIGN KEY (`banco_id`) REFERENCES `banco` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 6. Tabela UNIDADE_MEDIDA
DROP TABLE IF EXISTS `unidade_medida`;
CREATE TABLE `unidade_medida` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `status_registro` TINYINT DEFAULT 1,
    `sigla` VARCHAR(10) NOT NULL UNIQUE,
    `nome` VARCHAR(100) NOT NULL,
    `criado_por` INT NOT NULL,
    `data_criacao` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `alterado_por` INT DEFAULT NULL,
    `data_alteracao` TIMESTAMP DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 7. Tabela FORNECEDOR
DROP TABLE IF EXISTS `fornecedor`;
CREATE TABLE `fornecedor` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `estabelecimento_id` INT NOT NULL,
    `status_registro` TINYINT DEFAULT 1,
    `nome` VARCHAR(255) NOT NULL,
    `cnpj_cpf` VARCHAR(20) DEFAULT NULL,
    `email` VARCHAR(255) DEFAULT NULL,
    `telefone` VARCHAR(20) DEFAULT NULL,
    `endereco` TEXT DEFAULT NULL,
    `criado_por` INT NOT NULL,
    `data_criacao` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `alterado_por` INT DEFAULT NULL,
    `data_alteracao` TIMESTAMP DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT `fk_fornecedor_estabelecimento` FOREIGN KEY (`estabelecimento_id`) REFERENCES `estabelecimento` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 8. Tabela CLIENTE
DROP TABLE IF EXISTS `cliente`;
CREATE TABLE `cliente` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `estabelecimento_id` INT NOT NULL,
    `status_registro` TINYINT DEFAULT 1,
    `nome` VARCHAR(255) NOT NULL,
    `telefone` VARCHAR(20) DEFAULT NULL,
    `email` VARCHAR(255) DEFAULT NULL,
    `endereco` TEXT DEFAULT NULL,
    `criado_por` INT NOT NULL,
    `data_criacao` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `alterado_por` INT DEFAULT NULL,
    `data_alteracao` TIMESTAMP DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT `fk_cliente_estabelecimento` FOREIGN KEY (`estabelecimento_id`) REFERENCES `estabelecimento` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 9. Tabela AMBIENTE_VENDA (Plataformas e Canais)
DROP TABLE IF EXISTS `ambiente_venda`;
CREATE TABLE `ambiente_venda` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `estabelecimento_id` INT NOT NULL,
    `status_registro` TINYINT DEFAULT 1,
    `nome` VARCHAR(255) NOT NULL,
    `descricao` TEXT DEFAULT NULL,
    `taxa` DECIMAL(5,2) DEFAULT 0.00,
    `criado_por` INT NOT NULL,
    `data_criacao` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `alterado_por` INT DEFAULT NULL,
    `data_alteracao` TIMESTAMP DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT `fk_ambiente_venda_estabelecimento` FOREIGN KEY (`estabelecimento_id`) REFERENCES `estabelecimento` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 10. Tabela MAQUINA_VENDA (Maquininhas de Cartão)
DROP TABLE IF EXISTS `maquina_venda`;
CREATE TABLE `maquina_venda` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `estabelecimento_id` INT NOT NULL,
    `status_registro` TINYINT DEFAULT 1,
    `nome` VARCHAR(255) NOT NULL,
    `descricao` TEXT DEFAULT NULL,
    `tipo_taxa` ENUM('percentual', 'fixo_por_venda', 'mensalidade') DEFAULT 'percentual',
    `taxa` DECIMAL(5,2) DEFAULT 0.00,
    `mensalidade_valor` DECIMAL(10,2) DEFAULT 0.00,
    `criado_por` INT NOT NULL,
    `data_criacao` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `alterado_por` INT DEFAULT NULL,
    `data_alteracao` TIMESTAMP DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT `fk_maquina_venda_estabelecimento` FOREIGN KEY (`estabelecimento_id`) REFERENCES `estabelecimento` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 11. Tabela PRODUTO (Insumos, Produtos Acabados e Combos)
DROP TABLE IF EXISTS `produto`;
CREATE TABLE `produto` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `estabelecimento_id` INT NOT NULL,
    `status_registro` TINYINT DEFAULT 1,
    `tipo_produto` ENUM('insumo', 'acabado', 'combo') DEFAULT 'acabado',
    `nome` VARCHAR(255) NOT NULL,
    `descricao` TEXT DEFAULT NULL,
    `preco_custo` DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    `preco_venda` DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    `quantidade_estoque` DECIMAL(10,3) NOT NULL DEFAULT 0.000,
    `estoque_minimo` DECIMAL(10,3) DEFAULT 5.000,
    `unidade_medida_id` INT NOT NULL,
    `fornecedor_id` INT DEFAULT NULL,
    `criado_por` INT NOT NULL,
    `data_criacao` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `alterado_por` INT DEFAULT NULL,
    `data_alteracao` TIMESTAMP DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT `fk_produto_estabelecimento` FOREIGN KEY (`estabelecimento_id`) REFERENCES `estabelecimento` (`id`) ON DELETE CASCADE,
    CONSTRAINT `fk_produto_unidade_medida` FOREIGN KEY (`unidade_medida_id`) REFERENCES `unidade_medida` (`id`),
    CONSTRAINT `fk_produto_fornecedor` FOREIGN KEY (`fornecedor_id`) REFERENCES `fornecedor` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 12. Tabela PRODUTO_COMBO (Itens participantes de um Combo)
DROP TABLE IF EXISTS `produto_combo`;
CREATE TABLE `produto_combo` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `combo_id` INT NOT NULL,
    `item_produto_id` INT NOT NULL,
    `quantidade` DECIMAL(10,3) NOT NULL DEFAULT 1.000,
    `criado_por` INT NOT NULL,
    `data_criacao` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT `fk_combo_pai` FOREIGN KEY (`combo_id`) REFERENCES `produto` (`id`) ON DELETE CASCADE,
    CONSTRAINT `fk_combo_item` FOREIGN KEY (`item_produto_id`) REFERENCES `produto` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 13. Tabela RECEITA (Ficha Técnica - BOM)
DROP TABLE IF EXISTS `receita`;
CREATE TABLE `receita` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `produto_principal_id` INT NOT NULL,
    `produto_ingrediente_id` INT NOT NULL,
    `quantidade_necessaria` DECIMAL(10,3) NOT NULL,
    `unidade_medida_id` INT NOT NULL,
    `criado_por` INT NOT NULL,
    `data_criacao` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `alterado_por` INT DEFAULT NULL,
    `data_alteracao` TIMESTAMP DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT `fk_receita_produto_principal` FOREIGN KEY (`produto_principal_id`) REFERENCES `produto` (`id`) ON DELETE CASCADE,
    CONSTRAINT `fk_receita_produto_ingrediente` FOREIGN KEY (`produto_ingrediente_id`) REFERENCES `produto` (`id`),
    CONSTRAINT `fk_receita_unidade_medida` FOREIGN KEY (`unidade_medida_id`) REFERENCES `unidade_medida` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 14. Tabela ESTOQUE (Entradas, Lotes e Reposição)
DROP TABLE IF EXISTS `estoque`;
CREATE TABLE `estoque` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `estabelecimento_id` INT NOT NULL,
    `status_registro` TINYINT DEFAULT 1,
    `produto_id` INT NOT NULL,
    `fornecedor_id` INT DEFAULT NULL,
    `quantidade` DECIMAL(10,3) NOT NULL,
    `unidade_medida_id` INT NOT NULL,
    `preco_custo_unitario` DECIMAL(10,2) NOT NULL,
    `valor_total` DECIMAL(10,2) NOT NULL,
    `metodo_pagamento_id` INT DEFAULT NULL,
    `data_entrada` DATETIME DEFAULT CURRENT_TIMESTAMP,
    `observacoes` TEXT DEFAULT NULL,
    `criado_por` INT NOT NULL,
    `data_criacao` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `alterado_por` INT DEFAULT NULL,
    `data_alteracao` TIMESTAMP DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT `fk_estoque_estabelecimento` FOREIGN KEY (`estabelecimento_id`) REFERENCES `estabelecimento` (`id`) ON DELETE CASCADE,
    CONSTRAINT `fk_estoque_produto` FOREIGN KEY (`produto_id`) REFERENCES `produto` (`id`) ON DELETE CASCADE,
    CONSTRAINT `fk_estoque_fornecedor` FOREIGN KEY (`fornecedor_id`) REFERENCES `fornecedor` (`id`) ON DELETE SET NULL,
    CONSTRAINT `fk_estoque_unidade` FOREIGN KEY (`unidade_medida_id`) REFERENCES `unidade_medida` (`id`),
    CONSTRAINT `fk_estoque_metodo_pagamento` FOREIGN KEY (`metodo_pagamento_id`) REFERENCES `metodo_pagamento` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 15. Tabela VENDA (Cabeçalho de Vendas)
DROP TABLE IF EXISTS `venda`;
CREATE TABLE `venda` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `estabelecimento_id` INT NOT NULL,
    `status_registro` TINYINT DEFAULT 1,
    `cliente_id` INT DEFAULT NULL,
    `vendedor_id` INT NOT NULL,
    `ambiente_venda_id` INT NOT NULL,
    `metodo_pagamento_id` INT NOT NULL,
    `maquina_id` INT DEFAULT NULL,
    `data_venda` DATETIME DEFAULT CURRENT_TIMESTAMP,
    `valor_total` DECIMAL(10,2) NOT NULL,
    `taxa_canal_aplicada` DECIMAL(10,2) DEFAULT 0.00,
    `taxa_maquina_aplicada` DECIMAL(10,2) DEFAULT 0.00,
    `valor_liquido` DECIMAL(10,2) NOT NULL,
    `criado_por` INT NOT NULL,
    `data_criacao` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `alterado_por` INT DEFAULT NULL,
    `data_alteracao` TIMESTAMP DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT `fk_venda_estabelecimento` FOREIGN KEY (`estabelecimento_id`) REFERENCES `estabelecimento` (`id`) ON DELETE CASCADE,
    CONSTRAINT `fk_venda_cliente` FOREIGN KEY (`cliente_id`) REFERENCES `cliente` (`id`) ON DELETE SET NULL,
    CONSTRAINT `fk_venda_vendedor` FOREIGN KEY (`vendedor_id`) REFERENCES `usuario` (`id`),
    CONSTRAINT `fk_venda_ambiente` FOREIGN KEY (`ambiente_venda_id`) REFERENCES `ambiente_venda` (`id`),
    CONSTRAINT `fk_venda_metodo` FOREIGN KEY (`metodo_pagamento_id`) REFERENCES `metodo_pagamento` (`id`),
    CONSTRAINT `fk_venda_maquina` FOREIGN KEY (`maquina_id`) REFERENCES `maquina_venda` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 16. Tabela ITEM_VENDA
DROP TABLE IF EXISTS `item_venda`;
CREATE TABLE `item_venda` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `venda_id` INT NOT NULL,
    `produto_id` INT NOT NULL,
    `quantidade` DECIMAL(10,3) NOT NULL,
    `preco_venda` DECIMAL(10,2) NOT NULL,
    `subtotal` DECIMAL(10,2) NOT NULL,
    `criado_por` INT NOT NULL,
    `data_criacao` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `alterado_por` INT DEFAULT NULL,
    `data_alteracao` TIMESTAMP DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT `fk_item_venda_venda` FOREIGN KEY (`venda_id`) REFERENCES `venda` (`id`) ON DELETE CASCADE,
    CONSTRAINT `fk_item_venda_produto` FOREIGN KEY (`produto_id`) REFERENCES `produto` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 17. Tabela CUSTO_MENSAL (Despesas Fixas e Variáveis)
DROP TABLE IF EXISTS `custo_mensal`;
CREATE TABLE `custo_mensal` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `estabelecimento_id` INT NOT NULL,
    `status_registro` TINYINT DEFAULT 1,
    `status_pagamento` ENUM('pendente', 'pago') DEFAULT 'pendente',
    `tipo_custo` ENUM('fixo', 'variavel') DEFAULT 'fixo',
    `descricao` VARCHAR(255) NOT NULL,
    `valor` DECIMAL(10,2) NOT NULL,
    `data_vencimento` DATE DEFAULT NULL,
    `data_pagamento` DATE DEFAULT NULL,
    `mes` TINYINT NOT NULL,
    `ano` SMALLINT NOT NULL,
    `metodo_pagamento_id` INT DEFAULT NULL,
    `criado_por` INT NOT NULL,
    `data_criacao` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `alterado_por` INT DEFAULT NULL,
    `data_alteracao` TIMESTAMP DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT `fk_custo_estabelecimento` FOREIGN KEY (`estabelecimento_id`) REFERENCES `estabelecimento` (`id`) ON DELETE CASCADE,
    CONSTRAINT `fk_custo_metodo` FOREIGN KEY (`metodo_pagamento_id`) REFERENCES `metodo_pagamento` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 18. Tabela RESERVA_FINANCEIRA (Cofrinho / Reservas de Emergência)
DROP TABLE IF EXISTS `reserva_financeira`;
CREATE TABLE `reserva_financeira` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `estabelecimento_id` INT NOT NULL,
    `status_registro` TINYINT DEFAULT 1,
    `descricao` VARCHAR(255) NOT NULL,
    `meta_valor` DECIMAL(10,2) DEFAULT 0.00,
    `saldo_atual` DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    `data_previsao` DATE DEFAULT NULL,
    `criado_por` INT NOT NULL,
    `data_criacao` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `alterado_por` INT DEFAULT NULL,
    `data_alteracao` TIMESTAMP DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT `fk_reserva_estabelecimento` FOREIGN KEY (`estabelecimento_id`) REFERENCES `estabelecimento` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

SET FOREIGN_KEY_CHECKS = 1;

-- ============================================================
-- SEED DE DADOS INICIAIS DA APLICAÇÃO
-- ============================================================

-- Inserir Estabelecimento Inicial
INSERT INTO `estabelecimento` (`id`, `nome`, `cnpj`, `nome_responsavel`, `telefone_responsavel`, `email`) 
VALUES (1, 'Frango do Fusca - Matriz', '00.000.000/0001-00', 'Igor Silva', '(11) 99999-9999', 'contato@frangofusca.com.br');

-- Inserir Usuário Administrador Inicial (senha padrao: admin123)
INSERT INTO `usuario` (`id`, `estabelecimento_id`, `nome`, `email`, `senha`, `perfil`) 
VALUES (1, 1, 'Igor Silva do Nascimento', 'igorsilvadnascimento@gmail.com', '$2y$10$Vz.NSjS2J9zby9ZjSwiscO146N8P2cnC/audqLs.TOf0J/AKkf/ra', 'admin');

-- Atualizar criado_por do primeiro estabelecimento e usuário
UPDATE `estabelecimento` SET `criado_por` = 1 WHERE `id` = 1;
UPDATE `usuario` SET `criado_por` = 1 WHERE `id` = 1;

-- Inserir Unidades de Medida Padrão
INSERT INTO `unidade_medida` (`id`, `sigla`, `nome`, `criado_por`) VALUES
(1, 'UN', 'Unidade', 1),
(2, 'KG', 'Quilograma', 1),
(3, 'G', 'Grama', 1),
(4, 'L', 'Litro', 1),
(5, 'ML', 'Mililitro', 1),
(6, 'CX', 'Caixa', 1),
(7, 'PCT', 'Pacote', 1);

-- Inserir Bancos Padrão
INSERT INTO `banco` (`id`, `codigo`, `nome`, `criado_por`) VALUES
(1, '001', 'Banco do Brasil', 1),
(2, '237', 'Bradesco', 1),
(3, '341', 'Itaú Unibanco', 1),
(4, '104', 'Caixa Econômica Federal', 1),
(5, '033', 'Santander', 1),
(6, '260', 'Nu Pagamentos (Nubank)', 1),
(7, '336', 'Banco C6', 1),
(8, '077', 'Banco Inter', 1);

-- Inserir Métodos de Pagamento Padrão
INSERT INTO `metodo_pagamento` (`id`, `estabelecimento_id`, `nome`, `banco_id`, `criado_por`) VALUES
(1, 1, 'Dinheiro (Espécie)', NULL, 1),
(2, 1, 'Pix Chave CNPJ', 6, 1),
(3, 1, 'Cartão de Débito', 3, 1),
(4, 1, 'Cartão de Crédito', 3, 1);

-- Inserir Ambientes de Venda (Canais)
INSERT INTO `ambiente_venda` (`id`, `estabelecimento_id`, `nome`, `descricao`, `taxa`, `criado_por`) VALUES
(1, 1, 'Balcão / Loja Física', 'Venda direta presencial', 0.00, 1),
(2, 1, 'WhatsApp Delivery', 'Vendas diretas via WhatsApp com entregador próprio', 0.00, 1),
(3, 1, 'iFood Delivery', 'Plataforma iFood com comissão', 12.00, 1);

-- Inserir Maquininhas de Venda
INSERT INTO `maquina_venda` (`id`, `estabelecimento_id`, `nome`, `descricao`, `tipo_taxa`, `taxa`, `mensalidade_valor`, `criado_por`) VALUES
(1, 1, 'Maquininha Stone Débito', 'Tarifa para cartão débito Stone', 'percentual', 1.25, 0.00, 1),
(2, 1, 'Maquininha Stone Crédito', 'Tarifa para cartão crédito à vista', 'percentual', 2.99, 0.00, 1);
