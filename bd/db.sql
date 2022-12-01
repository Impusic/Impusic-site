--
-- Banco de dados: `impusic`
--
CREATE DATABASE IF NOT EXISTS `impusic` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `impusic`;

-- --------------------------------------------------------

--
-- Estrutura da tabela `channel`
--

DROP TABLE IF EXISTS `channel`;
CREATE TABLE `channel` (
  `id` int(11) NOT NULL,
  `user` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `date` varchar(255) NOT NULL,
  `location` varchar(255) NOT NULL,
  `spotify` varchar(255) NOT NULL,
  `soundcloud` varchar(255) NOT NULL,
  `instagram` varchar(255) NOT NULL,
  `facebook` varchar(255) NOT NULL,
  `discord` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estrutura da tabela `comments`
--

CREATE TABLE `comments` (
  `id` int(11) NOT NULL,
  `videoId` varchar(255) DEFAULT NULL,
  `text` varchar(500) NOT NULL,
  `edited` tinyint(1) NOT NULL,
  `time` varchar(255) DEFAULT NULL,
  `commentName` varchar(255) DEFAULT NULL,
  `commentUsername` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estrutura da tabela `follows`
--

DROP TABLE IF EXISTS `follows`;
CREATE TABLE `follows` (
  `id` int(11) NOT NULL,
  `idFollow` int(11) NOT NULL,
  `idUser` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estrutura da tabela `videos`
--

DROP TABLE IF EXISTS `videos`;
CREATE TABLE `videos` (
  `id` int(11) NOT NULL,
  `title` varchar(100) NOT NULL,
  `description` varchar(5000) NOT NULL,
  `channel` varchar(255) NOT NULL,
  `channelUser` varchar(255) NOT NULL,
  `channelId` int(255) NOT NULL,
  `thumbnail` varchar(255) NOT NULL,
  `video` varchar(255) NOT NULL,
  `musicId` int(11) NOT NULL,
  `date` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Índices para tabelas despejadas
--

--
-- Índices para tabela `channel`
--
ALTER TABLE `channel`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `follows`
--
ALTER TABLE `follows`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idUser` (`idUser`),
  ADD KEY `idFollow` (`idFollow`,`idUser`) USING BTREE;

--
-- Índices para tabela `videos`
--
ALTER TABLE `videos`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `channel`
--
ALTER TABLE `channel`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de tabela `follows`
--
ALTER TABLE `follows`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT de tabela `videos`
--
ALTER TABLE `videos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `comments`
--
ALTER TABLE `comments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;
  
--
-- Restrições para despejos de tabelas
--

--
-- Limitadores para a tabela `follows`
--
ALTER TABLE `follows`
  ADD CONSTRAINT `follows_ibfk_1` FOREIGN KEY (`idUser`) REFERENCES `channel` (`id`),
  ADD CONSTRAINT `follows_ibfk_2` FOREIGN KEY (`idFollow`) REFERENCES `channel` (`id`);
COMMIT;