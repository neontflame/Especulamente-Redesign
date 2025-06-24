-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Tempo de geração: 24/06/2025 às 01:07
-- Versão do servidor: 8.3.0
-- Versão do PHP: 8.2.18

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `especulamente`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `comentarios`
--

CREATE TABLE IF NOT EXISTS `comentarios` (
  `id` int NOT NULL AUTO_INCREMENT,
  `id_comentador` int NOT NULL,
  `id_coisa` int NOT NULL,
  `tipo_de_coisa` enum('perfil','projeto') NOT NULL,
  `texto` varchar(1024) NOT NULL,
  `fio` int DEFAULT NULL,
  `data` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `convites`
--

CREATE TABLE IF NOT EXISTS `convites` (
  `id` int NOT NULL AUTO_INCREMENT,
  `codigo` varchar(255) NOT NULL,
  `criado_por` int NOT NULL,
  `usado_por` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_criado_por` (`criado_por`),
  KEY `FK_usado_por` (`usado_por`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `daveitens`
--

CREATE TABLE IF NOT EXISTS `daveitens` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nome` varchar(255) NOT NULL,
  `descricao` text NOT NULL,
  `daveprice` int NOT NULL,
  `compravel` tinyint(1) NOT NULL DEFAULT '1',
  `imagem` varchar(255) NOT NULL,
  `consumivel` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `daveniveis`
--

CREATE TABLE IF NOT EXISTS `daveniveis` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nome` varchar(255) NOT NULL,
  `imagem` varchar(255) NOT NULL,
  `davecoins_proximo` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `forum_categorias`
--

CREATE TABLE IF NOT EXISTS `forum_categorias` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nome` text NOT NULL,
  `descricao` text NOT NULL,
  `tipoDeTopico` int NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `forum_posts`
--

CREATE TABLE IF NOT EXISTS `forum_posts` (
  `id` int NOT NULL AUTO_INCREMENT,
  `id_postador` int NOT NULL,
  `id_resposta` int NOT NULL DEFAULT '-1',
  `id_categoria` int NOT NULL,
  `sujeito` text NOT NULL,
  `conteudo` text NOT NULL,
  `data` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `dataBump` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `mitadas` int NOT NULL,
  `sojadas` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `inventario`
--

CREATE TABLE IF NOT EXISTS `inventario` (
  `id` int NOT NULL AUTO_INCREMENT,
  `id_usuario` int NOT NULL,
  `id_item` int NOT NULL,
  `dados` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `mensagens`
--

CREATE TABLE IF NOT EXISTS `mensagens` (
  `id` int NOT NULL AUTO_INCREMENT,
  `data` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `receptor` int NOT NULL,
  `html` text NOT NULL,
  `icone` text NOT NULL,
  `lido` tinyint(1) NOT NULL DEFAULT '0',
  `davecoins` int NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `projetos`
--

CREATE TABLE IF NOT EXISTS `projetos` (
  `id` int NOT NULL AUTO_INCREMENT,
  `id_criador` int NOT NULL,
  `nome` varchar(255) NOT NULL,
  `descricao` text NOT NULL,
  `tipo` enum('dl','md','jg','bg','rt') NOT NULL,
  `arquivos` text NOT NULL,
  `arquivos_de_vdd` text NOT NULL,
  `mitadas` int NOT NULL,
  `sojadas` int NOT NULL,
  `arquivo_vivel` text,
  `thumbnail` varchar(255) NOT NULL,
  `data` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `dataBump` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `reacoes`
--

CREATE TABLE IF NOT EXISTS `reacoes` (
  `id` int NOT NULL AUTO_INCREMENT,
  `tipo_de_reacao` enum('mitada','sojada') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `id_reator` int NOT NULL,
  `tipo_de_reagido` enum('perfil','projeto') NOT NULL,
  `id_reagido` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `reccodigo`
--

CREATE TABLE IF NOT EXISTS `reccodigo` (
  `id` int NOT NULL AUTO_INCREMENT,
  `codigo` varchar(255) NOT NULL,
  `criado_por` int NOT NULL,
  `data` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `FK_criado_por` (`criado_por`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `usuarios`
--

CREATE TABLE IF NOT EXISTS `usuarios` (
  `id` int NOT NULL AUTO_INCREMENT,
  `username` varchar(255) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `bio` varchar(1024) NOT NULL,
  `mitadas` int NOT NULL,
  `sojadas` int NOT NULL,
  `pfp` varchar(255) NOT NULL,
  `banner` varchar(255) NOT NULL,
  `davecoins` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
