-- --------------------------------------------------------
-- Servidor:                     127.0.0.1
-- Versão do servidor:           5.6.24 - MySQL Community Server (GPL)
-- OS do Servidor:               Win32
-- HeidiSQL Versão:              9.4.0.5125
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;


-- Copiando estrutura do banco de dados para aj_idoso
DROP DATABASE IF EXISTS `aj_idoso`;
CREATE DATABASE IF NOT EXISTS `aj_idoso` /*!40100 DEFAULT CHARACTER SET latin1 */;
USE `aj_idoso`;

-- Copiando estrutura para tabela aj_idoso.admin
DROP TABLE IF EXISTS `admin`;
CREATE TABLE IF NOT EXISTS `admin` (
  `id` int(3) NOT NULL AUTO_INCREMENT,
  `nome` varchar(20) COLLATE utf8_swedish_ci NOT NULL,
  `senha` varchar(20) COLLATE utf8_swedish_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_swedish_ci;

-- Copiando dados para a tabela aj_idoso.admin: ~0 rows (aproximadamente)
/*!40000 ALTER TABLE `admin` DISABLE KEYS */;
/*!40000 ALTER TABLE `admin` ENABLE KEYS */;

-- Copiando estrutura para tabela aj_idoso.aj_videos
DROP TABLE IF EXISTS `aj_videos`;
CREATE TABLE IF NOT EXISTS `aj_videos` (
  `id_video` int(4) unsigned NOT NULL AUTO_INCREMENT,
  `nome` varchar(80) COLLATE utf8_swedish_ci NOT NULL,
  `url` varchar(500) COLLATE utf8_swedish_ci NOT NULL,
  `pl_chave` varchar(150) COLLATE utf8_swedish_ci NOT NULL,
  `dat_cad` datetime NOT NULL,
  `ativo` tinyint(4) DEFAULT '1',
  PRIMARY KEY (`id_video`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_swedish_ci;

-- Copiando dados para a tabela aj_idoso.aj_videos: ~6 rows (aproximadamente)
/*!40000 ALTER TABLE `aj_videos` DISABLE KEYS */;
REPLACE INTO `aj_videos` (`id_video`, `nome`, `url`, `pl_chave`, `dat_cad`, `ativo`) VALUES
	(2, 'login facebook', 'https://www.youtube.com/watch?v=dRhYTim8bVg', 'login, facebook', '2017-08-28 21:55:13', 1),
	(3, 'excluir facebook', 'https://www.youtube.com/watch?v=AcIVSbqByWM', 'excluir facebook', '2017-08-28 22:12:53', 1),
	(6, 'sdf', 'ded', 'sdfsd', '2017-09-25 20:44:15', 1),
	(7, 'sdf', 'ded', 'sdfsd', '2017-09-25 20:45:11', 1),
	(8, 'Criar conta no skype', 'https://www.youtube.com/watch?v=fHzB6WbQwTU', 'skype', '2017-09-25 21:06:59', 1),
	(9, 'winrar', 'https://www.youtube.com/watch?v=6ljmJQ8BbUM', 'winrar', '2017-09-25 22:04:10', 1);
/*!40000 ALTER TABLE `aj_videos` ENABLE KEYS */;

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
