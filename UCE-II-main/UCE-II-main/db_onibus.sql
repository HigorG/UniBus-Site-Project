

DROP TABLE IF EXISTS `administrador`;
CREATE TABLE IF NOT EXISTS `administrador` (
  `ID` int NOT NULL AUTO_INCREMENT,
  `SENHA_ADM` varchar(15) NOT NULL,
  `USUARIO_ADM` varchar(45) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Despejando dados para a tabela `administrador`
--

INSERT INTO `administrador` (`ID`, `SENHA_ADM`, `USUARIO_ADM`) VALUES
(1, '1525', 'davi'),
(4, '1234', 'davi@gmail.com');

-- --------------------------------------------------------

--
-- Estrutura para tabela `estudante`
--

DROP TABLE IF EXISTS `estudante`;
CREATE TABLE IF NOT EXISTS `estudante` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nome` varchar(100) DEFAULT NULL,
  `endereco` varchar(255) DEFAULT NULL,
  `telefone` varchar(15) DEFAULT NULL,
  `faculdade` varchar(100) DEFAULT NULL,
  `numero_matricula` varchar(20) DEFAULT NULL,
  `nome_rota` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `nome_rota` (`nome_rota`)
) ENGINE=MyISAM AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Despejando dados para a tabela `estudante`
--

INSERT INTO `estudante` (`id`, `nome`, `endereco`, `telefone`, `faculdade`, `numero_matricula`, `nome_rota`) VALUES
(16, 'Davi', 'Tianguá', '88999999', 'Fied', '1234', 'Fied'),
(17, 'Hernandes', 'São Benedito', 'teste', 'Uninta', '1525', 'Fied'),
(15, 'Heitor', 'Teste', '99999999', 'Fied', '12345', 'Teste');


-- --------------------------------------------------------

--
-- Estrutura para tabela `horario`
--

DROP TABLE IF EXISTS `horario`;
CREATE TABLE IF NOT EXISTS `horario` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nome_rota` varchar(100) DEFAULT NULL,
  `dias_semana` set('Segunda','Terça','Quarta','Quinta','Sexta','Sábado','Domingo') DEFAULT NULL,
  `hora_saida` time DEFAULT NULL,
  `hora_chegada` time DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `nome_rota` (`nome_rota`)
) ENGINE=MyISAM AUTO_INCREMENT=22 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Despejando dados para a tabela `horario`
--

INSERT INTO `horario` (`id`, `nome_rota`, `dias_semana`, `hora_saida`, `hora_chegada`) VALUES
(1, 'Teste', 'Segunda', '17:30:00', '21:45:00'),
(2, 'Teste', 'Terça', '00:00:00', '00:00:00'),
(3, 'Teste', 'Quarta', '00:00:00', '00:00:00'),
(4, 'Teste', 'Quinta', '00:00:00', '00:00:00'),
(5, 'Teste', 'Sexta', '00:00:00', '00:00:00'),
(6, 'Teste', 'Sábado', '00:00:00', '00:00:00'),
(7, 'Teste', 'Domingo', '00:00:00', '00:00:00'),
(8, 'Uninta', 'Segunda', '18:15:00', '21:45:00'),
(9, 'Uninta', 'Terça', '21:45:00', '10:00:00'),
(10, 'Uninta', 'Quarta', '00:00:00', '00:00:00'),
(11, 'Uninta', 'Quinta', '00:00:00', '00:00:00'),
(12, 'Uninta', 'Sexta', '00:00:00', '00:00:00'),
(13, 'Uninta', 'Sábado', '00:00:00', '00:00:00'),
(14, 'Uninta', 'Domingo', '00:00:00', '00:00:00'),
(15, 'Teste2', 'Segunda', '18:15:00', '21:00:00'),
(16, 'Teste2', 'Quarta', '22:00:00', '08:00:00'),
(17, 'Teste', 'Segunda', '15:00:00', '16:00:00'),
(18, 'Fied', 'Segunda', '17:30:00', '21:30:00'),
(19, 'Fied', 'Terça', '17:30:00', '21:30:00'),
(20, 'Fied', 'Quarta', '17:30:00', '21:30:00'),
(21, 'Fied', 'Quinta', '17:30:00', '21:30:00');


-- --------------------------------------------------------

--
-- Estrutura para tabela `motorista`
--

DROP TABLE IF EXISTS `motorista`;
CREATE TABLE IF NOT EXISTS `motorista` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nome` varchar(100) DEFAULT NULL,
  `telefone` varchar(15) DEFAULT NULL,
  `rota_id` int DEFAULT NULL,
  `placa_onibus` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `rota_id` (`rota_id`),
  KEY `placa_onibus` (`placa_onibus`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `onibus`
--

DROP TABLE IF EXISTS `onibus`;
CREATE TABLE IF NOT EXISTS `onibus` (
  `id` int NOT NULL AUTO_INCREMENT,
  `placa` varchar(10) DEFAULT NULL,
  `modelo` varchar(50) DEFAULT NULL,
  `capacidade` int DEFAULT NULL,
  `nome_rota` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `placa` (`placa`),
  KEY `nome_rota` (`nome_rota`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `organizador`
--

DROP TABLE IF EXISTS `organizador`;
CREATE TABLE IF NOT EXISTS `organizador` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nome` varchar(100) DEFAULT NULL,
  `telefone` varchar(15) DEFAULT NULL,
  `rota_id` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `rota_id` (`rota_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `rota`
--

DROP TABLE IF EXISTS `rota`;
CREATE TABLE IF NOT EXISTS `rota` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nome_rota` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `nome_rota` (`nome_rota`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Despejando dados para a tabela `rota`
--

INSERT INTO `rota` (`id`, `nome_rota`) VALUES
(6, 'Fied'),
(5, 'Teste');



-- --------------------------------------------------------

--
-- Estrutura para tabela `usuarios`
--

DROP TABLE IF EXISTS `usuarios`;
CREATE TABLE IF NOT EXISTS `usuarios` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nome` varchar(100) NOT NULL,
  `usuario` varchar(50) NOT NULL,
  `senha` varchar(255) NOT NULL,
  `tipo` enum('administrador','usuario') NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `usuario` (`usuario`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Restrições para tabelas despejadas
--

--
-- Restrições para tabelas `estudantes`
--
ALTER TABLE `estudantes`
  ADD CONSTRAINT `fk_estudantes_rotas` FOREIGN KEY (`rota_id`) REFERENCES `rotas` (`ID`) ON DELETE CASCADE;

--
-- Restrições para tabelas `horarios_estudantes`
--
ALTER TABLE `horarios_estudantes`
  ADD CONSTRAINT `fk_horarios_estudantes_estudantes` FOREIGN KEY (`ESTUDANTE_ID`) REFERENCES `estudantes` (`ID`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_horarios_estudantes_rotas` FOREIGN KEY (`ROTA_ID`) REFERENCES `rotas` (`ID`) ON DELETE CASCADE;

--
-- Restrições para tabelas `horarios_rotas`
--
ALTER TABLE `horarios_rotas`
  ADD CONSTRAINT `fk_horarios_rotas_rotas` FOREIGN KEY (`rota_id`) REFERENCES `rotas` (`ID`) ON DELETE CASCADE;
COMMIT;


