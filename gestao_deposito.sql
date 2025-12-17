-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 17/12/2025 às 17:32
-- Versão do servidor: 10.4.32-MariaDB
-- Versão do PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `gestao_deposito`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `atributos`
--

CREATE TABLE `atributos` (
  `id` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL COMMENT 'Ex: Cor, Volume, Textura, Material, Bitola'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `atributos`
--

INSERT INTO `atributos` (`id`, `nome`) VALUES
(1, 'Cor'),
(2, 'Volume');

-- --------------------------------------------------------

--
-- Estrutura para tabela `categorias`
--

CREATE TABLE `categorias` (
  `id` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL COMMENT 'Ex: Acabamento, Estrutura, Hidráulica'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `categorias`
--

INSERT INTO `categorias` (`id`, `nome`) VALUES
(1, 'Fundação'),
(2, 'Acabamento2'),
(3, 'Estrutura'),
(4, 'Hidráulica'),
(5, 'Elétrica'),
(6, 'aaaaaa'),
(9, 'Tinta Rosa para pintar coisas de ROSA'),
(11, 'Acabamento'),
(12, 'Fundação'),
(13, 'Estrutura'),
(14, 'Hidráulica'),
(15, 'Elétrica'),
(16, 'Cores'),
(18, 'MatheusLandia 2.12'),
(19, 'RUAN');

-- --------------------------------------------------------

--
-- Estrutura para tabela `fornecedores`
--

CREATE TABLE `fornecedores` (
  `id` int(11) NOT NULL,
  `razao_social` varchar(255) NOT NULL,
  `cnpj` varchar(18) DEFAULT NULL,
  `contato_nome` varchar(100) DEFAULT NULL,
  `contato_telefone` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `fornecedores`
--

INSERT INTO `fornecedores` (`id`, `razao_social`, `cnpj`, `contato_nome`, `contato_telefone`) VALUES
(1, 'CimenTudo S.A.', '11.111.111/0001-11', NULL, NULL),
(2, 'Tintas-Mil Cores Ltda', '22.222.222/0001-22', NULL, NULL),
(3, 'Tintas Coral Ltda', '12.345.678/0001-90', 'Carlos Vendedor', '(11) 99999-1111'),
(4, 'Cimentos Votoran', '98.765.432/0001-10', 'Roberto Silva', '(11) 98888-2222'),
(5, 'Tijolos Cerâmica Forte', '45.678.123/0001-55', 'Ana Souza', '(19) 97777-3333'),
(6, 'a', '1', 'a1', '1'),
(7, 'Miguelito', '11111111111111', 'Miguelito Plays', '11111111111'),
(8, 'oi', '01', 'oi', '019');

-- --------------------------------------------------------

--
-- Estrutura para tabela `item_sku_atributos`
--

CREATE TABLE `item_sku_atributos` (
  `id_item_sku` int(11) NOT NULL,
  `id_valor_atributo` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `item_sku_atributos`
--

INSERT INTO `item_sku_atributos` (`id_item_sku`, `id_valor_atributo`) VALUES
(2001, 1),
(2001, 3),
(2002, 2),
(2002, 4);

-- --------------------------------------------------------

--
-- Estrutura para tabela `itens_sku`
--

CREATE TABLE `itens_sku` (
  `id` int(11) NOT NULL,
  `id_produto` int(11) NOT NULL COMMENT 'Referência ao produto "molde"',
  `id_unidade_medida` int(11) NOT NULL COMMENT 'Unidade de estoque/venda (Ex: Lata 18L, Saco 50kg, m²)',
  `codigo_sku` varchar(100) NOT NULL COMMENT 'Código único (pode ser o código de barras)',
  `peso_bruto_kg` decimal(10,3) DEFAULT NULL COMMENT 'Peso do item para logística',
  `estoque_minimo` decimal(10,3) NOT NULL DEFAULT 0.000 COMMENT 'Nível para disparar alerta de reposição'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `itens_sku`
--

INSERT INTO `itens_sku` (`id`, `id_produto`, `id_unidade_medida`, `codigo_sku`, `peso_bruto_kg`, `estoque_minimo`) VALUES
(1001, 1, 1, 'CIM-CPII-50KG', NULL, 50.000),
(2001, 2, 2, 'TIN-ACR-BRA-18L', NULL, 10.000),
(2002, 2, 3, 'TIN-ACR-AZU-3L6', NULL, 5.000),
(2003, 18, 7, 'TIN-BR-18L', 18.000, 10.000),
(2004, 18, 7, 'TIN-BR-3.6L', 3.600, 5.000),
(2005, 18, 7, 'TIN-AZ-18L', 18.000, 5.000),
(2006, 19, 1, 'CIM-CPII-50', 50.000, 100.000),
(2007, 20, 4, 'TIJ-BA-9F', 2.500, 1000.000),
(2008, 22, 2, 'Ts-BR-123', 100.000, 10.000);

-- --------------------------------------------------------

--
-- Estrutura para tabela `logs`
--

CREATE TABLE `logs` (
  `id` int(11) NOT NULL,
  `id_usuario` int(11) DEFAULT NULL,
  `acao` varchar(255) NOT NULL,
  `detalhes` text DEFAULT NULL,
  `ip` varchar(45) DEFAULT NULL,
  `data_hora` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `logs`
--

INSERT INTO `logs` (`id`, `id_usuario`, `acao`, `detalhes`, `ip`, `data_hora`) VALUES
(1, 3, 'Produto Criado', '{\"nome\":\"teste\",\"categoria\":9}', '::1', '2025-12-01 15:47:38'),
(2, 3, 'Produto Excluído', '{\"id_excluido\":24}', '::1', '2025-12-01 15:47:44'),
(3, 3, 'Produto Excluído', '{\"id_excluido\":23}', '::1', '2025-12-01 15:47:46'),
(4, 3, 'Produto Criado', '{\"nome\":\"Kaua123\",\"categoria\":9}', '::1', '2025-12-01 15:47:51'),
(5, 3, 'Login Sucesso', NULL, '::1', '2025-12-01 16:11:56'),
(6, NULL, 'Login Falha', '{\"login_tentado\":\"oi\"}', '::1', '2025-12-01 16:39:45'),
(7, 3, 'Login Sucesso', NULL, '::1', '2025-12-15 13:57:15'),
(8, 3, 'Login Sucesso', NULL, '::1', '2025-12-15 14:18:47'),
(9, 5, 'Login Falha', '{\"login_tentado\":\"47805206813\"}', '10.91.136.206', '2025-12-15 16:57:57'),
(10, 5, 'Login Sucesso', NULL, '10.91.136.206', '2025-12-15 16:58:04'),
(11, 3, 'Login Sucesso', NULL, '::1', '2025-12-16 13:34:10'),
(12, 4, 'Login Sucesso', NULL, '10.91.136.208', '2025-12-16 13:56:09'),
(13, 3, 'Login Sucesso', NULL, '::1', '2025-12-16 14:30:41');

-- --------------------------------------------------------

--
-- Estrutura para tabela `lotes`
--

CREATE TABLE `lotes` (
  `id` int(11) NOT NULL,
  `id_item_sku` int(11) NOT NULL COMMENT 'O item específico que este lote contém',
  `id_fornecedor` int(11) DEFAULT NULL COMMENT 'De quem compramos este lote (opcional)',
  `codigo_lote_fornecedor` varchar(100) DEFAULT NULL COMMENT 'Código do lote vindo do fornecedor',
  `quantidade_inicial` decimal(10,3) NOT NULL COMMENT 'Quantidade recebida na entrada',
  `quantidade_atual` decimal(10,3) NOT NULL COMMENT 'Saldo atual do lote (o que realmente temos)',
  `data_entrada` datetime NOT NULL DEFAULT current_timestamp(),
  `data_validade` date DEFAULT NULL COMMENT 'CRUCIAL: Data de vencimento (cimento, argamassa, tintas)',
  `custo_compra_unidade` decimal(10,2) DEFAULT NULL,
  `localizacao` varchar(100) DEFAULT NULL COMMENT 'Onde está no depósito (Ex: Rua A, Prateleira 3)'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `lotes`
--

INSERT INTO `lotes` (`id`, `id_item_sku`, `id_fornecedor`, `codigo_lote_fornecedor`, `quantidade_inicial`, `quantidade_atual`, `data_entrada`, `data_validade`, `custo_compra_unidade`, `localizacao`) VALUES
(1, 1001, 1, NULL, 100.000, 0.000, '2025-10-01 09:00:00', '2026-03-01', 52.50, 'Rua A1'),
(2, 1001, 1, NULL, 80.000, 0.000, '2025-11-04 14:00:00', '2026-05-15', 53.00, 'Rua A2'),
(3, 2001, 2, NULL, 30.000, 30.000, '2025-11-04 10:00:00', '2027-11-01', 350.00, 'Prat. T1'),
(4, 2002, 2, NULL, 50.000, 48.000, '2025-11-04 10:00:00', '2027-11-01', 89.90, 'Prat. T2'),
(10, 2002, 1, NULL, 12.000, 11.000, '2025-11-25 14:51:14', '2026-05-15', 12.00, NULL),
(11, 2005, 3, NULL, 10.000, 8.000, '2025-11-25 15:00:28', '2026-05-15', 12.00, NULL),
(12, 2006, 1, NULL, 1.000, 0.000, '2025-11-25 15:36:54', '2026-05-15', 1.00, NULL),
(13, 2007, 1, NULL, 12.000, 12.000, '2025-11-25 15:44:52', '2026-05-15', 12.00, NULL),
(14, 2006, 4, NULL, 1.000, 0.000, '2025-11-25 15:45:08', '2026-05-15', 33.00, NULL),
(15, 2005, 1, NULL, 12.000, 12.000, '2025-11-25 15:47:12', '2026-05-15', 12.00, NULL),
(16, 2007, 5, NULL, 12.000, 12.000, '2025-11-25 15:49:56', '2026-05-15', 12.00, NULL),
(17, 2005, 3, NULL, 12.200, 12.200, '2025-11-25 15:50:27', '2026-05-15', 1.00, NULL),
(18, 1001, 1, NULL, 12.000, 0.000, '2025-11-25 15:51:08', '2026-05-15', 1.00, NULL),
(19, 2007, 4, NULL, 1.000, 1.000, '2025-11-25 15:51:17', '2026-05-15', 1.00, NULL),
(20, 1001, 1, NULL, 12.000, 0.000, '2025-11-25 15:52:11', '2026-05-15', 12.00, NULL),
(21, 1001, 1, NULL, 12.000, 0.000, '2025-11-25 15:53:35', '2026-05-15', 12.00, NULL),
(22, 1001, 1, NULL, 12.000, 0.000, '2025-11-25 15:53:35', '2026-05-15', 12.00, NULL),
(23, 1001, 1, NULL, 12.000, 0.000, '2025-11-25 15:53:35', '2026-05-15', 12.00, NULL),
(24, 1001, 1, NULL, 12.000, 0.000, '2025-11-25 15:53:44', '2026-05-15', 12.00, NULL),
(25, 1001, 1, NULL, 12.000, 0.000, '2025-11-25 15:53:45', '0000-00-00', 12.00, NULL),
(26, 1001, 1, NULL, 12.000, 0.000, '2025-11-25 15:56:28', '0000-00-00', 12.00, NULL),
(27, 1001, 1, NULL, 12.000, 0.000, '2025-11-25 15:57:12', '0000-00-00', 12.00, NULL),
(28, 1001, 1, NULL, 12.000, 0.000, '2025-11-25 15:58:35', '0000-00-00', 12.00, NULL),
(29, 1001, 1, NULL, 12.000, 0.000, '2025-11-25 15:59:18', '0000-00-00', 12.00, NULL),
(30, 1001, 1, NULL, 12.000, 0.000, '2025-11-25 15:59:48', '0000-00-00', 12.00, NULL),
(31, 1001, 1, NULL, 12.000, 0.000, '2025-11-25 16:01:54', '0000-00-00', 12.00, NULL),
(32, 1001, 1, NULL, 12.000, 0.000, '2025-11-25 16:02:48', '0000-00-00', 12.00, NULL),
(33, 1001, 4, NULL, 1.000, 0.000, '2025-11-25 16:03:03', '0000-00-00', 1.00, NULL),
(34, 1001, 4, NULL, 1.000, 0.000, '2025-11-25 16:03:55', '2025-11-26', 1.00, NULL),
(35, 1001, 4, NULL, 1.000, 0.000, '2025-11-25 16:05:09', '0000-00-00', 1.00, NULL),
(36, 1001, 4, NULL, 1.000, 0.000, '2025-11-25 16:07:00', '2025-11-26', 1.00, NULL),
(37, 2006, 1, NULL, 10.000, 0.000, '2025-11-25 16:24:08', '2025-11-25', 15.00, NULL),
(38, 2005, 1, NULL, 1.000, 1.000, '2025-11-25 16:35:09', '2025-11-26', 1.00, NULL),
(39, 2006, 4, NULL, 1.000, 0.000, '2025-11-25 16:37:07', '2025-11-24', 1.00, NULL),
(40, 2006, 1, NULL, 1.000, 1.000, '2025-11-25 16:38:09', '2025-11-26', 1.00, NULL),
(41, 1001, 1, NULL, 1.000, 0.000, '2025-11-25 16:54:44', '2025-11-24', 1.00, NULL),
(42, 2001, 4, NULL, 60.000, 60.000, '2025-11-25 16:59:59', '2025-12-20', 3.00, NULL),
(43, 1001, 4, NULL, 30.000, 26.000, '2025-11-25 17:00:20', '2025-12-10', 3.00, NULL),
(44, 1001, 4, NULL, 30.000, 0.000, '2025-11-25 17:00:40', '2025-10-29', 2.40, NULL),
(45, 1001, 1, NULL, 10.000, 0.000, '2025-11-25 17:01:00', '2025-11-12', 2.00, NULL),
(46, 2006, 4, NULL, 30.000, 0.000, '2025-11-25 17:07:32', '2025-11-22', 1.00, NULL),
(47, 2005, 1, NULL, 12.000, 12.000, '2025-11-26 13:51:11', '2025-11-19', 1.00, NULL),
(48, 2006, 4, NULL, 100.000, 100.000, '2025-11-26 15:59:05', '2027-02-12', 50.00, NULL),
(49, 2006, 4, NULL, 12.000, 12.000, '2025-12-01 15:48:58', '2025-12-09', 12.11, NULL),
(50, 2008, 4, NULL, 12.000, 12.000, '2025-12-01 16:13:25', '2025-12-02', 12.00, NULL),
(51, 2007, 7, NULL, 0.010, 0.000, '2025-12-01 16:36:04', '2024-10-24', 0.00, NULL);

-- --------------------------------------------------------

--
-- Estrutura para tabela `movimentacoes_estoque`
--

CREATE TABLE `movimentacoes_estoque` (
  `id` bigint(20) NOT NULL,
  `id_lote` int(11) NOT NULL COMMENT 'Qual lote foi afetado',
  `id_usuario` int(11) NOT NULL COMMENT 'Quem realizou a movimentação',
  `tipo_movimento` enum('entrada_compra','saida_venda','ajuste_perda','ajuste_inventario','transferencia') NOT NULL,
  `quantidade` decimal(10,3) NOT NULL COMMENT 'Quantidade movimentada (Positiva para entradas, Negativa para saídas/perdas)',
  `data_movimento` datetime NOT NULL DEFAULT current_timestamp(),
  `id_documento_referencia` varchar(100) DEFAULT NULL COMMENT 'ID da Nota Fiscal (entrada) ou Pedido de Venda (saída)',
  `observacao` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `movimentacoes_estoque`
--

INSERT INTO `movimentacoes_estoque` (`id`, `id_lote`, `id_usuario`, `tipo_movimento`, `quantidade`, `data_movimento`, `id_documento_referencia`, `observacao`) VALUES
(1, 1, 2, 'entrada_compra', 100.000, '2025-11-04 15:07:03', 'NF-12345', NULL),
(2, 2, 2, 'entrada_compra', 80.000, '2025-11-04 15:07:03', 'NF-12500', NULL),
(3, 3, 2, 'entrada_compra', 30.000, '2025-11-04 15:07:03', 'NF-9980', NULL),
(4, 4, 2, 'entrada_compra', 50.000, '2025-11-04 15:07:03', 'NF-9981', NULL),
(10, 10, 3, 'entrada_compra', 12.000, '2025-11-25 14:51:14', NULL, NULL),
(11, 11, 3, 'entrada_compra', 10.000, '2025-11-25 15:00:28', NULL, NULL),
(12, 11, 3, 'saida_venda', 1.000, '2025-11-25 15:26:52', NULL, NULL),
(13, 11, 3, 'saida_venda', 1.000, '2025-11-25 15:27:15', NULL, NULL),
(14, 12, 3, 'entrada_compra', 1.000, '2025-11-25 15:36:54', NULL, NULL),
(15, 13, 3, 'entrada_compra', 12.000, '2025-11-25 15:44:52', NULL, NULL),
(16, 14, 3, 'entrada_compra', 1.000, '2025-11-25 15:45:08', NULL, NULL),
(17, 15, 3, 'entrada_compra', 12.000, '2025-11-25 15:47:12', NULL, NULL),
(18, 16, 3, 'entrada_compra', 12.000, '2025-11-25 15:49:56', NULL, NULL),
(19, 17, 3, 'entrada_compra', 12.200, '2025-11-25 15:50:27', NULL, NULL),
(20, 18, 3, 'entrada_compra', 12.000, '2025-11-25 15:51:08', NULL, NULL),
(21, 19, 3, 'entrada_compra', 1.000, '2025-11-25 15:51:17', NULL, NULL),
(22, 20, 3, 'entrada_compra', 12.000, '2025-11-25 15:52:11', NULL, NULL),
(23, 21, 3, 'entrada_compra', 12.000, '2025-11-25 15:53:35', NULL, NULL),
(24, 22, 3, 'entrada_compra', 12.000, '2025-11-25 15:53:35', NULL, NULL),
(25, 23, 3, 'entrada_compra', 12.000, '2025-11-25 15:53:35', NULL, NULL),
(26, 24, 3, 'entrada_compra', 12.000, '2025-11-25 15:53:44', NULL, NULL),
(27, 25, 3, 'entrada_compra', 12.000, '2025-11-25 15:53:45', NULL, NULL),
(28, 26, 3, 'entrada_compra', 12.000, '2025-11-25 15:56:28', NULL, NULL),
(29, 27, 3, 'entrada_compra', 12.000, '2025-11-25 15:57:12', NULL, NULL),
(30, 28, 3, 'entrada_compra', 12.000, '2025-11-25 15:58:35', NULL, NULL),
(31, 29, 3, 'entrada_compra', 12.000, '2025-11-25 15:59:18', NULL, NULL),
(32, 30, 3, 'entrada_compra', 12.000, '2025-11-25 15:59:48', NULL, NULL),
(33, 31, 3, 'entrada_compra', 12.000, '2025-11-25 16:01:54', NULL, NULL),
(34, 32, 3, 'entrada_compra', 12.000, '2025-11-25 16:02:48', NULL, NULL),
(35, 33, 3, 'entrada_compra', 1.000, '2025-11-25 16:03:03', NULL, NULL),
(36, 34, 3, 'entrada_compra', 1.000, '2025-11-25 16:03:55', NULL, NULL),
(37, 35, 3, 'entrada_compra', 1.000, '2025-11-25 16:05:09', NULL, NULL),
(38, 36, 3, 'entrada_compra', 1.000, '2025-11-25 16:07:00', NULL, NULL),
(39, 25, 3, 'saida_venda', 12.000, '2025-11-25 16:12:52', NULL, NULL),
(40, 26, 3, 'saida_venda', 12.000, '2025-11-25 16:12:52', NULL, NULL),
(41, 27, 3, 'saida_venda', 12.000, '2025-11-25 16:12:52', NULL, NULL),
(42, 28, 3, 'saida_venda', 12.000, '2025-11-25 16:12:52', NULL, NULL),
(43, 29, 3, 'saida_venda', 12.000, '2025-11-25 16:12:52', NULL, NULL),
(44, 30, 3, 'saida_venda', 12.000, '2025-11-25 16:12:52', NULL, NULL),
(45, 31, 3, 'saida_venda', 12.000, '2025-11-25 16:12:52', NULL, NULL),
(46, 32, 3, 'saida_venda', 12.000, '2025-11-25 16:12:52', NULL, NULL),
(47, 33, 3, 'saida_venda', 1.000, '2025-11-25 16:12:52', NULL, NULL),
(48, 35, 3, 'saida_venda', 1.000, '2025-11-25 16:12:52', NULL, NULL),
(49, 34, 3, 'saida_venda', 1.000, '2025-11-25 16:12:52', NULL, NULL),
(50, 36, 3, 'saida_venda', 1.000, '2025-11-25 16:12:52', NULL, NULL),
(51, 1, 3, 'saida_venda', 100.000, '2025-11-25 16:12:52', NULL, NULL),
(52, 2, 3, 'saida_venda', 55.000, '2025-11-25 16:12:52', NULL, NULL),
(53, 37, 8, 'entrada_compra', 10.000, '2025-11-25 16:24:08', NULL, NULL),
(54, 38, 3, 'entrada_compra', 1.000, '2025-11-25 16:35:09', NULL, NULL),
(55, 39, 3, 'entrada_compra', 1.000, '2025-11-25 16:37:07', NULL, NULL),
(56, 39, 3, 'saida_venda', 1.000, '2025-11-25 16:37:47', NULL, NULL),
(57, 37, 3, 'saida_venda', 10.000, '2025-11-25 16:37:47', NULL, NULL),
(58, 12, 3, 'saida_venda', 1.000, '2025-11-25 16:37:47', NULL, NULL),
(59, 14, 3, 'saida_venda', 1.000, '2025-11-25 16:37:47', NULL, NULL),
(60, 40, 3, 'entrada_compra', 1.000, '2025-11-25 16:38:09', NULL, NULL),
(61, 41, 3, 'entrada_compra', 1.000, '2025-11-25 16:54:44', NULL, NULL),
(62, 41, 3, 'saida_venda', 1.000, '2025-11-25 16:59:37', NULL, NULL),
(63, 2, 3, 'saida_venda', 25.000, '2025-11-25 16:59:37', NULL, NULL),
(64, 18, 3, 'saida_venda', 12.000, '2025-11-25 16:59:37', NULL, NULL),
(65, 20, 3, 'saida_venda', 12.000, '2025-11-25 16:59:37', NULL, NULL),
(66, 21, 3, 'saida_venda', 12.000, '2025-11-25 16:59:37', NULL, NULL),
(67, 22, 3, 'saida_venda', 12.000, '2025-11-25 16:59:37', NULL, NULL),
(68, 23, 3, 'saida_venda', 12.000, '2025-11-25 16:59:37', NULL, NULL),
(69, 24, 3, 'saida_venda', 12.000, '2025-11-25 16:59:37', NULL, NULL),
(70, 42, 3, 'entrada_compra', 60.000, '2025-11-25 16:59:59', NULL, NULL),
(71, 43, 3, 'entrada_compra', 30.000, '2025-11-25 17:00:20', NULL, NULL),
(72, 44, 3, 'entrada_compra', 30.000, '2025-11-25 17:00:40', NULL, NULL),
(73, 45, 3, 'entrada_compra', 10.000, '2025-11-25 17:01:00', NULL, NULL),
(74, 44, 3, 'saida_venda', 30.000, '2025-11-25 17:05:33', NULL, NULL),
(75, 45, 3, 'saida_venda', 10.000, '2025-11-25 17:05:33', NULL, NULL),
(76, 43, 3, 'saida_venda', 4.000, '2025-11-25 17:05:33', NULL, NULL),
(77, 46, 3, 'entrada_compra', 30.000, '2025-11-25 17:07:32', NULL, NULL),
(78, 46, 3, 'ajuste_perda', 12.000, '2025-11-25 17:25:17', NULL, 'teste ti'),
(79, 46, 3, 'ajuste_perda', 18.000, '2025-11-25 17:27:19', NULL, 'aa'),
(80, 10, 3, 'ajuste_perda', 1.000, '2025-11-25 17:27:40', NULL, '1'),
(81, 4, 3, 'ajuste_perda', 1.000, '2025-11-25 17:27:54', NULL, '1'),
(82, 4, 3, 'ajuste_perda', 1.000, '2025-11-25 17:28:00', NULL, '2'),
(83, 47, 3, 'entrada_compra', 12.000, '2025-11-26 13:51:11', NULL, NULL),
(84, 48, 5, 'entrada_compra', 100.000, '2025-11-26 15:59:05', NULL, NULL),
(85, 49, 3, 'entrada_compra', 12.000, '2025-12-01 15:48:58', NULL, NULL),
(86, 50, 3, 'entrada_compra', 12.000, '2025-12-01 16:13:25', NULL, NULL),
(87, 51, 3, 'entrada_compra', 0.010, '2025-12-01 16:36:04', NULL, NULL),
(88, 51, 3, 'ajuste_perda', 0.010, '2025-12-01 16:37:06', NULL, 'oi');

-- --------------------------------------------------------

--
-- Estrutura para tabela `produtos`
--

CREATE TABLE `produtos` (
  `id` int(11) NOT NULL,
  `id_categoria` int(11) NOT NULL,
  `nome` varchar(255) NOT NULL COMMENT 'Nome base do produto (Ex: Tinta Acrílica Standard)',
  `descricao` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `produtos`
--

INSERT INTO `produtos` (`id`, `id_categoria`, `nome`, `descricao`) VALUES
(1, 1, 'Cimento Portland CP II', 'Cimento para uso geral em fundações e estruturas.'),
(2, 2, 'Tinta Acrílica Fosca Premium', 'Tinta de alta performance para paredes internas e externas.'),
(13, 9, 'Tinta Rosa para pintar coisas de ROSA', 'Tinta Rosa para pintar coisas de ROSA'),
(14, 9, 'Lá Ele', '55'),
(15, 9, 'Tinta Rosa para pintar coisas de ROSA', 'Tinta Rosa para pintar coisas de ROSA'),
(17, 2, 'Tinta Cinza Mergulhante AS1124', 'Teste'),
(18, 11, 'Tinta Acrílica Fosca', 'Tinta de alta cobertura para paredes internas'),
(19, 1, 'Cimento CP II', 'Cimento portland composto para uso geral'),
(20, 3, 'Tijolo Baiano', 'Bloco cerâmico de vedação 9 furos'),
(21, 9, 'Tinta Rosa para pintar coisas de ROSA', 'Tinta Rosa para pintar coisas de ROSA'),
(22, 4, 'teste sku', 'teste sku'),
(25, 9, 'Kaua123', '123');

-- --------------------------------------------------------

--
-- Estrutura para tabela `unidades_medida`
--

CREATE TABLE `unidades_medida` (
  `id` int(11) NOT NULL,
  `nome` varchar(50) NOT NULL COMMENT 'Ex: Quilograma, Metro Cúbico, Saco 50kg',
  `sigla` varchar(10) NOT NULL COMMENT 'Ex: kg, m³, sc50, un'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `unidades_medida`
--

INSERT INTO `unidades_medida` (`id`, `nome`, `sigla`) VALUES
(1, 'Saco 50kg', 'sc50'),
(2, 'Lata 18L', 'lt18'),
(3, 'Lata 3.6L', 'lt3.6'),
(4, 'Unidade', 'un'),
(5, 'Metro Cúbico', 'm³'),
(6, 'Quilograma', 'kg'),
(7, 'Litro', 'L'),
(8, 'Metro Quadrado', 'm2'),
(9, 'oi', 'oi'),
(10, 'Ruan', 'RN');

-- --------------------------------------------------------

--
-- Estrutura para tabela `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `nome` varchar(150) NOT NULL,
  `login` varchar(50) NOT NULL,
  `hash_senha` varchar(255) NOT NULL COMMENT 'NUNCA guarde senhas em texto puro',
  `cargo` varchar(50) DEFAULT NULL COMMENT 'Ex: Estoquista, Vendedor, Gerente'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `usuarios`
--

INSERT INTO `usuarios` (`id`, `nome`, `login`, `hash_senha`, `cargo`) VALUES
(2, 'João Estoquista', 'joao.e', 'hash_seguro_joao', 'Estoquista'),
(3, 'Kaua', '44651922821', '$2y$10$iS6sL2Qt0CNzjWN.gloQieJs4bhrcgUBzcfVo8bsOZMcizAUq6Gpy', 'admin'),
(4, 'Guedes', '23574947860', '$2y$10$vaPlwXYnLBR8zZuVtvhOfuP7DGSpb2PxVemAPj1WAA/7ZRDXQfBr6', 'admin'),
(5, 'Ian', '47805206813', '$2y$10$6tFarF2q1gHH8ENSX6nNEue5hWxNhaILDleecZqCgeawrdtj3/P5y', 'admin'),
(6, 'Matheus Gonçalves Benevides', '44041574870', '$2y$10$Aw7CHFYmBlVChrmxYQrCPem1z6x7moAMjfkWNwdorpixbN..62KDC', 'vendedor'),
(7, 'Ruan Torres Latorre', '46576212895', '$2y$10$xgh1BC8eH9m27XVKWUbBJO8dfq3hyy.WZXY12wnTDEOY1uq06uHQW', 'vendedor'),
(8, 'Ruan Gomes', '20401729615', '$2y$10$w9f.2jmmKbtjYJqahOjgAeazZpsw5pCeJIwbrjSIc5firZezfT216', 'vendedor'),
(9, 'Pedro', '40906739845', '$2y$10$o3D9XLbBH2Goh5xdHu8JZ.J4f1j8V3gUkVuI63H3.pcdrhy.e5si6', 'estoquista'),
(10, 'Tester', 'tester', '$2y$10$e59.MWqmtck7H4Y/AjNJ9.sUk6s51Ddtb.yIpCffoCKqeGTREJNlW', 'estoquista'),
(11, 'Miguelito', '12345678911', '$2y$10$ZCFB/zW6YuOsUD42KixUVe5cbAxVM2HPzWkYUar4cD8Uxl1S9WeQy', 'estoquista');

-- --------------------------------------------------------

--
-- Estrutura para tabela `valores_atributos`
--

CREATE TABLE `valores_atributos` (
  `id` int(11) NOT NULL,
  `id_atributo` int(11) NOT NULL COMMENT 'Referência qual atributo este valor pertence (Ex: ID de "Cor")',
  `valor` varchar(100) NOT NULL COMMENT 'Ex: Branco Neve, 18L, MDF'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `valores_atributos`
--

INSERT INTO `valores_atributos` (`id`, `id_atributo`, `valor`) VALUES
(1, 1, 'Branco Neve'),
(2, 1, 'Azul Céu'),
(3, 2, '18L'),
(4, 2, '3.6L');

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `atributos`
--
ALTER TABLE `atributos`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `categorias`
--
ALTER TABLE `categorias`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `fornecedores`
--
ALTER TABLE `fornecedores`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `cnpj_UNIQUE` (`cnpj`);

--
-- Índices de tabela `item_sku_atributos`
--
ALTER TABLE `item_sku_atributos`
  ADD PRIMARY KEY (`id_item_sku`,`id_valor_atributo`),
  ADD KEY `fk_Item_SKU_Atributos_Valores_Atributos1_idx` (`id_valor_atributo`);

--
-- Índices de tabela `itens_sku`
--
ALTER TABLE `itens_sku`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `codigo_sku_UNIQUE` (`codigo_sku`),
  ADD KEY `fk_Itens_SKU_Produtos1_idx` (`id_produto`),
  ADD KEY `fk_Itens_SKU_Unidades_Medida1_idx` (`id_unidade_medida`);

--
-- Índices de tabela `logs`
--
ALTER TABLE `logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_usuario` (`id_usuario`);

--
-- Índices de tabela `lotes`
--
ALTER TABLE `lotes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_Lotes_Itens_SKU1_idx` (`id_item_sku`),
  ADD KEY `idx_data_validade` (`data_validade`) COMMENT 'Índice crucial para lógica FEFO (First-Expired, First-Out)',
  ADD KEY `fk_Lotes_Fornecedores1_idx` (`id_fornecedor`);

--
-- Índices de tabela `movimentacoes_estoque`
--
ALTER TABLE `movimentacoes_estoque`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_Movimentacoes_Estoque_Lotes1_idx` (`id_lote`),
  ADD KEY `fk_Movimentacoes_Estoque_Usuarios1_idx` (`id_usuario`);

--
-- Índices de tabela `produtos`
--
ALTER TABLE `produtos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_Produtos_Categorias1_idx` (`id_categoria`);

--
-- Índices de tabela `unidades_medida`
--
ALTER TABLE `unidades_medida`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `sigla_UNIQUE` (`sigla`);

--
-- Índices de tabela `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `login_UNIQUE` (`login`);

--
-- Índices de tabela `valores_atributos`
--
ALTER TABLE `valores_atributos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_Valores_Atributos_Atributos1_idx` (`id_atributo`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `atributos`
--
ALTER TABLE `atributos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de tabela `categorias`
--
ALTER TABLE `categorias`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT de tabela `fornecedores`
--
ALTER TABLE `fornecedores`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de tabela `itens_sku`
--
ALTER TABLE `itens_sku`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2009;

--
-- AUTO_INCREMENT de tabela `logs`
--
ALTER TABLE `logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT de tabela `lotes`
--
ALTER TABLE `lotes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=52;

--
-- AUTO_INCREMENT de tabela `movimentacoes_estoque`
--
ALTER TABLE `movimentacoes_estoque`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=89;

--
-- AUTO_INCREMENT de tabela `produtos`
--
ALTER TABLE `produtos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT de tabela `unidades_medida`
--
ALTER TABLE `unidades_medida`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de tabela `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT de tabela `valores_atributos`
--
ALTER TABLE `valores_atributos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Restrições para tabelas despejadas
--

--
-- Restrições para tabelas `item_sku_atributos`
--
ALTER TABLE `item_sku_atributos`
  ADD CONSTRAINT `fk_Item_SKU_Atributos_Itens_SKU1` FOREIGN KEY (`id_item_sku`) REFERENCES `itens_sku` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_Item_SKU_Atributos_Valores_Atributos1` FOREIGN KEY (`id_valor_atributo`) REFERENCES `valores_atributos` (`id`) ON UPDATE CASCADE;

--
-- Restrições para tabelas `itens_sku`
--
ALTER TABLE `itens_sku`
  ADD CONSTRAINT `fk_Itens_SKU_Produtos1` FOREIGN KEY (`id_produto`) REFERENCES `produtos` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_Itens_SKU_Unidades_Medida1` FOREIGN KEY (`id_unidade_medida`) REFERENCES `unidades_medida` (`id`) ON UPDATE CASCADE;

--
-- Restrições para tabelas `logs`
--
ALTER TABLE `logs`
  ADD CONSTRAINT `logs_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id`) ON DELETE SET NULL;

--
-- Restrições para tabelas `lotes`
--
ALTER TABLE `lotes`
  ADD CONSTRAINT `fk_Lotes_Fornecedores1` FOREIGN KEY (`id_fornecedor`) REFERENCES `fornecedores` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_Lotes_Itens_SKU1` FOREIGN KEY (`id_item_sku`) REFERENCES `itens_sku` (`id`) ON UPDATE CASCADE;

--
-- Restrições para tabelas `movimentacoes_estoque`
--
ALTER TABLE `movimentacoes_estoque`
  ADD CONSTRAINT `fk_Movimentacoes_Estoque_Lotes1` FOREIGN KEY (`id_lote`) REFERENCES `lotes` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_Movimentacoes_Estoque_Usuarios1` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id`) ON UPDATE CASCADE;

--
-- Restrições para tabelas `produtos`
--
ALTER TABLE `produtos`
  ADD CONSTRAINT `fk_Produtos_Categorias1` FOREIGN KEY (`id_categoria`) REFERENCES `categorias` (`id`) ON UPDATE CASCADE;

--
-- Restrições para tabelas `valores_atributos`
--
ALTER TABLE `valores_atributos`
  ADD CONSTRAINT `fk_Valores_Atributos_Atributos1` FOREIGN KEY (`id_atributo`) REFERENCES `atributos` (`id`) ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
