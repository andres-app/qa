-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Versión del servidor:         8.0.30 - MySQL Community Server - GPL
-- SO del servidor:              Win64
-- HeidiSQL Versión:             12.1.0.6537
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Volcando estructura de base de datos para mesadepartes
CREATE DATABASE IF NOT EXISTS `mesadepartes` /*!40100 DEFAULT CHARACTER SET utf8mb3 COLLATE utf8mb3_spanish_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `mesadepartes`;

-- Volcando estructura para tabla mesadepartes.tm_menu
CREATE TABLE IF NOT EXISTS `tm_menu` (
  `men_id` int NOT NULL AUTO_INCREMENT,
  `men_nom` varchar(200) COLLATE utf8mb3_spanish_ci DEFAULT NULL,
  `men_nom_vista` varchar(200) COLLATE utf8mb3_spanish_ci DEFAULT NULL,
  `men_icon` varchar(200) COLLATE utf8mb3_spanish_ci DEFAULT NULL,
  `men_ruta` varchar(200) COLLATE utf8mb3_spanish_ci DEFAULT NULL,
  `fech_crea` datetime DEFAULT CURRENT_TIMESTAMP,
  `fech_modi` datetime DEFAULT NULL,
  `fech_elim` datetime DEFAULT NULL,
  `est` int DEFAULT '1',
  PRIMARY KEY (`men_id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_spanish_ci;

-- Volcando datos para la tabla mesadepartes.tm_menu: ~11 rows (aproximadamente)
DELETE FROM `tm_menu`;
INSERT INTO `tm_menu` (`men_id`, `men_nom`, `men_nom_vista`, `men_icon`, `men_ruta`, `fech_crea`, `fech_modi`, `fech_elim`, `est`) VALUES
	(1, 'home', 'Inicio', 'home', '../home/', '2023-12-05 12:15:29', NULL, NULL, 1),
	(2, 'nuevotramite', 'Nuevo Tramite', 'grid', '../NuevoTramite/', '2023-12-05 12:15:47', NULL, NULL, 1),
	(3, 'consultartramite', 'Consultar Tramite', 'users', '../ConsultarTramite/', '2023-12-05 12:16:19', NULL, NULL, 1),
	(4, 'iniciocolaborador', 'Inicio Colaborador', 'home', '../homecolaborador/', '2023-12-05 12:16:46', NULL, NULL, 1),
	(5, 'gestionartramite', 'Gestionar Tramite', 'grid', '../gestionartramite/', '2023-12-05 12:17:08', NULL, NULL, 1),
	(6, 'buscartramite', 'Buscar Tramite', 'users', '../buscartramite/', '2023-12-05 12:17:32', NULL, NULL, 1),
	(7, 'mntcolaborador', 'Mnt.Colaborador', 'users', '../mntusuario/', '2023-12-05 12:18:06', NULL, NULL, 1),
	(8, 'mntarea', 'Mnt.Area', 'users', '../mntarea/', '2023-12-05 12:18:27', NULL, NULL, 1),
	(9, 'mnttramite', 'Mnt.Tramite', 'users', '../mnttramite/', '2023-12-05 12:18:51', NULL, NULL, 1),
	(10, 'mnttipo', 'Mnt.Tipo', 'users', '../mnttipo/', '2023-12-05 12:19:16', NULL, NULL, 1),
	(11, 'mntrol', 'Mnt.Rol', 'users', '../mntrol/', '2023-12-05 12:19:34', NULL, NULL, 1);

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
