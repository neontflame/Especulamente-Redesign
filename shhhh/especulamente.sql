-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Tempo de geração: 09/12/2025 às 21:20
-- Versão do servidor: 9.1.0
-- Versão do PHP: 8.0.30

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
-- Estrutura para tabela `bounties`
--

CREATE TABLE IF NOT EXISTS `bounties` (
  `id` int NOT NULL AUTO_INCREMENT,
  `davecoins` int NOT NULL,
  `nome` text COLLATE utf8mb4_general_ci NOT NULL,
  `imagem` text COLLATE utf8mb4_general_ci NOT NULL,
  `descricao` text COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `bounties`
--

INSERT INTO `bounties` (`id`, `davecoins`, `nome`, `imagem`, `descricao`) VALUES
(1, 0, 'Diada diária', 'diario.png', 'Você é um Bad Enough Dude para entrar no Especulamente todos os dias?? Pois bem, apenas os mais corajosos podem obter as DAVECOINS GRÁTIS diárias!'),
(2, 1, 'Dê uma mitada', 'mitada.png', 'Apreciar o trabalho de seus camaradas é o dever de todo bom Especulador. Realize o seu dever e espalhe mitadas na cara de todos!'),
(3, 5, 'Faça um comentário', 'comentario.png', 'Os ESPECULATIVOS adoram compartilhar feedback!!! É a forma divina suprema da mitada em forma de palavras. Não tenha vergonha de usar seus dedos.'),
(4, 10, 'Poste uma mídia', 'midia.png', 'Sua mídia está ENCONTRADA: você postará-la ela hoje mesmo ao agora:agora minuto.'),
(5, 10, 'Poste um downloadável', 'downloadavel.png', 'Seu computador contém ARQUIVOS, tantos arquivos... porque não dar alguns para nós?'),
(6, 10, 'Poste um blog', 'blog.png', 'AH cara... isso é literalmente eu quando eu penso.'),
(7, 25, 'Poste um jogo', 'jogo.png', 'Agora é só para os verdadeiros gamers; aqueles que dominam suas engines assim como os maiores bosses (chefões) dos games (jogos) clássicos de arcade (fliperama).'),
(8, 25, 'Crie um resto...', 'resto.png', 'A internet é uma série de tubos.'),
(9, 5, 'Responda a uma thread (fórum)', 'forumResp', 'Contribua para as conversas! Engaje com um ESPECULATIVO próximo respondendo à thread de tal nos FÓRUNS.'),
(10, 10, 'Crie uma thread (fórum)', 'forumThread', 'COMECE uma conversa, ou um debate! Crie uma thread nos FÓRUNS.');

-- --------------------------------------------------------

--
-- Estrutura para tabela `bounties_completos`
--

CREATE TABLE IF NOT EXISTS `bounties_completos` (
  `id` int NOT NULL AUTO_INCREMENT,
  `id_usuario` int NOT NULL,
  `id_bounty` int NOT NULL,
  `data` date NOT NULL,
  `reinvindicada` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=25 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `colecoes`
--

CREATE TABLE IF NOT EXISTS `colecoes` (
  `id` int NOT NULL AUTO_INCREMENT,
  `criador` int NOT NULL,
  `nome` text NOT NULL,
  `descricao` text NOT NULL,
  `data` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

ALTER TABLE `colecoes` ADD `thumbnail` TEXT NULL DEFAULT NULL AFTER `data`; 

-- --------------------------------------------------------

--
-- Estrutura para tabela `colecoes_curadores`
--

CREATE TABLE IF NOT EXISTS `colecoes_curadores` (
  `id` int NOT NULL AUTO_INCREMENT,
  `id_colecao` int NOT NULL,
  `id_curador` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `colecoes_projetos`
--

CREATE TABLE IF NOT EXISTS `colecoes_projetos` (
  `id` int NOT NULL AUTO_INCREMENT,
  `id_projeto` int NOT NULL,
  `id_colecao` int NOT NULL,
  `id_adicionador` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

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
) ENGINE=MyISAM AUTO_INCREMENT=98 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

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
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `daveitens`
--

CREATE TABLE IF NOT EXISTS `daveitens` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nome` varchar(255) NOT NULL,
  `descricao` text NOT NULL,
  `daveprice` int NOT NULL,
  `imagem` text NOT NULL,
  `compravel` tinyint(1) NOT NULL DEFAULT '1',
  `consumivel` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `daveniveis`
--

CREATE TABLE IF NOT EXISTS `daveniveis` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nome` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `imagem` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `davecoins_proximo` int NOT NULL,
  `diada` int NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `daveniveis`
--

INSERT INTO `daveniveis` (`id`, `nome`, `imagem`, `davecoins_proximo`, `diada`) VALUES
(1, 'Novo Especulador', 'novo.png', 10, 1),
(2, 'Especulador', 'especulador.png', 25, 1),
(3, 'Especulativo', 'especulativo.png', 75, 1),
(4, 'Apostador', 'apostador.png', 125, 1),
(5, 'Bicheiro', 'bicheiro.png', 200, 1),
(6, 'Investidor', 'investidor.png', 300, 1),
(7, 'Empreendedor em Potencial', 'empreendedor_potencial.png', 450, 1),
(8, 'Empreendedor em Realidade', 'empreendedor_real.png', 600, 1),
(9, 'Milhário', 'milhario.png', 1000, 1),
(10, 'Milionário', 'milionario.png', 1500, 1),
(11, 'Especulionário', 'especulionario.png', 2022, 1),
(12, 'Lagartixa', 'lagartixa.png', 2500, 1),
(13, 'Lagarto', 'lagarto.png', 2750, 1),
(14, 'Lagaaaaaarto', 'lagaaaaaarto.png', 3000, 1),
(15, 'Rinoceronte', 'rinoceronte.png', 4000, 1),
(16, 'Draguinho', 'draguinho.png', 4200, 1),
(17, 'Dragão', 'dragao.png', 4400, 1),
(18, 'Dragão Ancião', 'dragao_anciao.png', 4600, 1),
(19, 'Dragão Geriátrico', 'dragao_geriatrico.png', 4800, 1),
(20, 'Dragão Cremado', 'dragao_cremado.png', 5000, 1),
(21, 'Super Saia Jeans Deus', 'supersaiajeansdeus.png', 7000, 1),
(22, 'Ultra Saia Jeans Instinto', 'ultrasupersaiajeansinstinto.png', 8001, 1),
(23, 'Gorila', 'gorila.png', 10000, 1);

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
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Despejando dados para a tabela `forum_categorias`
--

INSERT INTO `forum_categorias` (`id`, `nome`, `descricao`, `tipoDeTopico`) VALUES
(1, 'Avisos', 'Saiba o que a equipe do Especulamente está planejando!', 0),
(2, 'Categoria 2', 'teste wuaa', 1);

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
) ENGINE=MyISAM AUTO_INCREMENT=61 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `inventario`
--

CREATE TABLE IF NOT EXISTS `inventario` (
  `id` int NOT NULL AUTO_INCREMENT,
  `id_usuario` int NOT NULL,
  `id_item` int NOT NULL,
  `dados` varchar(1024) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

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
) ENGINE=MyISAM AUTO_INCREMENT=125 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

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
  `thumbnail` text,
  `data` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `dataBump` datetime DEFAULT NULL,
  `naolist` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=62 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `reacoes`
--

CREATE TABLE IF NOT EXISTS `reacoes` (
  `id` int NOT NULL AUTO_INCREMENT,
  `tipo_de_reacao` enum('mitada','sojada') NOT NULL,
  `id_reator` int NOT NULL,
  `tipo_de_reagido` enum('perfil','projeto','forum') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `id_reagido` int NOT NULL,
  `data` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=214 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

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
  `davecoins` int NOT NULL DEFAULT '0',
  `assinatura` text,
  `css` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
