-- ============================================================
-- Backup Completo do Banco de Dados: frango_fusca
-- Gerado em: 2026-09-16 01:53:14
-- Host: 127.0.0.1:3306
-- ============================================================

SET FOREIGN_KEY_CHECKS=0;
SET SQL_MODE='NO_AUTO_VALUE_ON_ZERO';
SET NAMES utf8mb4;
SET CHARACTER SET utf8mb4;

CREATE DATABASE IF NOT EXISTS `frango_fusca` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `frango_fusca`;

-- ------------------------------------------------------------
-- Tabela: `ambiente_venda`
-- ------------------------------------------------------------

DROP TABLE IF EXISTS `ambiente_venda`;
CREATE TABLE `ambiente_venda` (
  `id` int NOT NULL AUTO_INCREMENT,
  `estabelecimento_id` int NOT NULL,
  `status_registro` tinyint DEFAULT '1',
  `nome` varchar(255) NOT NULL,
  `descricao` text,
  `taxa` decimal(5,2) DEFAULT '0.00',
  `criado_por` int NOT NULL,
  `data_criacao` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `alterado_por` int DEFAULT NULL,
  `data_alteracao` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `fk_ambiente_venda_estabelecimento` (`estabelecimento_id`),
  CONSTRAINT `fk_ambiente_venda_estabelecimento` FOREIGN KEY (`estabelecimento_id`) REFERENCES `estabelecimento` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `ambiente_venda` (`id`, `estabelecimento_id`, `status_registro`, `nome`, `descricao`, `taxa`, `criado_por`, `data_criacao`, `alterado_por`, `data_alteracao`) VALUES
('1', '1', '1', 'Balcão / Loja Física', 'Venda direta presencial na loja', '0.00', '1', '2026-09-12 20:18:32', NULL, NULL),
('2', '1', '1', 'WhatsApp Delivery', 'Vendas diretas via WhatsApp com entregador próprio', '0.00', '1', '2026-09-12 20:18:32', NULL, NULL),
('3', '1', '1', 'iFood Delivery (Entrega Própria)', 'Plataforma iFood com entrega da loja', '12.00', '1', '2026-09-12 20:18:32', NULL, NULL),
('4', '1', '1', 'iFood Delivery (Entrega iFood)', 'Plataforma iFood com entregador iFood', '27.00', '1', '2026-09-12 20:18:32', NULL, NULL),
('5', '1', '1', 'Rappi Delivery', 'Plataforma Rappi com entrega', '14.00', '1', '2026-09-12 20:18:32', NULL, NULL),
('6', '1', '1', '99Food Delivery', 'Plataforma 99Food com entrega', '12.00', '1', '2026-09-12 20:18:32', NULL, NULL);

-- ------------------------------------------------------------
-- Tabela: `banco`
-- ------------------------------------------------------------

DROP TABLE IF EXISTS `banco`;
CREATE TABLE `banco` (
  `id` int NOT NULL AUTO_INCREMENT,
  `status_registro` tinyint DEFAULT '1',
  `codigo` varchar(10) DEFAULT NULL,
  `nome` varchar(255) NOT NULL,
  `criado_por` int NOT NULL,
  `data_criacao` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `alterado_por` int DEFAULT NULL,
  `data_alteracao` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `banco` (`id`, `status_registro`, `codigo`, `nome`, `criado_por`, `data_criacao`, `alterado_por`, `data_alteracao`) VALUES
('1', '1', '001', 'Banco do Brasil', '1', '2026-09-12 20:18:32', NULL, NULL),
('2', '1', '237', 'Bradesco', '1', '2026-09-12 20:18:32', NULL, NULL),
('3', '1', '341', 'Itaú Unibanco', '1', '2026-09-12 20:18:32', NULL, NULL),
('4', '1', '104', 'Caixa Econômica Federal', '1', '2026-09-12 20:18:32', NULL, NULL),
('5', '1', '033', 'Santander', '1', '2026-09-12 20:18:32', NULL, NULL),
('6', '1', '260', 'Nu Pagamentos (Nubank)', '1', '2026-09-12 20:18:32', NULL, NULL),
('7', '1', '336', 'Banco C6', '1', '2026-09-12 20:18:32', NULL, NULL),
('8', '1', '077', 'Banco Inter', '1', '2026-09-12 20:18:32', NULL, NULL);

-- ------------------------------------------------------------
-- Tabela: `cliente`
-- ------------------------------------------------------------

DROP TABLE IF EXISTS `cliente`;
CREATE TABLE `cliente` (
  `id` int NOT NULL AUTO_INCREMENT,
  `estabelecimento_id` int NOT NULL,
  `status_registro` tinyint DEFAULT '1',
  `nome` varchar(255) NOT NULL,
  `telefone` varchar(20) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `endereco` text,
  `criado_por` int NOT NULL,
  `data_criacao` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `alterado_por` int DEFAULT NULL,
  `data_alteracao` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `fk_cliente_estabelecimento` (`estabelecimento_id`),
  CONSTRAINT `fk_cliente_estabelecimento` FOREIGN KEY (`estabelecimento_id`) REFERENCES `estabelecimento` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `cliente` (`id`, `estabelecimento_id`, `status_registro`, `nome`, `telefone`, `email`, `endereco`, `criado_por`, `data_criacao`, `alterado_por`, `data_alteracao`) VALUES
('1', '1', '1', 'Maria da Silva Santos', '(31) 98877-6655', 'maria.santos@gmail.com', 'Rua Pará de Minas, 450 - Padre Eustáquio, BH/MG', '1', '2026-09-12 20:18:32', NULL, NULL),
('2', '1', '1', 'Carlos Eduardo Oliveira', '(31) 99988-7766', 'carlosedu@yahoo.com.br', 'Av. Cristóvão Colombo, 1200 - Savassi, BH/MG', '1', '2026-09-12 20:18:32', NULL, NULL),
('3', '1', '1', 'Fernanda Souza Lima', '(31) 97766-5544', 'fernanda.lima@outlook.com', 'Rua Platina, 890 - Prado, BH/MG', '1', '2026-09-12 20:18:32', NULL, NULL),
('4', '1', '1', 'Luciano Barbosa Gomes', '(31) 98654-3210', 'luciano.gomes@hotmail.com', 'Av. Altamiro Avelino Soares, 300 - Castelo, BH/MG', '1', '2026-09-12 20:18:32', NULL, NULL);

-- ------------------------------------------------------------
-- Tabela: `convite_usuario`
-- ------------------------------------------------------------

DROP TABLE IF EXISTS `convite_usuario`;
CREATE TABLE `convite_usuario` (
  `id` int NOT NULL AUTO_INCREMENT,
  `estabelecimento_id` int NOT NULL,
  `email` varchar(255) NOT NULL,
  `codigo_convite` varchar(50) NOT NULL,
  `perfil` enum('admin','vendedor','caixa','gerente') DEFAULT 'vendedor',
  `status_convite` enum('pendente','aceito','expirado') DEFAULT 'pendente',
  `criado_por` int NOT NULL,
  `data_criacao` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `data_aceite` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `codigo_convite` (`codigo_convite`),
  KEY `fk_convite_estabelecimento` (`estabelecimento_id`),
  CONSTRAINT `fk_convite_estabelecimento` FOREIGN KEY (`estabelecimento_id`) REFERENCES `estabelecimento` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ------------------------------------------------------------
-- Tabela: `custo_mensal`
-- ------------------------------------------------------------

DROP TABLE IF EXISTS `custo_mensal`;
CREATE TABLE `custo_mensal` (
  `id` int NOT NULL AUTO_INCREMENT,
  `estabelecimento_id` int NOT NULL,
  `status_registro` tinyint DEFAULT '1',
  `status_pagamento` enum('pendente','pago') DEFAULT 'pendente',
  `tipo_custo` enum('fixo','variavel') DEFAULT 'fixo',
  `descricao` varchar(255) NOT NULL,
  `valor` decimal(10,2) NOT NULL,
  `data_vencimento` date DEFAULT NULL,
  `data_pagamento` date DEFAULT NULL,
  `mes` tinyint NOT NULL,
  `ano` smallint NOT NULL,
  `metodo_pagamento_id` int DEFAULT NULL,
  `criado_por` int NOT NULL,
  `data_criacao` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `alterado_por` int DEFAULT NULL,
  `data_alteracao` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `fk_custo_estabelecimento` (`estabelecimento_id`),
  KEY `fk_custo_metodo` (`metodo_pagamento_id`),
  CONSTRAINT `fk_custo_estabelecimento` FOREIGN KEY (`estabelecimento_id`) REFERENCES `estabelecimento` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_custo_metodo` FOREIGN KEY (`metodo_pagamento_id`) REFERENCES `metodo_pagamento` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `custo_mensal` (`id`, `estabelecimento_id`, `status_registro`, `status_pagamento`, `tipo_custo`, `descricao`, `valor`, `data_vencimento`, `data_pagamento`, `mes`, `ano`, `metodo_pagamento_id`, `criado_por`, `data_criacao`, `alterado_por`, `data_alteracao`) VALUES
('1', '1', '1', 'pago', 'fixo', 'Aluguel Loja Comercial (Padre Eustáquio - BH)', '2800.00', '2026-09-10', NULL, '9', '2026', NULL, '1', '2026-09-12 20:18:32', NULL, NULL),
('2', '1', '1', 'pago', 'variavel', 'Energia Elétrica Comercial (CEMIG)', '850.00', '2026-09-15', NULL, '9', '2026', NULL, '1', '2026-09-12 20:18:32', NULL, NULL),
('3', '1', '1', 'pago', 'variavel', 'Água e Esgoto Comercial (COPASA)', '320.00', '2026-09-18', NULL, '9', '2026', NULL, '1', '2026-09-12 20:18:32', NULL, NULL),
('4', '1', '1', 'pago', 'variavel', 'Gás de Cozinha Industrial P45 (Ultragaz)', '980.00', '2026-09-05', NULL, '9', '2026', NULL, '1', '2026-09-12 20:18:32', NULL, NULL),
('5', '1', '1', 'pago', 'fixo', 'Internet Fibra Óptica 500MB (Vero BH)', '149.90', '2026-09-20', NULL, '9', '2026', NULL, '1', '2026-09-12 20:18:32', NULL, NULL),
('6', '1', '1', 'pendente', 'fixo', 'Manutenção Preventiva Máquina de Assar', '250.00', '2026-09-25', NULL, '9', '2026', NULL, '1', '2026-09-12 20:18:32', NULL, NULL),
('7', '1', '1', 'pago', 'fixo', 'Licença Sistema de Gestão & Automação PDV', '120.00', '2026-09-01', NULL, '9', '2026', NULL, '1', '2026-09-12 20:18:32', NULL, NULL),
('8', '1', '1', 'pago', 'variavel', 'Produtos de Higiene e Limpeza da Loja', '300.00', '2026-09-12', NULL, '9', '2026', NULL, '1', '2026-09-12 20:18:32', NULL, NULL);

-- ------------------------------------------------------------
-- Tabela: `estabelecimento`
-- ------------------------------------------------------------

DROP TABLE IF EXISTS `estabelecimento`;
CREATE TABLE `estabelecimento` (
  `id` int NOT NULL AUTO_INCREMENT,
  `status_registro` tinyint DEFAULT '1',
  `nome` varchar(255) NOT NULL,
  `cnpj` varchar(20) DEFAULT NULL,
  `endereco` text,
  `telefone_contato` varchar(20) DEFAULT NULL,
  `nome_responsavel` varchar(255) DEFAULT NULL,
  `telefone_responsavel` varchar(20) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `criado_por` int DEFAULT NULL,
  `data_criacao` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `alterado_por` int DEFAULT NULL,
  `data_alteracao` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `estabelecimento` (`id`, `status_registro`, `nome`, `cnpj`, `endereco`, `telefone_contato`, `nome_responsavel`, `telefone_responsavel`, `email`, `criado_por`, `data_criacao`, `alterado_por`, `data_alteracao`) VALUES
('1', '1', 'Frango do Fusca - Matriz', '00.000.000/0001-00', NULL, NULL, 'Igor Silva', '(11) 99999-9999', 'contato@frangofusca.com.br', '1', '2026-09-12 20:08:13', NULL, '2026-09-12 20:08:13');

-- ------------------------------------------------------------
-- Tabela: `estoque`
-- ------------------------------------------------------------

DROP TABLE IF EXISTS `estoque`;
CREATE TABLE `estoque` (
  `id` int NOT NULL AUTO_INCREMENT,
  `estabelecimento_id` int NOT NULL,
  `status_registro` tinyint DEFAULT '1',
  `produto_id` int NOT NULL,
  `fornecedor_id` int DEFAULT NULL,
  `quantidade` decimal(10,3) NOT NULL,
  `unidade_medida_id` int NOT NULL,
  `preco_custo_unitario` decimal(10,2) NOT NULL,
  `valor_total` decimal(10,2) NOT NULL,
  `metodo_pagamento_id` int DEFAULT NULL,
  `data_entrada` datetime DEFAULT CURRENT_TIMESTAMP,
  `observacoes` text,
  `criado_por` int NOT NULL,
  `data_criacao` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `alterado_por` int DEFAULT NULL,
  `data_alteracao` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `fk_estoque_estabelecimento` (`estabelecimento_id`),
  KEY `fk_estoque_produto` (`produto_id`),
  KEY `fk_estoque_fornecedor` (`fornecedor_id`),
  KEY `fk_estoque_unidade` (`unidade_medida_id`),
  KEY `fk_estoque_metodo_pagamento` (`metodo_pagamento_id`),
  CONSTRAINT `fk_estoque_estabelecimento` FOREIGN KEY (`estabelecimento_id`) REFERENCES `estabelecimento` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_estoque_fornecedor` FOREIGN KEY (`fornecedor_id`) REFERENCES `fornecedor` (`id`) ON DELETE SET NULL,
  CONSTRAINT `fk_estoque_metodo_pagamento` FOREIGN KEY (`metodo_pagamento_id`) REFERENCES `metodo_pagamento` (`id`) ON DELETE SET NULL,
  CONSTRAINT `fk_estoque_produto` FOREIGN KEY (`produto_id`) REFERENCES `produto` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_estoque_unidade` FOREIGN KEY (`unidade_medida_id`) REFERENCES `unidade_medida` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `estoque` (`id`, `estabelecimento_id`, `status_registro`, `produto_id`, `fornecedor_id`, `quantidade`, `unidade_medida_id`, `preco_custo_unitario`, `valor_total`, `metodo_pagamento_id`, `data_entrada`, `observacoes`, `criado_por`, `data_criacao`, `alterado_por`, `data_alteracao`) VALUES
('1', '1', '1', '1', '1', '100.000', '2', '8.90', '890.00', '2', '2026-09-07 20:18:32', 'Lote Lote-Aves-Avivar #1042', '1', '2026-09-12 20:18:32', NULL, NULL),
('2', '1', '1', '2', '2', '80.000', '2', '4.50', '360.00', '1', '2026-09-07 20:18:32', 'Abastecimento CEASA Hortifruti', '1', '2026-09-12 20:18:32', NULL, NULL),
('3', '1', '1', '3', '6', '40.000', '2', '9.80', '392.00', '3', '2026-09-08 20:18:32', 'Compra Mart Minas Farofas', '1', '2026-09-12 20:18:32', NULL, NULL),
('4', '1', '1', '13', '4', '60.000', '1', '7.90', '474.00', '2', '2026-09-09 20:18:32', 'Carga Coca-Cola FEMSA BH', '1', '2026-09-12 20:18:32', NULL, NULL),
('5', '1', '1', '14', '3', '48.000', '1', '6.50', '312.00', '2', '2026-09-09 20:18:32', 'Carga Guaraná Ambev BH', '1', '2026-09-12 20:18:32', NULL, NULL),
('6', '1', '1', '16', '3', '120.000', '1', '5.80', '696.00', '2', '2026-09-10 20:18:32', 'Carga Heineken Ambev BH', '1', '2026-09-12 20:18:32', NULL, NULL);

-- ------------------------------------------------------------
-- Tabela: `fornecedor`
-- ------------------------------------------------------------

DROP TABLE IF EXISTS `fornecedor`;
CREATE TABLE `fornecedor` (
  `id` int NOT NULL AUTO_INCREMENT,
  `estabelecimento_id` int NOT NULL,
  `status_registro` tinyint DEFAULT '1',
  `nome` varchar(255) NOT NULL,
  `cnpj_cpf` varchar(20) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `telefone` varchar(20) DEFAULT NULL,
  `endereco` text,
  `criado_por` int NOT NULL,
  `data_criacao` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `alterado_por` int DEFAULT NULL,
  `data_alteracao` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `fk_fornecedor_estabelecimento` (`estabelecimento_id`),
  CONSTRAINT `fk_fornecedor_estabelecimento` FOREIGN KEY (`estabelecimento_id`) REFERENCES `estabelecimento` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `fornecedor` (`id`, `estabelecimento_id`, `status_registro`, `nome`, `cnpj_cpf`, `email`, `telefone`, `endereco`, `criado_por`, `data_criacao`, `alterado_por`, `data_alteracao`) VALUES
('1', '1', '1', 'Avivar Alimentos Ltda', '03.123.456/0001-89', 'comercial@avivar.com.br', '(31) 3355-1000', 'Rod. MG-050, Contagem / BH - MG', '1', '2026-09-12 20:18:32', NULL, NULL),
('2', '1', '1', 'CEASA Minas - Entreposto Contagem', '17.987.654/0001-12', 'hortifruti@ceasaminas.com.br', '(31) 3399-2000', 'BR-040, Km 529 - Contagem / BH - MG', '1', '2026-09-12 20:18:32', NULL, NULL),
('3', '1', '1', 'Ambev CDD BH Norte', '02.444.555/0001-33', 'vendas.bhnorte@ambev.com.br', '(31) 3450-8000', 'Av. Risoleta Neves, B. São Gabriel - BH/MG', '1', '2026-09-12 20:18:32', NULL, NULL),
('4', '1', '1', 'Coca-Cola FEMSA Brasil BH', '45.678.901/0001-44', 'atendimento@femsa.com.br', '(31) 2101-5000', 'Anel Rodoviário, Olhos d\'Água - BH/MG', '1', '2026-09-12 20:18:32', NULL, NULL),
('5', '1', '1', 'BH Embalagens e Descartáveis', '22.333.444/0001-55', 'vendas@bhembalagens.com.br', '(31) 3271-9000', 'Rua Tupis, Centro - Belo Horizonte/MG', '1', '2026-09-12 20:18:32', NULL, NULL),
('6', '1', '1', 'Supernosso Atacarejo / Mart Minas', '11.222.333/0001-66', 'atacadobh@supernosso.com.br', '(31) 3115-4000', 'Av. Dom Pedro II, Padre Eustáquio - BH/MG', '1', '2026-09-12 20:18:32', NULL, NULL);

-- ------------------------------------------------------------
-- Tabela: `item_venda`
-- ------------------------------------------------------------

DROP TABLE IF EXISTS `item_venda`;
CREATE TABLE `item_venda` (
  `id` int NOT NULL AUTO_INCREMENT,
  `venda_id` int NOT NULL,
  `produto_id` int NOT NULL,
  `quantidade` decimal(10,3) NOT NULL,
  `preco_venda` decimal(10,2) NOT NULL,
  `subtotal` decimal(10,2) NOT NULL,
  `criado_por` int NOT NULL,
  `data_criacao` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `alterado_por` int DEFAULT NULL,
  `data_alteracao` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `fk_item_venda_venda` (`venda_id`),
  KEY `fk_item_venda_produto` (`produto_id`),
  CONSTRAINT `fk_item_venda_produto` FOREIGN KEY (`produto_id`) REFERENCES `produto` (`id`),
  CONSTRAINT `fk_item_venda_venda` FOREIGN KEY (`venda_id`) REFERENCES `venda` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=40 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `item_venda` (`id`, `venda_id`, `produto_id`, `quantidade`, `preco_venda`, `subtotal`, `criado_por`, `data_criacao`, `alterado_por`, `data_alteracao`) VALUES
('1', '1', '24', '1.000', '69.90', '69.90', '1', '2026-09-12 20:18:32', NULL, NULL),
('2', '2', '25', '1.000', '79.90', '79.90', '1', '2026-09-12 20:18:32', NULL, NULL),
('3', '3', '26', '1.000', '84.90', '84.90', '1', '2026-09-12 20:18:32', NULL, NULL),
('4', '4', '21', '1.000', '45.00', '45.00', '1', '2026-09-12 20:18:32', NULL, NULL),
('5', '4', '22', '1.000', '16.00', '16.00', '1', '2026-09-12 20:18:32', NULL, NULL),
('6', '5', '24', '1.000', '69.90', '69.90', '1', '2026-09-12 20:18:32', NULL, NULL),
('7', '6', '25', '1.000', '79.90', '79.90', '1', '2026-09-12 20:18:32', NULL, NULL),
('8', '7', '21', '1.000', '45.00', '45.00', '1', '2026-09-12 20:18:32', NULL, NULL),
('9', '8', '24', '1.000', '69.90', '69.90', '1', '2026-09-15 20:49:29', NULL, NULL),
('10', '9', '25', '1.000', '79.90', '79.90', '1', '2026-09-15 20:49:29', NULL, NULL),
('11', '10', '21', '1.000', '45.00', '45.00', '1', '2026-09-15 20:49:29', NULL, NULL),
('12', '10', '22', '1.000', '16.00', '16.00', '1', '2026-09-15 20:49:29', NULL, NULL),
('13', '10', '13', '1.000', '14.00', '14.00', '1', '2026-09-15 20:49:29', NULL, NULL),
('14', '11', '24', '1.000', '69.90', '69.90', '1', '2026-09-15 20:49:29', NULL, NULL),
('15', '11', '23', '1.000', '12.00', '12.00', '1', '2026-09-15 20:49:29', NULL, NULL),
('16', '12', '26', '1.000', '84.90', '84.90', '1', '2026-09-15 20:49:29', NULL, NULL),
('17', '13', '21', '1.000', '45.00', '45.00', '1', '2026-09-15 20:49:29', NULL, NULL),
('18', '13', '13', '1.000', '14.00', '14.00', '1', '2026-09-15 20:49:29', NULL, NULL),
('19', '14', '21', '1.000', '45.00', '45.00', '1', '2026-09-15 20:49:29', NULL, NULL),
('20', '14', '22', '1.000', '16.00', '16.00', '1', '2026-09-15 20:49:29', NULL, NULL),
('21', '15', '24', '1.000', '69.90', '69.90', '1', '2026-09-15 20:49:29', NULL, NULL),
('22', '16', '21', '1.000', '45.00', '45.00', '1', '2026-09-15 20:49:29', NULL, NULL),
('23', '16', '16', '2.000', '11.00', '22.00', '1', '2026-09-15 20:49:29', NULL, NULL),
('24', '17', '25', '1.000', '79.90', '79.90', '1', '2026-09-15 20:49:29', NULL, NULL),
('25', '18', '26', '1.000', '84.90', '84.90', '1', '2026-09-15 20:49:29', NULL, NULL),
('26', '19', '21', '1.000', '45.00', '45.00', '1', '2026-09-15 20:49:29', NULL, NULL),
('27', '19', '14', '1.000', '12.00', '12.00', '1', '2026-09-15 20:49:29', NULL, NULL),
('28', '20', '24', '1.000', '69.90', '69.90', '1', '2026-09-15 20:49:29', NULL, NULL),
('29', '21', '25', '1.000', '79.90', '79.90', '1', '2026-09-15 20:49:29', NULL, NULL),
('30', '22', '21', '1.000', '45.00', '45.00', '1', '2026-09-15 20:49:29', NULL, NULL),
('31', '22', '22', '1.000', '16.00', '16.00', '1', '2026-09-15 20:49:29', NULL, NULL),
('32', '23', '26', '1.000', '84.90', '84.90', '1', '2026-09-15 20:49:29', NULL, NULL),
('33', '24', '24', '1.000', '69.90', '69.90', '1', '2026-09-15 20:49:29', NULL, NULL),
('34', '25', '24', '1.000', '69.90', '69.90', '1', '2026-09-15 22:06:16', NULL, NULL),
('35', '26', '18', '1.000', '8.50', '8.50', '1', '2026-09-15 22:06:23', NULL, NULL),
('36', '27', '20', '1.000', '4.00', '4.00', '1', '2026-09-15 22:06:43', NULL, NULL),
('37', '28', '26', '1.000', '84.90', '84.90', '1', '2026-09-15 22:08:06', NULL, NULL),
('38', '29', '25', '1.000', '79.90', '79.90', '1', '2026-09-15 22:08:34', NULL, NULL),
('39', '30', '24', '4.000', '69.90', '279.60', '1', '2026-09-15 22:09:44', NULL, NULL);

-- ------------------------------------------------------------
-- Tabela: `maquina_venda`
-- ------------------------------------------------------------

DROP TABLE IF EXISTS `maquina_venda`;
CREATE TABLE `maquina_venda` (
  `id` int NOT NULL AUTO_INCREMENT,
  `estabelecimento_id` int NOT NULL,
  `status_registro` tinyint DEFAULT '1',
  `nome` varchar(255) NOT NULL,
  `descricao` text,
  `tipo_taxa` enum('percentual','fixo_por_venda','mensalidade') DEFAULT 'percentual',
  `taxa` decimal(5,2) DEFAULT '0.00',
  `mensalidade_valor` decimal(10,2) DEFAULT '0.00',
  `criado_por` int NOT NULL,
  `data_criacao` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `alterado_por` int DEFAULT NULL,
  `data_alteracao` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `fk_maquina_venda_estabelecimento` (`estabelecimento_id`),
  CONSTRAINT `fk_maquina_venda_estabelecimento` FOREIGN KEY (`estabelecimento_id`) REFERENCES `estabelecimento` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `maquina_venda` (`id`, `estabelecimento_id`, `status_registro`, `nome`, `descricao`, `tipo_taxa`, `taxa`, `mensalidade_valor`, `criado_por`, `data_criacao`, `alterado_por`, `data_alteracao`) VALUES
('1', '1', '1', 'Maquininha Stone Débito', 'Tarifa débito Stone corporativo', 'percentual', '1.25', '0.00', '1', '2026-09-12 20:18:32', NULL, NULL),
('2', '1', '1', 'Maquininha Stone Crédito à Vista', 'Tarifa crédito à vista Stone', 'percentual', '2.99', '0.00', '1', '2026-09-12 20:18:32', NULL, NULL),
('3', '1', '1', 'Maquininha Stone Crédito Parcelado (2x-6x)', 'Tarifa crédito parcelado', 'percentual', '4.50', '0.00', '1', '2026-09-12 20:18:32', NULL, NULL),
('4', '1', '1', 'Moderninha PagBank Débito', 'Tarifa débito PagBank', 'percentual', '1.39', '0.00', '1', '2026-09-12 20:18:32', NULL, NULL),
('5', '1', '1', 'Moderninha PagBank Crédito', 'Tarifa crédito PagBank', 'percentual', '3.19', '0.00', '1', '2026-09-12 20:18:32', NULL, NULL),
('6', '1', '1', 'Mercado Pago Point Débito', 'Tarifa débito Mercado Pago', 'percentual', '1.19', '0.00', '1', '2026-09-12 20:18:32', NULL, NULL);

-- ------------------------------------------------------------
-- Tabela: `metodo_pagamento`
-- ------------------------------------------------------------

DROP TABLE IF EXISTS `metodo_pagamento`;
CREATE TABLE `metodo_pagamento` (
  `id` int NOT NULL AUTO_INCREMENT,
  `estabelecimento_id` int NOT NULL,
  `status_registro` tinyint DEFAULT '1',
  `nome` varchar(255) NOT NULL,
  `banco_id` int DEFAULT NULL,
  `agencia` varchar(20) DEFAULT NULL,
  `conta` varchar(30) DEFAULT NULL,
  `criado_por` int NOT NULL,
  `data_criacao` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `alterado_por` int DEFAULT NULL,
  `data_alteracao` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `fk_metodo_pagamento_estabelecimento` (`estabelecimento_id`),
  KEY `fk_metodo_pagamento_banco` (`banco_id`),
  CONSTRAINT `fk_metodo_pagamento_banco` FOREIGN KEY (`banco_id`) REFERENCES `banco` (`id`) ON DELETE SET NULL,
  CONSTRAINT `fk_metodo_pagamento_estabelecimento` FOREIGN KEY (`estabelecimento_id`) REFERENCES `estabelecimento` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `metodo_pagamento` (`id`, `estabelecimento_id`, `status_registro`, `nome`, `banco_id`, `agencia`, `conta`, `criado_por`, `data_criacao`, `alterado_por`, `data_alteracao`) VALUES
('1', '1', '1', 'Dinheiro (Espécie)', NULL, NULL, NULL, '1', '2026-09-12 20:18:32', NULL, NULL),
('2', '1', '1', 'Pix Chave CNPJ (Nubank)', '6', NULL, NULL, '1', '2026-09-12 20:18:32', NULL, NULL),
('3', '1', '1', 'Cartão de Débito (Stone)', '3', NULL, NULL, '1', '2026-09-12 20:18:32', NULL, NULL),
('4', '1', '1', 'Cartão de Crédito (Stone)', '3', NULL, NULL, '1', '2026-09-12 20:18:32', NULL, NULL),
('5', '1', '1', 'Pix QR Code Inter', '8', NULL, NULL, '1', '2026-09-12 20:18:32', NULL, NULL);

-- ------------------------------------------------------------
-- Tabela: `produto`
-- ------------------------------------------------------------

DROP TABLE IF EXISTS `produto`;
CREATE TABLE `produto` (
  `id` int NOT NULL AUTO_INCREMENT,
  `estabelecimento_id` int NOT NULL,
  `status_registro` tinyint DEFAULT '1',
  `tipo_produto` enum('insumo','acabado','combo') DEFAULT 'acabado',
  `nome` varchar(255) NOT NULL,
  `descricao` text,
  `preco_custo` decimal(10,2) NOT NULL DEFAULT '0.00',
  `preco_venda` decimal(10,2) NOT NULL DEFAULT '0.00',
  `quantidade_estoque` decimal(10,3) NOT NULL DEFAULT '0.000',
  `estoque_minimo` decimal(10,3) DEFAULT '5.000',
  `unidade_medida_id` int NOT NULL,
  `fornecedor_id` int DEFAULT NULL,
  `criado_por` int NOT NULL,
  `data_criacao` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `alterado_por` int DEFAULT NULL,
  `data_alteracao` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `fk_produto_estabelecimento` (`estabelecimento_id`),
  KEY `fk_produto_unidade_medida` (`unidade_medida_id`),
  KEY `fk_produto_fornecedor` (`fornecedor_id`),
  CONSTRAINT `fk_produto_estabelecimento` FOREIGN KEY (`estabelecimento_id`) REFERENCES `estabelecimento` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_produto_fornecedor` FOREIGN KEY (`fornecedor_id`) REFERENCES `fornecedor` (`id`) ON DELETE SET NULL,
  CONSTRAINT `fk_produto_unidade_medida` FOREIGN KEY (`unidade_medida_id`) REFERENCES `unidade_medida` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=27 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `produto` (`id`, `estabelecimento_id`, `status_registro`, `tipo_produto`, `nome`, `descricao`, `preco_custo`, `preco_venda`, `quantidade_estoque`, `estoque_minimo`, `unidade_medida_id`, `fornecedor_id`, `criado_por`, `data_criacao`, `alterado_por`, `data_alteracao`) VALUES
('1', '1', '1', 'insumo', 'Frango Inteiro Cru com Miúdos', 'Frango resfriado de primeira linha para assar', '8.90', '0.00', '85.000', '20.000', '2', '1', '1', '2026-09-12 20:18:32', NULL, NULL),
('2', '1', '1', 'insumo', 'Batata Monalisa Selecionada', 'Batata lavada especial para assar no molho do frango', '4.50', '0.00', '60.000', '15.000', '2', '2', '1', '2026-09-12 20:18:32', NULL, NULL),
('3', '1', '1', 'insumo', 'Farofa Pronta Temperada Prática', 'Farofa crocante com bacon e temperos caseiros', '9.80', '0.00', '35.000', '10.000', '2', '6', '1', '2026-09-12 20:18:32', NULL, NULL),
('4', '1', '1', 'insumo', 'Sal Refinado Iodado (Pacote 1kg)', 'Sal para marinar tempero especial', '2.20', '0.00', '15.000', '5.000', '2', '6', '1', '2026-09-12 20:18:32', NULL, NULL),
('5', '1', '1', 'insumo', 'Cebolinha Fresca (Maço Especial)', 'Cheiro verde fresco de hortifruti', '1.50', '0.00', '20.000', '5.000', '8', '2', '1', '2026-09-12 20:18:32', NULL, NULL),
('6', '1', '1', 'insumo', 'Salsinha Fresca (Maço Especial)', 'Salsa verde fresca', '1.50', '0.00', '20.000', '5.000', '8', '2', '1', '2026-09-12 20:18:32', NULL, NULL),
('7', '1', '1', 'insumo', 'Alho Descascado In Natura', 'Alho fresco batido para vinha-d\'alhos', '22.00', '0.00', '8.000', '2.000', '2', '2', '1', '2026-09-12 20:18:32', NULL, NULL),
('8', '1', '1', 'insumo', 'Óleo de Soja Refinado 900ml', 'Óleo para marinada e emulsionar tempero', '5.90', '0.00', '24.000', '6.000', '1', '6', '1', '2026-09-12 20:18:32', NULL, NULL),
('9', '1', '1', 'insumo', 'Colorau / Páprica Doce Moída', 'Tempero corante natural para dourar a pele', '0.04', '0.00', '2500.000', '500.000', '3', '6', '1', '2026-09-12 20:18:32', NULL, NULL),
('10', '1', '1', 'insumo', 'Embalagem Térmica Frango Assado', 'Saco térmico laminado antivazamento com fecho', '1.10', '0.00', '200.000', '50.000', '1', '5', '1', '2026-09-12 20:18:32', NULL, NULL),
('11', '1', '0', 'insumo', 'Embalagem de 1500ml Batata', 'Pote de plastico retangular com tampa', '0.65', '0.00', '150.000', '22.000', '7', NULL, '1', '2026-09-12 20:18:32', '1', '2026-09-15 22:07:47'),
('12', '1', '1', 'insumo', 'Manteiga de Garrafa / Tempero Especial', 'Manteiga aromatizada para regar batatas', '28.00', '0.00', '4.000', '1.000', '4', '6', '1', '2026-09-12 20:18:32', NULL, NULL),
('13', '1', '1', 'acabado', 'Refrigerante Coca-Cola 2 Litros', 'Garrafa PET 2L gelada', '7.90', '14.00', '48.000', '12.000', '1', '4', '1', '2026-09-12 20:18:32', NULL, NULL),
('14', '1', '1', 'acabado', 'Refrigerante Guaraná Antarctica 2 Litros', 'Garrafa PET 2L gelada', '6.50', '12.00', '36.000', '12.000', '1', '3', '1', '2026-09-12 20:18:32', NULL, NULL),
('15', '1', '1', 'acabado', 'Refrigerante Coca-Cola Lata 350ml', 'Lata 350ml trincando de gelada', '3.10', '6.50', '72.000', '24.000', '1', '4', '1', '2026-09-12 20:18:32', NULL, NULL),
('16', '1', '1', 'acabado', 'Cerveja Heineken Long Neck 330ml', 'Long Neck 330ml puro malte', '5.80', '11.00', '96.000', '24.000', '1', '3', '1', '2026-09-12 20:18:32', NULL, NULL),
('17', '1', '0', 'acabado', 'Cerveja Amstel Latão 473ml', 'Latão 473ml gelada', '3.90', '8.00', '72.000', '24.000', '1', '3', '1', '2026-09-12 20:18:32', '1', '2026-09-15 21:44:40'),
('18', '1', '1', 'acabado', 'Cerveja Spaten Latão 473ml', 'Latão 473ml estilo Munich Helles', '4.20', '8.50', '59.000', '24.000', '1', '3', '1', '2026-09-12 20:18:32', NULL, '2026-09-15 22:06:23'),
('19', '1', '1', 'acabado', 'Suco Del Valle Uva 1 Litro', 'Caixa 1 Litro', '5.20', '10.00', '24.000', '6.000', '1', '4', '1', '2026-09-12 20:18:32', NULL, NULL),
('20', '1', '1', 'acabado', 'Água Mineral sem Gás 500ml', 'Garrafa 500ml', '1.20', '4.00', '119.000', '24.000', '1', '4', '1', '2026-09-12 20:18:32', NULL, '2026-09-15 22:06:43'),
('21', '1', '1', 'acabado', 'Frango Assado Especial no Espeto', 'Frango assado lentamente na máquina rotativa com tempero mineiro', '32.50', '45.00', '40.000', '10.000', '1', NULL, '1', '2026-09-12 20:18:32', NULL, '2026-09-15 22:01:14'),
('22', '1', '1', 'acabado', 'Porção de Batatas Assadas (500g)', 'Batatas assadas na gordura e suco do frango assado', '3.80', '16.00', '30.000', '10.000', '1', NULL, '1', '2026-09-12 20:18:32', NULL, NULL),
('23', '1', '1', 'acabado', 'Farofa Caseira da Casa (300g)', 'Farofa de frango enriquecida com tempero especial', '3.50', '12.00', '25.000', '10.000', '1', NULL, '1', '2026-09-12 20:18:32', NULL, NULL),
('24', '1', '1', 'combo', 'Combo Família Fusca', '1 Frango Assado + 1 Porção de Batatas + 1 Coca-Cola 2L', '34.50', '69.90', '-5.000', '0.000', '1', NULL, '1', '2026-09-12 20:18:32', NULL, '2026-09-15 22:09:44'),
('25', '1', '1', 'combo', 'Combo Almoço de Domingo', '1 Frango Assado + 1 Porção de Batatas + 1 Farofa da Casa + 1 Guaraná 2L', '36.60', '79.90', '-1.000', '0.000', '1', NULL, '1', '2026-09-12 20:18:32', NULL, '2026-09-15 22:08:34'),
('26', '1', '1', 'combo', 'Combo Galera da Cerveja', '1 Frango Assado + 4 Cervejas Heineken Long Neck 330ml', '46.00', '84.90', '-1.000', '0.000', '1', NULL, '1', '2026-09-12 20:18:32', NULL, '2026-09-15 22:08:06');

-- ------------------------------------------------------------
-- Tabela: `produto_combo`
-- ------------------------------------------------------------

DROP TABLE IF EXISTS `produto_combo`;
CREATE TABLE `produto_combo` (
  `id` int NOT NULL AUTO_INCREMENT,
  `combo_id` int NOT NULL,
  `item_produto_id` int NOT NULL,
  `quantidade` decimal(10,3) NOT NULL DEFAULT '1.000',
  `criado_por` int NOT NULL,
  `data_criacao` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `fk_combo_pai` (`combo_id`),
  KEY `fk_combo_item` (`item_produto_id`),
  CONSTRAINT `fk_combo_item` FOREIGN KEY (`item_produto_id`) REFERENCES `produto` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_combo_pai` FOREIGN KEY (`combo_id`) REFERENCES `produto` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `produto_combo` (`id`, `combo_id`, `item_produto_id`, `quantidade`, `criado_por`, `data_criacao`) VALUES
('1', '24', '21', '1.000', '1', '2026-09-12 20:18:32'),
('2', '24', '22', '1.000', '1', '2026-09-12 20:18:32'),
('3', '24', '13', '1.000', '1', '2026-09-12 20:18:32'),
('4', '25', '21', '1.000', '1', '2026-09-12 20:18:32'),
('5', '25', '22', '1.000', '1', '2026-09-12 20:18:32'),
('6', '25', '23', '1.000', '1', '2026-09-12 20:18:32'),
('7', '25', '14', '1.000', '1', '2026-09-12 20:18:32'),
('8', '26', '21', '1.000', '1', '2026-09-12 20:18:32'),
('9', '26', '16', '4.000', '1', '2026-09-12 20:18:32');

-- ------------------------------------------------------------
-- Tabela: `receita`
-- ------------------------------------------------------------

DROP TABLE IF EXISTS `receita`;
CREATE TABLE `receita` (
  `id` int NOT NULL AUTO_INCREMENT,
  `produto_principal_id` int NOT NULL,
  `produto_ingrediente_id` int NOT NULL,
  `quantidade_necessaria` decimal(10,3) NOT NULL,
  `unidade_medida_id` int NOT NULL,
  `criado_por` int NOT NULL,
  `data_criacao` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `alterado_por` int DEFAULT NULL,
  `data_alteracao` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `fk_receita_produto_principal` (`produto_principal_id`),
  KEY `fk_receita_produto_ingrediente` (`produto_ingrediente_id`),
  KEY `fk_receita_unidade_medida` (`unidade_medida_id`),
  CONSTRAINT `fk_receita_produto_ingrediente` FOREIGN KEY (`produto_ingrediente_id`) REFERENCES `produto` (`id`),
  CONSTRAINT `fk_receita_produto_principal` FOREIGN KEY (`produto_principal_id`) REFERENCES `produto` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_receita_unidade_medida` FOREIGN KEY (`unidade_medida_id`) REFERENCES `unidade_medida` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `receita` (`id`, `produto_principal_id`, `produto_ingrediente_id`, `quantidade_necessaria`, `unidade_medida_id`, `criado_por`, `data_criacao`, `alterado_por`, `data_alteracao`) VALUES
('9', '22', '2', '0.650', '2', '1', '2026-09-12 20:18:32', NULL, NULL),
('10', '22', '12', '0.030', '4', '1', '2026-09-12 20:18:32', NULL, NULL),
('11', '22', '4', '0.010', '2', '1', '2026-09-12 20:18:32', NULL, NULL),
('12', '22', '11', '1.000', '1', '1', '2026-09-12 20:18:32', NULL, NULL),
('13', '23', '3', '0.300', '2', '1', '2026-09-12 20:18:32', NULL, NULL),
('14', '23', '12', '0.020', '4', '1', '2026-09-12 20:18:32', NULL, NULL),
('15', '21', '1', '1.000', '1', '1', '2026-09-15 22:01:14', NULL, NULL),
('16', '21', '2', '5.000', '1', '1', '2026-09-15 22:01:14', NULL, NULL),
('17', '21', '10', '1.000', '1', '1', '2026-09-15 22:01:14', NULL, NULL);

-- ------------------------------------------------------------
-- Tabela: `reserva_financeira`
-- ------------------------------------------------------------

DROP TABLE IF EXISTS `reserva_financeira`;
CREATE TABLE `reserva_financeira` (
  `id` int NOT NULL AUTO_INCREMENT,
  `estabelecimento_id` int NOT NULL,
  `status_registro` tinyint DEFAULT '1',
  `descricao` varchar(255) NOT NULL,
  `meta_valor` decimal(10,2) DEFAULT '0.00',
  `saldo_atual` decimal(10,2) NOT NULL DEFAULT '0.00',
  `data_previsao` date DEFAULT NULL,
  `criado_por` int NOT NULL,
  `data_criacao` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `alterado_por` int DEFAULT NULL,
  `data_alteracao` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `fk_reserva_estabelecimento` (`estabelecimento_id`),
  CONSTRAINT `fk_reserva_estabelecimento` FOREIGN KEY (`estabelecimento_id`) REFERENCES `estabelecimento` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `reserva_financeira` (`id`, `estabelecimento_id`, `status_registro`, `descricao`, `meta_valor`, `saldo_atual`, `data_previsao`, `criado_por`, `data_criacao`, `alterado_por`, `data_alteracao`) VALUES
('1', '1', '1', 'Fundo Emergencial do Estabelecimento', '15000.00', '5400.00', '2026-12-31', '1', '2026-09-12 20:18:32', NULL, NULL),
('2', '1', '1', 'Provisão Troca Assadeira Rotativa 30 Frangos', '8000.00', '2200.00', '2027-06-30', '1', '2026-09-12 20:18:32', NULL, NULL),
('3', '1', '1', 'Provisão de 13º e Férias Equipe', '6000.00', '3100.00', '2026-11-30', '1', '2026-09-12 20:18:32', NULL, NULL);

-- ------------------------------------------------------------
-- Tabela: `unidade_medida`
-- ------------------------------------------------------------

DROP TABLE IF EXISTS `unidade_medida`;
CREATE TABLE `unidade_medida` (
  `id` int NOT NULL AUTO_INCREMENT,
  `status_registro` tinyint DEFAULT '1',
  `sigla` varchar(10) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `criado_por` int NOT NULL,
  `data_criacao` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `alterado_por` int DEFAULT NULL,
  `data_alteracao` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `sigla` (`sigla`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `unidade_medida` (`id`, `status_registro`, `sigla`, `nome`, `criado_por`, `data_criacao`, `alterado_por`, `data_alteracao`) VALUES
('1', '1', 'un', 'Unidade', '1', '2026-09-12 20:18:32', NULL, NULL),
('2', '1', 'kg', 'Quilograma', '1', '2026-09-12 20:18:32', NULL, NULL),
('3', '1', 'g', 'Grama', '1', '2026-09-12 20:18:32', NULL, NULL),
('4', '1', 'l', 'Litro', '1', '2026-09-12 20:18:32', NULL, NULL),
('5', '1', 'ml', 'Mililitro', '1', '2026-09-12 20:18:32', NULL, NULL),
('6', '1', 'cx', 'Caixa', '1', '2026-09-12 20:18:32', NULL, NULL),
('7', '1', 'pct', 'Pacote', '1', '2026-09-12 20:18:32', NULL, NULL),
('8', '1', 'mço', 'Maço', '1', '2026-09-12 20:18:32', NULL, NULL);

-- ------------------------------------------------------------
-- Tabela: `usuario`
-- ------------------------------------------------------------

DROP TABLE IF EXISTS `usuario`;
CREATE TABLE `usuario` (
  `id` int NOT NULL AUTO_INCREMENT,
  `estabelecimento_id` int DEFAULT NULL,
  `status_registro` tinyint DEFAULT '1',
  `nome` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `senha` varchar(255) NOT NULL,
  `perfil` enum('admin','vendedor','caixa','gerente') DEFAULT 'vendedor',
  `criado_por` int DEFAULT NULL,
  `data_criacao` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `alterado_por` int DEFAULT NULL,
  `data_alteracao` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`),
  KEY `fk_usuario_estabelecimento` (`estabelecimento_id`),
  CONSTRAINT `fk_usuario_estabelecimento` FOREIGN KEY (`estabelecimento_id`) REFERENCES `estabelecimento` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `usuario` (`id`, `estabelecimento_id`, `status_registro`, `nome`, `email`, `senha`, `perfil`, `criado_por`, `data_criacao`, `alterado_por`, `data_alteracao`) VALUES
('1', '1', '1', 'Igor Silva do Nascimento', 'igorsilvadnascimento@gmail.com', '$2y$10$N3hTtRG2V8gilQ9QPA8UWu/N6D7q6l3T4abgJ04Tc.1hluUxZVLWO', 'admin', '1', '2026-09-12 20:08:13', NULL, '2026-09-15 20:46:37');

-- ------------------------------------------------------------
-- Tabela: `venda`
-- ------------------------------------------------------------

DROP TABLE IF EXISTS `venda`;
CREATE TABLE `venda` (
  `id` int NOT NULL AUTO_INCREMENT,
  `estabelecimento_id` int NOT NULL,
  `status_registro` tinyint DEFAULT '1',
  `cliente_id` int DEFAULT NULL,
  `vendedor_id` int NOT NULL,
  `ambiente_venda_id` int NOT NULL,
  `metodo_pagamento_id` int NOT NULL,
  `maquina_id` int DEFAULT NULL,
  `data_venda` datetime DEFAULT CURRENT_TIMESTAMP,
  `valor_total` decimal(10,2) NOT NULL,
  `taxa_canal_aplicada` decimal(10,2) DEFAULT '0.00',
  `taxa_maquina_aplicada` decimal(10,2) DEFAULT '0.00',
  `valor_liquido` decimal(10,2) NOT NULL,
  `criado_por` int NOT NULL,
  `data_criacao` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `alterado_por` int DEFAULT NULL,
  `data_alteracao` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `fk_venda_estabelecimento` (`estabelecimento_id`),
  KEY `fk_venda_cliente` (`cliente_id`),
  KEY `fk_venda_vendedor` (`vendedor_id`),
  KEY `fk_venda_ambiente` (`ambiente_venda_id`),
  KEY `fk_venda_metodo` (`metodo_pagamento_id`),
  KEY `fk_venda_maquina` (`maquina_id`),
  CONSTRAINT `fk_venda_ambiente` FOREIGN KEY (`ambiente_venda_id`) REFERENCES `ambiente_venda` (`id`),
  CONSTRAINT `fk_venda_cliente` FOREIGN KEY (`cliente_id`) REFERENCES `cliente` (`id`) ON DELETE SET NULL,
  CONSTRAINT `fk_venda_estabelecimento` FOREIGN KEY (`estabelecimento_id`) REFERENCES `estabelecimento` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_venda_maquina` FOREIGN KEY (`maquina_id`) REFERENCES `maquina_venda` (`id`) ON DELETE SET NULL,
  CONSTRAINT `fk_venda_metodo` FOREIGN KEY (`metodo_pagamento_id`) REFERENCES `metodo_pagamento` (`id`),
  CONSTRAINT `fk_venda_vendedor` FOREIGN KEY (`vendedor_id`) REFERENCES `usuario` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `venda` (`id`, `estabelecimento_id`, `status_registro`, `cliente_id`, `vendedor_id`, `ambiente_venda_id`, `metodo_pagamento_id`, `maquina_id`, `data_venda`, `valor_total`, `taxa_canal_aplicada`, `taxa_maquina_aplicada`, `valor_liquido`, `criado_por`, `data_criacao`, `alterado_por`, `data_alteracao`) VALUES
('1', '1', '1', '1', '1', '1', '2', NULL, '2026-09-12 19:18:32', '69.90', '0.00', '0.00', '69.90', '1', '2026-09-12 20:18:32', NULL, NULL),
('2', '1', '1', '2', '1', '2', '2', NULL, '2026-09-12 18:18:32', '79.90', '0.00', '0.00', '79.90', '1', '2026-09-12 20:18:32', NULL, NULL),
('3', '1', '1', '3', '1', '4', '3', '1', '2026-09-12 17:18:32', '84.90', '22.92', '1.06', '60.92', '1', '2026-09-12 20:18:32', NULL, NULL),
('4', '1', '1', '4', '1', '1', '1', NULL, '2026-09-12 16:18:32', '61.00', '0.00', '0.00', '61.00', '1', '2026-09-12 20:18:32', NULL, NULL),
('5', '1', '1', '1', '1', '1', '2', NULL, '2026-09-05 20:18:32', '69.90', '0.00', '0.00', '69.90', '1', '2026-09-12 20:18:32', NULL, NULL),
('6', '1', '1', '2', '1', '2', '2', NULL, '2026-09-05 20:18:32', '79.90', '0.00', '0.00', '79.90', '1', '2026-09-12 20:18:32', NULL, NULL),
('7', '1', '1', '3', '1', '1', '4', '2', '2026-09-05 20:18:32', '45.00', '0.00', '1.35', '43.65', '1', '2026-09-12 20:18:32', NULL, NULL),
('8', '1', '1', '1', '1', '1', '2', NULL, '2026-09-15 11:30:00', '69.90', '0.00', '0.00', '69.90', '1', '2026-09-15 20:49:29', NULL, NULL),
('9', '1', '1', '2', '1', '2', '2', NULL, '2026-09-15 11:45:00', '79.90', '0.00', '0.00', '79.90', '1', '2026-09-15 20:49:29', NULL, NULL),
('10', '1', '1', '3', '1', '3', '3', '1', '2026-09-15 12:10:00', '75.00', '9.00', '0.94', '65.06', '1', '2026-09-15 20:49:29', NULL, NULL),
('11', '1', '1', '4', '1', '1', '1', NULL, '2026-09-15 12:30:00', '81.90', '0.00', '0.00', '81.90', '1', '2026-09-15 20:49:29', NULL, NULL),
('12', '1', '1', '1', '1', '4', '4', '2', '2026-09-15 12:50:00', '84.90', '22.92', '2.54', '59.44', '1', '2026-09-15 20:49:29', NULL, NULL),
('13', '1', '1', '2', '1', '2', '5', NULL, '2026-09-15 13:15:00', '59.00', '0.00', '0.00', '59.00', '1', '2026-09-15 20:49:29', NULL, NULL),
('14', '1', '1', '3', '1', '1', '3', '1', '2026-09-15 13:40:00', '61.00', '0.00', '0.76', '60.24', '1', '2026-09-15 20:49:29', NULL, NULL),
('15', '1', '1', '4', '1', '2', '2', NULL, '2026-09-15 17:30:00', '69.90', '0.00', '0.00', '69.90', '1', '2026-09-15 20:49:29', NULL, NULL),
('16', '1', '1', '1', '1', '1', '1', NULL, '2026-09-15 18:15:00', '67.00', '0.00', '0.00', '67.00', '1', '2026-09-15 20:49:29', NULL, NULL),
('17', '1', '1', '2', '1', '3', '4', '2', '2026-09-15 18:45:00', '79.90', '9.59', '2.39', '67.92', '1', '2026-09-15 20:49:29', NULL, NULL),
('18', '1', '1', '3', '1', '1', '2', NULL, '2026-09-15 19:20:00', '84.90', '0.00', '0.00', '84.90', '1', '2026-09-15 20:49:29', NULL, NULL),
('19', '1', '1', '4', '1', '2', '2', NULL, '2026-09-15 20:05:00', '57.00', '0.00', '0.00', '57.00', '1', '2026-09-15 20:49:29', NULL, NULL),
('20', '1', '1', '1', '1', '1', '2', NULL, '2026-09-08 11:40:00', '69.90', '0.00', '0.00', '69.90', '1', '2026-09-15 20:49:29', NULL, NULL),
('21', '1', '1', '2', '1', '2', '2', NULL, '2026-09-08 12:20:00', '79.90', '0.00', '0.00', '79.90', '1', '2026-09-15 20:49:29', NULL, NULL),
('22', '1', '1', '3', '1', '1', '1', NULL, '2026-09-08 13:00:00', '61.00', '0.00', '0.00', '61.00', '1', '2026-09-15 20:49:29', NULL, NULL),
('23', '1', '1', '4', '1', '3', '3', '1', '2026-09-08 18:30:00', '84.90', '10.19', '1.06', '73.65', '1', '2026-09-15 20:49:29', NULL, NULL),
('24', '1', '1', '1', '1', '2', '2', NULL, '2026-09-08 19:10:00', '69.90', '0.00', '0.00', '69.90', '1', '2026-09-15 20:49:29', NULL, NULL),
('25', '1', '1', NULL, '1', '6', '5', NULL, '2026-09-15 22:06:16', '69.90', '8.39', '0.00', '61.51', '1', '2026-09-15 22:06:16', NULL, NULL),
('26', '1', '1', NULL, '1', '6', '5', NULL, '2026-09-15 22:06:23', '8.50', '1.02', '0.00', '7.48', '1', '2026-09-15 22:06:23', NULL, NULL),
('27', '1', '1', NULL, '1', '6', '5', NULL, '2026-09-15 22:06:43', '4.00', '0.48', '0.00', '3.52', '1', '2026-09-15 22:06:43', NULL, NULL),
('28', '1', '1', NULL, '1', '6', '5', NULL, '2026-09-15 22:08:06', '84.90', '10.19', '0.00', '74.71', '1', '2026-09-15 22:08:06', NULL, NULL),
('29', '1', '1', NULL, '1', '6', '5', NULL, '2026-09-15 22:08:34', '79.90', '9.59', '0.00', '70.31', '1', '2026-09-15 22:08:34', NULL, NULL),
('30', '1', '1', '3', '1', '6', '5', NULL, '2026-09-15 22:09:44', '279.60', '33.55', '0.00', '246.05', '1', '2026-09-15 22:09:44', NULL, NULL);

SET FOREIGN_KEY_CHECKS=1;

-- ============================================================
-- Fim do backup
-- ============================================================