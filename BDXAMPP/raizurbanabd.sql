-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3307
-- Tempo de geração: 25/06/2025 às 02:32
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
-- Banco de dados: `raizurbanabd`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `cadusuarios`
--

CREATE TABLE `cadusuarios` (
  `idUsuario` int(11) NOT NULL,
  `nomeUsuario` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `cpf` varchar(15) NOT NULL,
  `genero` varchar(255) NOT NULL,
  `dataNasc` date NOT NULL,
  `senha` varchar(255) NOT NULL,
  `confirmarSenha` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `cadusuarios`
--

INSERT INTO `cadusuarios` (`idUsuario`, `nomeUsuario`, `email`, `cpf`, `genero`, `dataNasc`, `senha`, `confirmarSenha`) VALUES
(1, 'Felipe da Silva Machado', 'felipe@felipe.com.br', '398.325.328-84', 'Masculino', '1991-09-10', '$2y$10$IkHEXsonjzKluTJJCHyNF.oX328.4uEajdl1bxSVLCsL748O6Auge', ''),
(2, 'Camilla Singnorini Vieira', 'camilla@camilla.com.br', '777.777.777-77', 'Feminino', '1991-08-25', '$2y$10$toZW3hk4SbwCXt.HOYVYNe4n5uvKwR5b8vI1/nLixBydzGGb/XBjy', '');

-- --------------------------------------------------------

--
-- Estrutura para tabela `endereco`
--

CREATE TABLE `endereco` (
  `idEndereco` int(11) NOT NULL,
  `fk_idUsuario` int(11) NOT NULL,
  `rua` varchar(255) NOT NULL,
  `numero` varchar(10) NOT NULL,
  `bairro` varchar(255) NOT NULL,
  `cidade` varchar(255) NOT NULL,
  `uf` char(2) NOT NULL,
  `cep` varchar(8) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `endereco`
--

INSERT INTO `endereco` (`idEndereco`, `fk_idUsuario`, `rua`, `numero`, `bairro`, `cidade`, `uf`, `cep`) VALUES
(1, 1, 'Rua', '777', 'bairro', 'cidade', 'uf', '07190-06'),
(2, 2, 'Rua', '154', 'bairro1', 'cidade1', 'uf', '07190-06');

-- --------------------------------------------------------

--
-- Estrutura para tabela `telefones`
--

CREATE TABLE `telefones` (
  `idFone` int(11) NOT NULL,
  `fk_idUsuario` int(11) NOT NULL,
  `telefone` varchar(20) DEFAULT NULL,
  `celular` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `telefones`
--

INSERT INTO `telefones` (`idFone`, `fk_idUsuario`, `telefone`, `celular`) VALUES
(1, 1, '1124087261', '(11) 98921-7437'),
(2, 2, '', '77777777777');

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `cadusuarios`
--
ALTER TABLE `cadusuarios`
  ADD PRIMARY KEY (`idUsuario`);

--
-- Índices de tabela `endereco`
--
ALTER TABLE `endereco`
  ADD PRIMARY KEY (`idEndereco`),
  ADD KEY `fk_idUsuario_cadUsuarioEnd` (`fk_idUsuario`);

--
-- Índices de tabela `telefones`
--
ALTER TABLE `telefones`
  ADD PRIMARY KEY (`idFone`),
  ADD KEY `fk_idUsuario_cadUsuario` (`fk_idUsuario`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `cadusuarios`
--
ALTER TABLE `cadusuarios`
  MODIFY `idUsuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de tabela `endereco`
--
ALTER TABLE `endereco`
  MODIFY `idEndereco` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de tabela `telefones`
--
ALTER TABLE `telefones`
  MODIFY `idFone` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Restrições para tabelas despejadas
--

--
-- Restrições para tabelas `endereco`
--
ALTER TABLE `endereco`
  ADD CONSTRAINT `fk_idUsuario_cadUsuarioEnd` FOREIGN KEY (`fk_idUsuario`) REFERENCES `cadusuarios` (`idUsuario`);

--
-- Restrições para tabelas `telefones`
--
ALTER TABLE `telefones`
  ADD CONSTRAINT `fk_idUsuario_cadUsuario` FOREIGN KEY (`fk_idUsuario`) REFERENCES `cadusuarios` (`idUsuario`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
