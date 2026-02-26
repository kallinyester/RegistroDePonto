--
-- Banco de dados: `ponto`
-- Exportado

-- --------------------------------------------------------

CREATE TABLE `evento` (
  `id` int(4) NOT NULL,
  `evento` varchar(100) NOT NULL,
  `data` date NOT NULL,
  `hora_inicio` time DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dados da tabela `evento`
--

INSERT INTO `evento` (`id`, `evento`, `data`, `hora_inicio`) VALUES
(4, 'evento aaaa', '2025-06-15', '15:00:00'),
(5, 'Teste evento', '2025-06-11', '19:30:00'),
(6, 'Evento legal', '2025-06-18', '14:00:00'),
(7, 'teste horas', '2025-06-13', '11:00:00'),
(8, 'teste hojeee', '2025-06-16', '16:50:00'),
(9, 'teste 17/06', '2025-06-17', '05:30:00'),
(10, 'teste 17/06 horas', '2025-06-17', '08:00:00');

-- --------------------------------------------------------

CREATE TABLE `funcionarios` (
  `id` int(4) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `situacao` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dados da tabela `funcionarios`
--

INSERT INTO `funcionarios` (`id`, `nome`, `situacao`) VALUES
(1, 'Adrielly Cristine Barcelos Silva', 'efetivo'),
(2, 'Ana Júlia Fares Casagrande', 'efetivo'),
(3, 'Ana Maria Marques Silveira', 'efetivo'),
(4, 'Bruno Agapito Moraes', 'efetivo'),
(5, 'Cecília Mamede Motta', 'efetivo'),
(6, 'Fabiane Iage Silva', 'efetivo'),
(7, 'Fernanda Cristina Faria Almeida de Paula', 'efetivo'),
(8, 'Fernnanda Queiroz Calmon Almeida Gomes', 'efetivo'),
(9, 'Gabriela Cavalheiro Bassi', 'efetivo'),
(10, 'Gustavo Verçosa Rezende', 'efetivo'),
(11, 'Higor Elias Lopes De Andrade', 'efetivo'),
(12, 'Isabella Teodoro de Andrade', 'efetivo'),
(14, 'Luana Sant’anna Lula Maciel', 'efetivo'),
(15, 'Maria Eduarda Resende Nascimento', 'efetivo'),
(16, 'Matheus Eduardo Coelho', 'efetivo'),
(17, 'Matheus Henrique Araújo de Carvalho', 'efetivo'),
(18, 'Solano Anute Furtado', 'efetivo'),
(19, 'Tarsila Medina Teixeira', 'efetivo'),
(20, 'Tiago Alexandre Malaquias', 'efetivo'),
(21, 'Vítor Luís Tendolini da Silva', 'efetivo'),
(22, 'Yasmin dos Reis Maurício', 'efetivo'),
(27, 'Beatriz Roque Brugin', 'efetivo'),
(29, 'Daniel Carlos Medeiros Alves', 'trainee'),
(30, 'Diogo dos Santos Tiago', 'trainee'),
(32, 'Isadora do Vale Rezende', 'trainee'),
(34, 'João Paulo Guimarães', 'trainee'),
(35, 'João Vitor de Oliveira Botelho', 'efetivo'),
(36, 'João Vitor Romão Paulino', 'efetivo'),
(37, 'Lais Suitt Gomides', 'trainee'),
(38, 'Lanna Maria Vital Almeida Messias', 'trainee'),
(39, 'Leandra Sousa Araújo', 'efetivo'),
(41, 'Luisa Cavalcanti Brandão da Fonseca', 'trainee'),
(47, 'Maria Paula Kitagawa', 'efetivo'),
(49, 'Nicole Christine Silva', 'trainee'),
(50, 'Nicollas Ferreira Santos Rocha', 'trainee'),
(53, 'Yasmin Carolyne Souza Soares', 'trainee');

-- --------------------------------------------------------

CREATE TABLE `funcionarios_evento` (
  `id` int(4) NOT NULL,
  `evento_id` int(4) NOT NULL,
  `funcionarios_id` int(4) DEFAULT NULL,
  `data` date DEFAULT NULL,
  `hora_entrada` time DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dados da tabela `funcionarios_evento`
--

INSERT INTO `funcionarios_evento` (`id`, `evento_id`, `funcionarios_id`, `data`, `hora_entrada`) VALUES
(6, 9, 32, '2025-06-17', '05:59:40'),
(7, 9, 29, '2025-06-17', '06:00:19'),
(8, 9, 30, '2025-06-17', '06:00:50'),
(9, 9, 34, '2025-06-17', '06:01:27'),
(10, 10, 16, '2025-06-17', '16:36:04');

-- --------------------------------------------------------

CREATE TABLE `funcionarios_rg` (
  `id` int(4) NOT NULL,
  `funcionarios_id` int(4) DEFAULT NULL,
  `data` date DEFAULT NULL,
  `hora_entrada` time DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dados da tabela `funcionarios_rg`
--

INSERT INTO `funcionarios_rg` (`id`, `funcionarios_id`, `data`, `hora_entrada`) VALUES
(2, 12, '2025-05-22', '15:16:38'),
(3, 1, '2025-06-03', '19:00:12'),
(4, 2, '2025-06-03', '18:59:04'),
(22, 3, '2025-06-03', '17:59:06'),
(25, 27, '2025-06-03', '18:05:24'),
(26, 4, '2025-06-03', '17:56:02'),
(28, 5, '2025-06-03', '17:58:26'),
(29, 35, '2025-06-03', '18:53:42'),
(30, 8, '2025-06-03', '17:57:36'),
(43, 22, '2025-06-10', '18:18:00'),
(45, 19, '2025-07-01', '19:45:38'),
(46, 12, '2025-07-01', '19:46:20'),
(47, 17, '2025-07-01', '19:46:43');

-- --------------------------------------------------------

CREATE TABLE `funcionarios_sede` (
  `id` int(4) NOT NULL,
  `funcionarios_id` int(4) DEFAULT NULL,
  `data` date DEFAULT NULL,
  `hora_entrada` time DEFAULT NULL,
  `hora_saida` time DEFAULT NULL,
  `total_horas_dia` time DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dados da tabela `funcionarios_sede`
--

INSERT INTO `funcionarios_sede` (`id`, `funcionarios_id`, `data`, `hora_entrada`, `hora_saida`, `total_horas_dia`) VALUES
(1, 1, '2025-06-02', '12:50:54', '13:53:16', '01:02:22'),
(2, 2, '2025-06-02', '12:51:06', '14:02:48', '01:11:42'),
(3, 3, '2025-06-02', '12:51:12', '14:02:58', '01:11:46'),
(4, 4, '2025-06-02', '12:51:22', '15:02:22', '02:11:00'),
(6, 4, '2025-06-05', '13:10:12', '16:11:08', '03:00:56'),
(8, 6, '2025-06-10', '12:57:48', '14:53:08', '01:55:20'),
(9, 14, '2025-06-10', '13:26:00', '17:26:00', '04:00:00'),
(10, 15, '2025-06-10', '13:26:28', '17:00:00', '03:33:32'),
(11, 10, '2025-06-10', '15:07:58', '17:07:58', '02:00:00'),
(12, 1, '2025-06-15', '22:46:28', '23:15:02', '00:28:34'),
(13, 50, '2025-06-15', '22:54:42', '23:54:42', '01:00:00'),
(14, 16, '2025-06-15', '23:05:12', '23:55:12', '00:50:00'),
(15, 15, '2025-06-16', '00:47:01', '02:41:43', '01:54:42'),
(17, 1, '2025-06-17', '16:30:42', '18:30:42', '02:00:00'),
(18, 29, '2025-06-17', '16:31:07', NULL, NULL),
(19, 18, '2025-06-24', '20:05:12', '20:11:08', '00:05:56'),
(20, 18, '2025-06-24', '20:06:37', '20:11:08', '00:05:56'),
(21, 6, '2025-06-24', '20:11:33', '20:48:53', '00:37:20'),
(22, 6, '2025-06-24', '20:13:30', '20:14:05', '00:02:32'),
(23, 4, '2025-06-24', '20:19:29', '20:39:36', '00:20:07'),
(24, 6, '2025-06-24', '20:37:16', '22:00:00', '01:22:44'),
(25, 1, '2025-06-26', '09:16:36', '11:18:02', '02:01:26'),
(26, 1, '2025-06-26', '19:18:40', '21:18:56', '02:00:16'),
(27, 1, '2025-06-26', '08:25:42', '10:27:54', '02:02:12'),
(28, 21, '2025-06-26', '11:18:01', NULL, NULL),
(29, 9, '2025-06-29', '22:17:21', '22:17:43', '00:00:22'),
(30, 16, '2025-06-29', '22:17:51', '22:18:49', '00:00:58'),
(31, 17, '2025-06-29', '22:19:16', '22:20:13', '00:00:57'),
(32, 10, '2025-06-29', '22:20:50', '23:00:04', '00:39:14'),
(33, 18, '2025-06-30', '14:15:22', '15:15:40', '01:00:18'),
(34, 18, '2025-06-30', '16:15:52', '18:16:48', '02:00:56'),
(35, 18, '2025-07-01', '19:43:27', NULL, NULL);

-- --------------------------------------------------------

CREATE TABLE `tokens` (
  `id` int(11) NOT NULL,
  `token` int(11) NOT NULL,
  `usado` tinyint(1) DEFAULT 0,
  `criado_em` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dados da tabela `tokens`
--

INSERT INTO `tokens` (`id`, `token`, `usado`, `criado_em`) VALUES
(321, 4973398, 0, '2025-07-01 19:27:12');

-- --------------------------------------------------------

CREATE TABLE `totais_semanais` (
  `id` int(11) NOT NULL,
  `funcionarios_id` int(11) DEFAULT NULL,
  `semana` int(11) DEFAULT NULL,
  `ano` int(11) DEFAULT NULL,
  `total_horas` time DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dados da tabela `totais_semanais`
--

INSERT INTO `totais_semanais` (`id`, `funcionarios_id`, `semana`, `ano`, `total_horas`) VALUES
(1, 1, 23, 2025, '01:02:22'),
(2, 2, 23, 2025, '01:11:42'),
(3, 3, 23, 2025, '01:11:46'),
(4, 4, 23, 2025, '05:11:56'),
(6, 15, 25, 2025, '01:54:42'),
(7, 6, 24, 2025, '01:55:20'),
(8, 1, 24, 2025, '00:28:34'),
(9, 10, 24, 2025, '02:00:00'),
(11, 14, 24, 2025, '04:00:00'),
(12, 16, 24, 2025, '00:50:00'),
(13, 50, 24, 2025, '01:00:00'),
(15, 18, 26, 2025, '00:11:52'),
(16, 6, 26, 2025, '00:39:52'),
(17, 4, 26, 2025, '00:20:07'),
(18, 1, 26, 2025, '00:03:57'),
(19, 9, 26, 2025, '00:00:22'),
(20, 16, 26, 2025, '00:00:58'),
(21, 17, 26, 2025, '00:00:57'),
(22, 18, 26, 2025, '00:01:12'),
(23, 18, 27, 2025, '03:01:14'),
(24, 10, 26, 2025, '00:39:14');

-- --------------------------------------------------------

CREATE TABLE `usuario` (
  `id` int(4) NOT NULL,
  `usuario` varchar(50) NOT NULL,
  `senha` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dados da tabela `usuario`
--

INSERT INTO `usuario` (`id`, `usuario`, `senha`) VALUES
(1, 'admin', 'admin');

--
-- Índices para tabelas despejadas
--

--
-- Índices para tabela `evento`
--
ALTER TABLE `evento`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `funcionarios`
--
ALTER TABLE `funcionarios`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `funcionarios_evento`
--
ALTER TABLE `funcionarios_evento`
  ADD PRIMARY KEY (`id`),
  ADD KEY `evento_id` (`evento_id`),
  ADD KEY `funcionarios_id` (`funcionarios_id`);

--
-- Índices para tabela `funcionarios_rg`
--
ALTER TABLE `funcionarios_rg`
  ADD PRIMARY KEY (`id`),
  ADD KEY `funcionarios_id` (`funcionarios_id`);

--
-- Índices para tabela `funcionarios_sede`
--
ALTER TABLE `funcionarios_sede`
  ADD PRIMARY KEY (`id`),
  ADD KEY `funcionarios_id` (`funcionarios_id`);

--
-- Índices para tabela `tokens`
--
ALTER TABLE `tokens`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `totais_semanais`
--
ALTER TABLE `totais_semanais`
  ADD PRIMARY KEY (`id`),
  ADD KEY `funcionarios_id` (`funcionarios_id`);

--
-- Índices para tabela `usuario`
--
ALTER TABLE `usuario`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `evento`
--
ALTER TABLE `evento`
  MODIFY `id` int(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de tabela `funcionarios`
--
ALTER TABLE `funcionarios`
  MODIFY `id` int(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=63;

--
-- AUTO_INCREMENT de tabela `funcionarios_evento`
--
ALTER TABLE `funcionarios_evento`
  MODIFY `id` int(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT de tabela `funcionarios_rg`
--
ALTER TABLE `funcionarios_rg`
  MODIFY `id` int(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=48;

--
-- AUTO_INCREMENT de tabela `funcionarios_sede`
--
ALTER TABLE `funcionarios_sede`
  MODIFY `id` int(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT de tabela `tokens`
--
ALTER TABLE `tokens`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=322;

--
-- AUTO_INCREMENT de tabela `totais_semanais`
--
ALTER TABLE `totais_semanais`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT de tabela `usuario`
--
ALTER TABLE `usuario`
  MODIFY `id` int(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Restrições para despejos de tabelas
--

--
-- Limitadores para a tabela `funcionarios_evento`
--
ALTER TABLE `funcionarios_evento`
  ADD CONSTRAINT `funcionarios_evento_ibfk_1` FOREIGN KEY (`evento_id`) REFERENCES `evento` (`id`),
  ADD CONSTRAINT `funcionarios_evento_ibfk_2` FOREIGN KEY (`funcionarios_id`) REFERENCES `funcionarios` (`id`);

--
-- Limitadores para a tabela `funcionarios_rg`
--
ALTER TABLE `funcionarios_rg`
  ADD CONSTRAINT `funcionarios_rg_ibfk_1` FOREIGN KEY (`funcionarios_id`) REFERENCES `funcionarios` (`id`);

--
-- Limitadores para a tabela `funcionarios_sede`
--
ALTER TABLE `funcionarios_sede`
  ADD CONSTRAINT `funcionarios_sede_ibfk_1` FOREIGN KEY (`funcionarios_id`) REFERENCES `funcionarios` (`id`);

--
-- Limitadores para a tabela `totais_semanais`
--
ALTER TABLE `totais_semanais`
  ADD CONSTRAINT `totais_semanais_ibfk_1` FOREIGN KEY (`funcionarios_id`) REFERENCES `funcionarios` (`id`);
COMMIT;
