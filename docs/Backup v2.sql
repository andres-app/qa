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
DROP DATABASE IF EXISTS `mesadepartes`;
CREATE DATABASE IF NOT EXISTS `mesadepartes` /*!40100 DEFAULT CHARACTER SET utf8mb3 COLLATE utf8mb3_spanish_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `mesadepartes`;

-- Volcando estructura para procedimiento mesadepartes.sp_i_area_01
DROP PROCEDURE IF EXISTS `sp_i_area_01`;
DELIMITER //
CREATE PROCEDURE `sp_i_area_01`(
	IN `xusu_id` INT
)
BEGIN
	DECLARE areaCount INT;

	SELECT COUNT(*) INTO areaCount FROM td_area_detalle WHERE usu_id = xusu_id;
	
	IF areaCount = 0 THEN
		INSERT INTO td_area_detalle(usu_id,area_id)
		SELECT xusu_id,area_id FROM tm_area WHERE est=1;
	ELSE
		INSERT INTO td_area_detalle(usu_id,area_id)
		SELECT xusu_id,area_id FROM tm_area WHERE est=1 AND area_id NOT IN (SELECT area_id FROM td_area_detalle WHERE usu_id = xusu_id);
	END IF;
	
	SELECT 
	td_area_detalle.aread_id,
	td_area_detalle.area_id,
	td_area_detalle.aread_permi,
	tm_area.area_nom,
	tm_area.area_correo 
	FROM td_area_detalle
	INNER JOIN tm_area ON tm_area.area_id = td_area_detalle.area_id
	WHERE 
	td_area_detalle.usu_id = xusu_id
	AND tm_area.est=1;
END//
DELIMITER ;

-- Volcando estructura para procedimiento mesadepartes.sp_i_rol_01
DROP PROCEDURE IF EXISTS `sp_i_rol_01`;
DELIMITER //
CREATE PROCEDURE `sp_i_rol_01`(
	IN `xrol_id` INT
)
BEGIN
	DECLARE rolCount INT;

	SELECT COUNT(*) INTO rolCount FROM td_menu_detalle WHERE rol_id = xrol_id;
	
	IF rolCount = 0 THEN
		INSERT INTO td_menu_detalle(rol_id,men_id)
		SELECT xrol_id,men_id FROM tm_menu WHERE est=1;
	ELSE
		INSERT INTO td_menu_detalle(rol_id,men_id)
		SELECT xrol_id,men_id FROM tm_menu WHERE est=1 AND men_id NOT IN (SELECT men_id FROM td_menu_detalle WHERE rol_id = xrol_id);
	END IF;
	
	SELECT 
		td_menu_detalle.mend_id,
		td_menu_detalle.rol_id,
		td_menu_detalle.mend_permi,
		tm_menu.men_id,
		tm_menu.men_nom,
		tm_menu.men_nom_vista,
		tm_menu.men_icon,
		tm_menu.men_ruta
	FROM td_menu_detalle
	INNER JOIN tm_menu ON tm_menu.men_id = td_menu_detalle.men_id
	WHERE 
	td_menu_detalle.rol_id = xrol_id
	AND tm_menu.est=1;
END//
DELIMITER ;

-- Volcando estructura para procedimiento mesadepartes.sp_l_documento_01
DROP PROCEDURE IF EXISTS `sp_l_documento_01`;
DELIMITER //
CREATE PROCEDURE `sp_l_documento_01`(
	IN `xdoc_id` INT
)
BEGIN
	SELECT 
	tm_documento.doc_id,
	tm_documento.area_id,
	tm_area.area_nom,
	tm_area.area_correo,
	tm_documento.doc_externo,
	tm_documento.doc_dni,
	tm_documento.doc_nom,
	tm_documento.doc_descrip,
	tm_documento.tra_id,
	tm_tramite.tra_nom,
	tm_documento.tip_id,
	tm_tipo.tip_nom,
	tm_documento.usu_id,
	tm_usuario.usu_nomape,
	tm_usuario.usu_correo,
	tm_documento.doc_estado,
	tm_documento.doc_respuesta,
	COALESCE(contador.cant,0) AS cant,
	CONCAT(DATE_FORMAT(tm_documento.fech_crea,'%m'),'-',DATE_FORMAT(tm_documento.fech_crea,'%Y'),'-',tm_documento.doc_id) 
AS nrotramite
	FROM tm_documento
	INNER JOIN tm_area ON tm_documento.area_id = tm_area.area_id
	INNER JOIN tm_tramite ON tm_documento.tra_id = tm_tramite.tra_id
	INNER JOIN tm_tipo ON tm_documento.tip_id = tm_tipo.tip_id
	INNER JOIN tm_usuario ON tm_documento.usu_id = tm_usuario.usu_id
	LEFT JOIN (
		SELECT doc_id,COUNT(*) AS cant
		FROM td_documento_detalle 
		WHERE doc_id= xdoc_id
		GROUP BY doc_id
	) contador ON tm_documento.doc_id = contador.doc_id
	WHERE tm_documento.doc_id = xdoc_id;
END//
DELIMITER ;

-- Volcando estructura para procedimiento mesadepartes.sp_l_documento_02
DROP PROCEDURE IF EXISTS `sp_l_documento_02`;
DELIMITER //
CREATE PROCEDURE `sp_l_documento_02`(
	IN `xusu_id` INT
)
BEGIN
	SELECT 
	tm_documento.doc_id,
	tm_documento.area_id,
	tm_area.area_nom,
	tm_area.area_correo,
	tm_documento.doc_externo,
	tm_documento.doc_dni,
	tm_documento.doc_nom,
	tm_documento.doc_descrip,
	tm_documento.tra_id,
	tm_tramite.tra_nom,
	tm_documento.tip_id,
	tm_tipo.tip_nom,
	tm_documento.usu_id,
	tm_usuario.usu_nomape,
	tm_usuario.usu_correo,
	tm_documento.doc_estado,
	CONCAT(DATE_FORMAT(tm_documento.fech_crea,'%m'),'-',DATE_FORMAT(tm_documento.fech_crea,'%Y'),'-',tm_documento.doc_id) 
AS nrotramite
	FROM tm_documento
	INNER JOIN tm_area ON tm_documento.area_id = tm_area.area_id
	INNER JOIN tm_tramite ON tm_documento.tra_id = tm_tramite.tra_id
	INNER JOIN tm_tipo ON tm_documento.tip_id = tm_tipo.tip_id
	INNER JOIN tm_usuario ON tm_documento.usu_id = tm_usuario.usu_id
	WHERE tm_documento.usu_id = xusu_id;
END//
DELIMITER ;

-- Volcando estructura para tabla mesadepartes.td_area_detalle
DROP TABLE IF EXISTS `td_area_detalle`;
CREATE TABLE IF NOT EXISTS `td_area_detalle` (
  `aread_id` int NOT NULL AUTO_INCREMENT,
  `usu_id` int DEFAULT NULL,
  `area_id` int DEFAULT NULL,
  `aread_permi` varchar(2) COLLATE utf8mb3_spanish_ci DEFAULT 'No',
  `fech_crea` datetime DEFAULT CURRENT_TIMESTAMP,
  `fech_modi` datetime DEFAULT NULL,
  `fech_elim` datetime DEFAULT NULL,
  `est` int DEFAULT '1',
  PRIMARY KEY (`aread_id`)
) ENGINE=InnoDB AUTO_INCREMENT=43 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_spanish_ci;

-- Volcando datos para la tabla mesadepartes.td_area_detalle: ~22 rows (aproximadamente)
REPLACE INTO `td_area_detalle` (`aread_id`, `usu_id`, `area_id`, `aread_permi`, `fech_crea`, `fech_modi`, `fech_elim`, `est`) VALUES
	(16, 116, 1, 'No', '2023-12-04 21:43:24', '2023-12-04 22:14:34', NULL, 1),
	(17, 116, 2, 'Si', '2023-12-04 21:43:24', '2023-12-05 21:36:24', NULL, 1),
	(18, 116, 3, 'No', '2023-12-04 21:43:24', '2023-12-04 22:14:32', NULL, 1),
	(19, 116, 4, 'No', '2023-12-04 21:43:24', '2023-12-04 22:14:32', NULL, 1),
	(20, 116, 5, 'Si', '2023-12-04 21:43:24', '2023-12-04 22:14:26', NULL, 1),
	(21, 116, 6, 'Si', '2023-12-04 21:43:24', '2023-12-05 21:36:36', NULL, 1),
	(22, 116, 7, 'No', '2023-12-04 21:43:24', '2023-12-04 22:14:35', NULL, 1),
	(23, 116, 8, 'No', '2023-12-04 21:43:24', '2023-12-04 22:14:28', NULL, 1),
	(24, 116, 9, 'No', '2023-12-04 21:43:24', '2023-12-04 22:14:29', NULL, 1),
	(25, 116, 10, 'No', '2023-12-04 21:43:24', '2023-12-04 22:14:29', NULL, 1),
	(31, 116, 14, 'No', '2023-12-04 21:44:31', '2023-12-05 21:36:22', NULL, 1),
	(32, 104, 1, 'No', '2023-12-04 21:59:34', NULL, NULL, 1),
	(33, 104, 2, 'No', '2023-12-04 21:59:34', NULL, NULL, 1),
	(34, 104, 3, 'No', '2023-12-04 21:59:34', NULL, NULL, 1),
	(35, 104, 4, 'No', '2023-12-04 21:59:34', NULL, NULL, 1),
	(36, 104, 5, 'Si', '2023-12-04 21:59:34', '2023-12-05 16:25:14', NULL, 1),
	(37, 104, 6, 'No', '2023-12-04 21:59:34', NULL, NULL, 1),
	(38, 104, 7, 'No', '2023-12-04 21:59:34', NULL, NULL, 1),
	(39, 104, 8, 'No', '2023-12-04 21:59:34', NULL, NULL, 1),
	(40, 104, 9, 'No', '2023-12-04 21:59:34', NULL, NULL, 1),
	(41, 104, 10, 'No', '2023-12-04 21:59:34', NULL, NULL, 1),
	(42, 104, 14, 'Si', '2023-12-04 21:59:34', '2023-12-05 16:25:14', NULL, 1);

-- Volcando estructura para tabla mesadepartes.td_documento_detalle
DROP TABLE IF EXISTS `td_documento_detalle`;
CREATE TABLE IF NOT EXISTS `td_documento_detalle` (
  `det_id` int NOT NULL AUTO_INCREMENT,
  `doc_id` int DEFAULT NULL,
  `det_nom` varchar(250) COLLATE utf8mb3_spanish_ci DEFAULT NULL,
  `usu_id` int DEFAULT NULL,
  `det_tipo` varchar(50) COLLATE utf8mb3_spanish_ci DEFAULT NULL,
  `fech_crea` datetime DEFAULT CURRENT_TIMESTAMP,
  `fech_modi` datetime DEFAULT NULL,
  `fech_elim` datetime DEFAULT NULL,
  `est` int DEFAULT '1',
  PRIMARY KEY (`det_id`)
) ENGINE=InnoDB AUTO_INCREMENT=108 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_spanish_ci;

-- Volcando datos para la tabla mesadepartes.td_documento_detalle: ~107 rows (aproximadamente)
REPLACE INTO `td_documento_detalle` (`det_id`, `doc_id`, `det_nom`, `usu_id`, `det_tipo`, `fech_crea`, `fech_modi`, `fech_elim`, `est`) VALUES
	(1, 1, 'test.pdf', 1, 'Pendiente', NULL, NULL, NULL, 1),
	(2, 18, 'test.pdf', 104, 'Pendiente', NULL, NULL, NULL, 1),
	(3, 19, 'test.pdf', 104, 'Pendiente', NULL, NULL, NULL, 1),
	(4, 19, 'test.pdf', 104, 'Pendiente', NULL, NULL, NULL, 1),
	(5, 20, 'test.pdf', 104, 'Pendiente', NULL, NULL, NULL, 1),
	(6, 21, 'test.pdf', 104, 'Pendiente', NULL, NULL, NULL, 1),
	(7, 22, 'test.pdf', 104, 'Pendiente', NULL, NULL, NULL, 1),
	(8, 23, 'test.pdf', 104, 'Pendiente', NULL, NULL, NULL, 1),
	(9, 24, 'test.pdf', 104, 'Pendiente', NULL, NULL, NULL, 1),
	(10, 25, 'test.pdf', 104, 'Pendiente', '2023-11-29 13:26:30', NULL, NULL, 1),
	(11, 29, 'test.pdf', 104, 'Pendiente', '2023-11-29 19:19:05', NULL, NULL, 1),
	(12, 30, 'test.pdf', 104, 'Pendiente', '2023-11-29 19:25:07', NULL, NULL, 1),
	(13, 30, 'test.pdf', 104, 'Pendiente', '2023-11-29 19:25:07', NULL, NULL, 1),
	(14, 31, 'test.pdf', 104, 'Pendiente', '2023-11-29 19:26:39', NULL, NULL, 1),
	(15, 32, 'test.pdf', 104, 'Pendiente', '2023-11-29 19:27:51', NULL, NULL, 1),
	(16, 32, 'test.pdf', 104, 'Pendiente', '2023-11-29 19:27:51', NULL, NULL, 1),
	(17, 33, 'test.pdf', 104, 'Pendiente', '2023-11-29 19:29:16', NULL, NULL, 1),
	(18, 34, 'test.pdf', 104, 'Pendiente', '2023-11-29 19:33:20', NULL, NULL, 1),
	(19, 35, 'test.pdf', 104, 'Pendiente', '2023-11-29 19:37:18', NULL, NULL, 1),
	(20, 36, 'test.pdf', 104, 'Pendiente', '2023-11-29 19:37:42', NULL, NULL, 1),
	(21, 37, 'test.pdf', 104, 'Pendiente', '2023-11-29 20:48:03', NULL, NULL, 1),
	(22, 38, 'test.pdf', 104, 'Pendiente', '2023-11-29 20:50:38', NULL, NULL, 1),
	(23, 40, 'test.pdf', 104, 'Pendiente', '2023-11-29 20:51:19', NULL, NULL, 1),
	(24, 40, 'test.pdf', 104, 'Pendiente', '2023-11-29 20:51:19', NULL, NULL, 1),
	(25, 44, 'test.pdf', 104, 'Pendiente', '2023-11-30 11:05:22', NULL, NULL, 1),
	(26, 45, 'test.pdf', 104, 'Pendiente', '2023-11-30 11:06:43', NULL, NULL, 1),
	(27, 46, 'test.pdf', 104, 'Pendiente', '2023-11-30 11:07:05', NULL, NULL, 1),
	(28, 48, 'ListadoCompras.pdf', 117, 'Pendiente', '2023-12-06 12:58:35', NULL, NULL, 1),
	(29, 48, 'ListadoCompras1.pdf', 117, 'Pendiente', '2023-12-06 12:58:35', NULL, NULL, 1),
	(30, 48, 'ListadoCompras2.pdf', 117, 'Pendiente', '2023-12-06 12:58:35', NULL, NULL, 1),
	(31, 48, 'ListadoCompras3.pdf', 117, 'Pendiente', '2023-12-06 12:58:35', NULL, NULL, 1),
	(32, 48, 'ListadoCompras4.pdf', 117, 'Pendiente', '2023-12-06 12:58:35', NULL, NULL, 1),
	(33, 48, 'Respuesta1.pdf', 116, 'Terminado', '2023-12-06 17:08:22', NULL, NULL, 1),
	(34, 48, 'Respuesta2.pdf', 116, 'Terminado', '2023-12-06 17:08:22', NULL, NULL, 1),
	(35, 48, 'Respuesta3.pdf', 116, 'Terminado', '2023-12-06 17:08:22', NULL, NULL, 1),
	(36, 48, 'Respuesta4.pdf', 116, 'Terminado', '2023-12-06 17:08:22', NULL, NULL, 1),
	(37, 48, 'Respuesta5.pdf', 116, 'Terminado', '2023-12-06 17:08:22', NULL, NULL, 1),
	(38, 49, 'ListadoCompras.pdf', 117, 'Pendiente', '2023-12-06 17:21:13', NULL, NULL, 1),
	(39, 49, 'ListadoCompras1.pdf', 117, 'Pendiente', '2023-12-06 17:21:13', NULL, NULL, 1),
	(40, 49, 'ListadoCompras2.pdf', 117, 'Pendiente', '2023-12-06 17:21:13', NULL, NULL, 1),
	(41, 51, 'ListadoCompras1.pdf', 117, 'Pendiente', '2023-12-06 17:21:45', NULL, NULL, 1),
	(42, 52, 'ListadoCompras1.pdf', 117, 'Pendiente', '2023-12-06 17:22:02', NULL, NULL, 1),
	(43, 52, 'ListadoCompras2.pdf', 117, 'Pendiente', '2023-12-06 17:22:02', NULL, NULL, 1),
	(44, 53, 'ListadoCompras3.pdf', 117, 'Pendiente', '2023-12-06 17:22:21', NULL, NULL, 1),
	(45, 49, 'Respuesta1.pdf', 116, 'Terminado', '2023-12-06 17:24:35', NULL, NULL, 1),
	(46, 50, 'Respuesta1.pdf', 116, 'Terminado', '2023-12-06 18:05:03', NULL, NULL, 1),
	(47, 54, 'ListadoCompras.pdf', 117, 'Pendiente', '2023-12-09 10:08:17', NULL, NULL, 1),
	(48, 54, 'ListadoCompras1.pdf', 117, 'Pendiente', '2023-12-09 10:08:17', NULL, NULL, 1),
	(49, 54, 'ListadoCompras2.pdf', 117, 'Pendiente', '2023-12-09 10:08:17', NULL, NULL, 1),
	(50, 54, 'ListadoCompras3.pdf', 117, 'Pendiente', '2023-12-09 10:08:17', NULL, NULL, 1),
	(51, 54, 'ListadoCompras4.pdf', 117, 'Pendiente', '2023-12-09 10:08:17', NULL, NULL, 1),
	(52, 55, 'ListadoCompras.pdf', 117, 'Pendiente', '2023-12-09 10:15:34', NULL, NULL, 1),
	(53, 55, 'ListadoCompras1.pdf', 117, 'Pendiente', '2023-12-09 10:15:34', NULL, NULL, 1),
	(54, 55, 'ListadoCompras2.pdf', 117, 'Pendiente', '2023-12-09 10:15:34', NULL, NULL, 1),
	(55, 55, 'ListadoCompras3.pdf', 117, 'Pendiente', '2023-12-09 10:15:34', NULL, NULL, 1),
	(56, 55, 'COTIZACION  MATRIMONIO 40 P.pdf', 117, 'Pendiente', '2023-12-09 10:15:34', NULL, NULL, 1),
	(57, 55, 'Respuesta1.pdf', 116, 'Terminado', '2023-12-09 10:19:52', NULL, NULL, 1),
	(58, 55, 'Respuesta2.pdf', 116, 'Terminado', '2023-12-09 10:19:52', NULL, NULL, 1),
	(59, 55, 'Respuesta3.pdf', 116, 'Terminado', '2023-12-09 10:19:52', NULL, NULL, 1),
	(60, 55, 'Respuesta4.pdf', 116, 'Terminado', '2023-12-09 10:19:52', NULL, NULL, 1),
	(61, 55, 'Respuesta5.pdf', 116, 'Terminado', '2023-12-09 10:19:52', NULL, NULL, 1),
	(62, 56, 'Respuesta1.pdf', 117, 'Pendiente', '2023-12-09 10:29:54', NULL, NULL, 1),
	(63, 56, 'Respuesta2.pdf', 117, 'Pendiente', '2023-12-09 10:29:54', NULL, NULL, 1),
	(64, 56, 'Respuesta3.pdf', 117, 'Pendiente', '2023-12-09 10:29:54', NULL, NULL, 1),
	(65, 56, 'COTIZACION  MATRIMONIO 40 P (1).pdf', 117, 'Pendiente', '2023-12-09 10:29:54', NULL, NULL, 1),
	(66, 57, 'COTIZACION  MATRIMONIO 40 P (1).pdf', 117, 'Pendiente', '2023-12-09 10:30:36', NULL, NULL, 1),
	(67, 58, 'ListadoCompras.pdf', 117, 'Pendiente', '2023-12-10 10:56:33', NULL, NULL, 1),
	(68, 58, 'ListadoCompras2.pdf', 117, 'Pendiente', '2023-12-10 10:56:33', NULL, NULL, 1),
	(69, 58, 'ListadoCompras3.pdf', 117, 'Pendiente', '2023-12-10 10:56:33', NULL, NULL, 1),
	(70, 58, 'ListadoCompras4.pdf', 117, 'Pendiente', '2023-12-10 10:56:33', NULL, NULL, 1),
	(71, 59, 'Respuesta1.pdf', 104, 'Pendiente', '2024-03-01 16:50:00', NULL, NULL, 1),
	(72, 59, 'Respuesta2.pdf', 104, 'Pendiente', '2024-03-01 16:50:00', NULL, NULL, 1),
	(73, 59, 'Respuesta3.pdf', 104, 'Pendiente', '2024-03-01 16:50:00', NULL, NULL, 1),
	(74, 60, 'ListadoCompras.pdf', 104, 'Pendiente', '2024-03-01 16:50:39', NULL, NULL, 1),
	(75, 60, 'ListadoCompras1.pdf', 104, 'Pendiente', '2024-03-01 16:50:39', NULL, NULL, 1),
	(76, 60, 'ListadoCompras2.pdf', 104, 'Pendiente', '2024-03-01 16:50:39', NULL, NULL, 1),
	(77, 60, 'ListadoCompras3.pdf', 104, 'Pendiente', '2024-03-01 16:50:39', NULL, NULL, 1),
	(78, 60, 'ListadoCompras4.pdf', 104, 'Pendiente', '2024-03-01 16:50:39', NULL, NULL, 1),
	(79, 61, 'ListadoCompras4.pdf', 104, 'Pendiente', '2024-03-01 16:51:20', NULL, NULL, 1),
	(80, 61, 'Respuesta1.pdf', 104, 'Pendiente', '2024-03-01 16:51:20', NULL, NULL, 1),
	(81, 61, 'Respuesta2.pdf', 104, 'Pendiente', '2024-03-01 16:51:20', NULL, NULL, 1),
	(82, 61, 'Respuesta1.pdf', 104, 'Pendiente', '2024-03-01 16:51:20', NULL, NULL, 1),
	(83, 61, 'Respuesta2.pdf', 104, 'Pendiente', '2024-03-01 16:51:20', NULL, NULL, 1),
	(84, 62, 'Respuesta1.pdf', 104, 'Pendiente', '2024-03-01 16:52:47', NULL, NULL, 1),
	(85, 62, 'ListadoCompras.pdf', 104, 'Pendiente', '2024-03-01 16:52:47', NULL, NULL, 1),
	(86, 62, 'ListadoCompras1.pdf', 104, 'Pendiente', '2024-03-01 16:52:47', NULL, NULL, 1),
	(87, 62, 'ListadoCompras2.pdf', 104, 'Pendiente', '2024-03-01 16:52:47', NULL, NULL, 1),
	(88, 62, 'ListadoCompras3.pdf', 104, 'Pendiente', '2024-03-01 16:52:47', NULL, NULL, 1),
	(89, 63, 'ListadoCompras.pdf', 104, 'Pendiente', '2024-03-01 16:53:25', NULL, NULL, 1),
	(90, 63, 'Respuesta4.pdf', 104, 'Pendiente', '2024-03-01 16:53:25', NULL, NULL, 1),
	(91, 63, 'Respuesta5.pdf', 104, 'Pendiente', '2024-03-01 16:53:25', NULL, NULL, 1),
	(92, 64, 'ListadoCompras.pdf', 104, 'Pendiente', '2024-03-01 16:54:32', NULL, NULL, 1),
	(93, 64, 'ListadoCompras1.pdf', 104, 'Pendiente', '2024-03-01 16:54:32', NULL, NULL, 1),
	(94, 64, 'ListadoCompras2.pdf', 104, 'Pendiente', '2024-03-01 16:54:32', NULL, NULL, 1),
	(95, 64, 'ListadoCompras3.pdf', 104, 'Pendiente', '2024-03-01 16:54:32', NULL, NULL, 1),
	(96, 64, 'ListadoCompras4.pdf', 104, 'Pendiente', '2024-03-01 16:54:32', NULL, NULL, 1),
	(97, 65, 'ListadoCompras4.pdf', 104, 'Pendiente', '2024-03-01 16:57:06', NULL, NULL, 1),
	(98, 66, 'Respuesta5.pdf', 104, 'Pendiente', '2024-03-01 16:57:47', NULL, NULL, 1),
	(99, 67, 'Respuesta5.pdf', 104, 'Pendiente', '2024-03-01 16:58:20', NULL, NULL, 1),
	(100, 68, 'Respuesta1.pdf', 104, 'Pendiente', '2024-03-01 17:01:33', NULL, NULL, 1),
	(101, 69, 'ListadoCompras.pdf', 104, 'Pendiente', '2024-03-01 17:02:02', NULL, NULL, 1),
	(102, 69, 'ListadoCompras1.pdf', 104, 'Pendiente', '2024-03-01 17:02:02', NULL, NULL, 1),
	(103, 69, 'ListadoCompras2.pdf', 104, 'Pendiente', '2024-03-01 17:02:02', NULL, NULL, 1),
	(104, 69, 'ListadoCompras3.pdf', 104, 'Pendiente', '2024-03-01 17:02:02', NULL, NULL, 1),
	(105, 69, 'ListadoCompras4.pdf', 104, 'Pendiente', '2024-03-01 17:02:02', NULL, NULL, 1),
	(106, 70, 'Respuesta5.pdf', 104, 'Pendiente', '2024-03-01 17:04:27', NULL, NULL, 1),
	(107, 71, 'Respuesta4.pdf', 104, 'Pendiente', '2024-03-01 17:04:47', NULL, NULL, 1);

-- Volcando estructura para tabla mesadepartes.td_menu_detalle
DROP TABLE IF EXISTS `td_menu_detalle`;
CREATE TABLE IF NOT EXISTS `td_menu_detalle` (
  `mend_id` int NOT NULL AUTO_INCREMENT,
  `rol_id` int DEFAULT NULL,
  `men_id` int DEFAULT NULL,
  `mend_permi` varchar(2) COLLATE utf8mb3_spanish_ci DEFAULT 'No',
  `fech_crea` datetime DEFAULT CURRENT_TIMESTAMP,
  `fech_modi` datetime DEFAULT NULL,
  `fech_elim` datetime DEFAULT NULL,
  `est` int DEFAULT '1',
  PRIMARY KEY (`mend_id`)
) ENGINE=InnoDB AUTO_INCREMENT=42 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_spanish_ci;

-- Volcando datos para la tabla mesadepartes.td_menu_detalle: ~29 rows (aproximadamente)
REPLACE INTO `td_menu_detalle` (`mend_id`, `rol_id`, `men_id`, `mend_permi`, `fech_crea`, `fech_modi`, `fech_elim`, `est`) VALUES
	(1, 3, 1, 'No', '2023-12-05 12:53:29', '2023-12-05 21:28:57', NULL, 1),
	(2, 3, 2, 'Si', '2023-12-05 12:53:29', '2023-12-10 11:24:02', NULL, 1),
	(3, 3, 3, 'Si', '2023-12-05 12:53:29', '2023-12-10 11:23:59', NULL, 1),
	(4, 3, 4, 'Si', '2023-12-05 12:53:29', '2023-12-05 21:28:23', NULL, 1),
	(5, 3, 5, 'Si', '2023-12-05 12:53:29', '2023-12-10 11:23:59', NULL, 1),
	(6, 3, 6, 'Si', '2023-12-05 12:53:29', '2023-12-10 11:24:01', NULL, 1),
	(7, 3, 7, 'Si', '2023-12-05 12:53:29', '2023-12-05 15:45:48', NULL, 1),
	(8, 3, 8, 'Si', '2023-12-05 12:53:29', '2023-12-05 15:45:49', NULL, 1),
	(9, 3, 9, 'Si', '2023-12-05 12:53:29', '2023-12-05 15:32:40', NULL, 1),
	(10, 3, 10, 'Si', '2023-12-05 12:53:29', '2023-12-05 15:32:39', NULL, 1),
	(11, 3, 11, 'Si', '2023-12-05 12:53:29', '2023-12-05 15:32:38', NULL, 1),
	(16, 2, 1, 'No', '2023-12-05 15:32:23', '2023-12-05 21:28:39', NULL, 1),
	(17, 2, 2, 'No', '2023-12-05 15:32:23', '2023-12-05 21:28:37', NULL, 1),
	(18, 2, 3, 'No', '2023-12-05 15:32:23', '2023-12-05 21:28:44', NULL, 1),
	(19, 2, 4, 'Si', '2023-12-05 15:32:23', '2023-12-05 15:32:29', NULL, 1),
	(20, 2, 5, 'Si', '2023-12-05 15:32:23', '2023-12-05 15:32:30', NULL, 1),
	(21, 2, 6, 'Si', '2023-12-05 15:32:23', '2023-12-05 15:32:30', NULL, 1),
	(22, 2, 7, 'No', '2023-12-05 15:32:23', '2023-12-05 15:32:48', NULL, 1),
	(23, 2, 8, 'No', '2023-12-05 15:32:23', '2023-12-05 15:32:47', NULL, 1),
	(24, 2, 9, 'No', '2023-12-05 15:32:23', '2023-12-05 15:32:49', NULL, 1),
	(25, 2, 10, 'No', '2023-12-05 15:32:23', '2023-12-05 15:32:49', NULL, 1),
	(26, 2, 11, 'No', '2023-12-05 15:32:23', '2023-12-05 15:32:48', NULL, 1),
	(31, 1, 1, 'Si', '2023-12-05 15:46:28', '2023-12-05 15:46:38', NULL, 1),
	(32, 1, 2, 'Si', '2023-12-05 15:46:28', '2023-12-05 15:46:41', NULL, 1),
	(33, 1, 3, 'Si', '2023-12-05 15:46:28', '2023-12-05 15:46:36', NULL, 1),
	(34, 1, 4, 'No', '2023-12-05 15:46:28', NULL, NULL, 1),
	(35, 1, 5, 'No', '2023-12-05 15:46:28', NULL, NULL, 1),
	(36, 1, 6, 'No', '2023-12-05 15:46:28', NULL, NULL, 1),
	(37, 1, 7, 'No', '2023-12-05 15:46:28', NULL, NULL, 1),
	(38, 1, 8, 'No', '2023-12-05 15:46:28', NULL, NULL, 1),
	(39, 1, 9, 'No', '2023-12-05 15:46:28', NULL, NULL, 1),
	(40, 1, 10, 'No', '2023-12-05 15:46:28', NULL, NULL, 1),
	(41, 1, 11, 'No', '2023-12-05 15:46:28', NULL, NULL, 1);

-- Volcando estructura para tabla mesadepartes.tm_area
DROP TABLE IF EXISTS `tm_area`;
CREATE TABLE IF NOT EXISTS `tm_area` (
  `area_id` int NOT NULL AUTO_INCREMENT,
  `area_nom` varchar(50) COLLATE utf8mb3_spanish_ci NOT NULL,
  `area_correo` varchar(50) COLLATE utf8mb3_spanish_ci NOT NULL DEFAULT '1',
  `fech_crea` datetime DEFAULT CURRENT_TIMESTAMP,
  `fech_modi` datetime DEFAULT NULL,
  `fech_elim` datetime DEFAULT NULL,
  `est` int NOT NULL DEFAULT '1',
  PRIMARY KEY (`area_id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_spanish_ci;

-- Volcando datos para la tabla mesadepartes.tm_area: ~8 rows (aproximadamente)
REPLACE INTO `tm_area` (`area_id`, `area_nom`, `area_correo`, `fech_crea`, `fech_modi`, `fech_elim`, `est`) VALUES
	(1, 'Recursos Humanos (RRHH)', 'davis_anderson_87@hotmail.com', '2023-11-29 19:18:20', NULL, NULL, 1),
	(2, 'Finanzas', 'davis_anderson_87@hotmail.com', '2023-11-29 19:18:20', NULL, NULL, 1),
	(3, 'Marketing', 'davis_anderson_87@hotmail.com', '2023-11-29 19:18:20', NULL, NULL, 1),
	(4, 'Producción/Operaciones', 'davis_anderson_87@hotmail.com', '2023-11-29 19:18:20', NULL, NULL, 1),
	(5, 'Ventas', 'davis_anderson_87@hotmail.com', '2023-11-29 19:18:20', NULL, NULL, 1),
	(6, 'Servicio al Cliente', 'davis_anderson_87@hotmail.com', '2023-11-29 19:18:20', NULL, NULL, 1),
	(7, 'Tecnología de la Información (TI)', 'davis_anderson_87@hotmail.com', '2023-11-29 19:18:20', NULL, NULL, 1),
	(8, 'Investigación y Desarrollo (I+D)', 'davis_anderson_87@hotmail.com', '2023-11-29 19:18:20', NULL, NULL, 1),
	(9, 'Logística', 'davis_anderson_87@hotmail.com', '2023-11-29 19:18:20', NULL, NULL, 1),
	(10, 'Legal y Cumplimiento', 'davis_anderson_87@hotmail.com', '2023-11-29 19:18:20', NULL, NULL, 1),
	(11, 'test2222', 'test2222@test.com', NULL, '2023-12-01 22:42:42', '2023-12-01 22:42:52', 0),
	(12, 'test1111', 'test2222@test.com', NULL, '2023-12-01 22:43:14', '2023-12-01 22:43:23', 0),
	(13, 'test123', 'test2222@test.com', '2023-12-01 22:43:31', NULL, '2023-12-01 22:43:53', 0),
	(14, 'test', 'test@tra.com', '2023-12-04 21:44:19', NULL, NULL, 1);

-- Volcando estructura para tabla mesadepartes.tm_documento
DROP TABLE IF EXISTS `tm_documento`;
CREATE TABLE IF NOT EXISTS `tm_documento` (
  `doc_id` int NOT NULL AUTO_INCREMENT,
  `area_id` int DEFAULT NULL,
  `tra_id` int DEFAULT NULL,
  `doc_externo` varchar(50) COLLATE utf8mb3_spanish_ci DEFAULT NULL,
  `tip_id` int DEFAULT NULL,
  `doc_dni` varchar(50) COLLATE utf8mb3_spanish_ci DEFAULT NULL,
  `doc_nom` varchar(250) COLLATE utf8mb3_spanish_ci DEFAULT NULL,
  `doc_descrip` varchar(500) COLLATE utf8mb3_spanish_ci DEFAULT NULL,
  `usu_id` int DEFAULT NULL,
  `doc_estado` varchar(50) COLLATE utf8mb3_spanish_ci DEFAULT 'Pendiente',
  `doc_respuesta` varchar(500) COLLATE utf8mb3_spanish_ci DEFAULT NULL,
  `doc_usu_terminado` int DEFAULT NULL,
  `fech_crea` datetime DEFAULT CURRENT_TIMESTAMP,
  `fech_modi` datetime DEFAULT NULL,
  `fech_elim` datetime DEFAULT NULL,
  `fech_terminado` datetime DEFAULT NULL,
  `est` int DEFAULT '1',
  PRIMARY KEY (`doc_id`)
) ENGINE=InnoDB AUTO_INCREMENT=72 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_spanish_ci;

-- Volcando datos para la tabla mesadepartes.tm_documento: ~57 rows (aproximadamente)
REPLACE INTO `tm_documento` (`doc_id`, `area_id`, `tra_id`, `doc_externo`, `tip_id`, `doc_dni`, `doc_nom`, `doc_descrip`, `usu_id`, `doc_estado`, `doc_respuesta`, `doc_usu_terminado`, `fech_crea`, `fech_modi`, `fech_elim`, `fech_terminado`, `est`) VALUES
	(1, 1, 1, 'TEST', 1, '123456', 'TEST NOMBRE', 'BLA BLA BLA', 1, 'Pendiente', NULL, NULL, '2023-11-29 19:03:56', NULL, NULL, NULL, 1),
	(16, 10, 4, 'asdasdas', 1, 'asdasdas', 'asdasdas', 'asdasdasdasdasd', 104, 'Pendiente', NULL, NULL, '2023-11-29 19:03:56', NULL, NULL, NULL, 1),
	(17, 8, 3, 'asdasd', 2, 'asd', 'asd', 'asdasd', 104, 'Pendiente', NULL, NULL, '2023-11-29 19:03:56', NULL, NULL, NULL, 1),
	(18, 3, 4, 'asdasd', 1, 'asd', 'asd', 'asdasd', 104, 'Pendiente', NULL, NULL, '2023-11-29 19:03:56', NULL, NULL, NULL, 1),
	(19, 10, 4, 'asdasd', 1, 'asdasd', 'asdas', 'adasdasdasd', 104, 'Pendiente', NULL, NULL, '2023-11-29 19:03:56', NULL, NULL, NULL, 1),
	(20, 8, 4, 'asdasd', 1, 'asdasdas', 'asdasd', 'asdasdas', 104, 'Pendiente', NULL, NULL, '2023-11-29 19:03:56', NULL, NULL, NULL, 1),
	(21, 9, 2, 'asdas', 1, 'asd', 'asd', 'asd', 104, 'Pendiente', NULL, NULL, '2023-11-29 19:03:56', NULL, NULL, NULL, 1),
	(22, 8, 3, 'asdasd', 3, 'asd', 'asd', 'asdasd', 104, 'Pendiente', NULL, NULL, '2023-11-29 19:03:56', NULL, NULL, NULL, 1),
	(23, 8, 3, 'asdasd', 2, 'asdasd', 'asdasd', 'asdasdasd', 104, 'Pendiente', NULL, NULL, '2023-11-29 19:03:56', NULL, NULL, NULL, 1),
	(24, 10, 4, 'asdasda', 1, 'adasd', 'asdasd', 'asdasd', 104, 'Pendiente', NULL, NULL, '2023-11-29 19:03:56', NULL, NULL, NULL, 1),
	(25, 8, 3, 'asdasd', 2, 'asdasd', 'asdasd', 'asdasd', 104, 'Pendiente', NULL, NULL, '2023-11-29 19:03:56', NULL, NULL, NULL, 1),
	(26, 10, 4, 'asdasd', 1, 'asdsa', 'asdas', 'asdasd', 104, 'Pendiente', NULL, NULL, '2023-11-29 19:04:14', NULL, NULL, NULL, 1),
	(27, 9, 4, 'asdsad', 2, 'asdasd', 'asdasd', 'asdasd', 104, 'Pendiente', NULL, NULL, '2023-11-29 19:15:31', NULL, NULL, NULL, 1),
	(28, 9, 4, 'asdsad', 2, 'asdasd', 'asdasd', 'asdasd', 104, 'Pendiente', NULL, NULL, '2023-11-29 19:15:35', NULL, NULL, NULL, 1),
	(29, 3, 7, 'asdasdasd', 1, 'asdasdasd', 'asdasdas', 'asdasdasdasdas', 104, 'Pendiente', NULL, NULL, '2023-11-29 19:19:01', NULL, NULL, NULL, 1),
	(30, 9, 4, 'asdasd', 1, 'adasd', 'asdasd', 'asdasdas', 104, 'Pendiente', NULL, NULL, '2023-11-29 19:25:03', NULL, NULL, NULL, 1),
	(31, 3, 5, 'asdasdas', 1, 'asdasd', 'asdasd', 'adasd', 104, 'Pendiente', NULL, NULL, '2023-11-29 19:26:39', NULL, NULL, NULL, 1),
	(32, 9, 5, 'asdasd', 1, 'adsad', 'asdasd', 'asdasdasd', 104, 'Pendiente', NULL, NULL, '2023-11-29 19:27:51', NULL, NULL, NULL, 1),
	(33, 10, 3, 'asdasd', 1, 'asdasd', 'asda', 'asdasdasd', 104, 'Pendiente', NULL, NULL, '2023-11-29 19:29:16', NULL, NULL, NULL, 1),
	(34, 9, 3, 'asdad', 1, 'asd', 'asd', 'asd', 104, 'Pendiente', NULL, NULL, '2023-11-29 19:33:20', NULL, NULL, NULL, 1),
	(35, 2, 3, 'asdadsa', 3, 'asdas', 'dasdas', 'asdasd', 104, 'Pendiente', NULL, NULL, '2023-11-29 19:37:18', NULL, NULL, NULL, 1),
	(36, 10, 3, 'adad', 1, 'asd', 'asd', 'asdas', 104, 'Pendiente', NULL, NULL, '2023-11-29 19:37:42', NULL, NULL, NULL, 1),
	(37, 8, 3, 'asdad', 1, 'asdasd', 'asdasd', 'asdasdas', 104, 'Pendiente', NULL, NULL, '2023-11-29 20:48:03', NULL, NULL, NULL, 1),
	(38, 9, 5, 'asdasdsad', 1, 'asdadsa', 'asdasdasd', 'asdasdasdasdasdasd', 104, 'Pendiente', NULL, NULL, '2023-11-29 20:50:38', NULL, NULL, NULL, 1),
	(39, 3, 3, 'asdasd', 2, 'asdasd', 'asdasd', 'asdasd', 104, 'Pendiente', NULL, NULL, '2023-11-29 20:51:01', NULL, NULL, NULL, 1),
	(40, 3, 4, 'asdasd', 1, 'asdasd', 'asdsad', 'asdasd', 104, 'Pendiente', NULL, NULL, '2023-11-29 20:51:19', NULL, NULL, NULL, 1),
	(41, 10, 2, 'asdasd', 1, 'asdasd', 'asdasd', 'asdasdas', 104, 'Pendiente', NULL, NULL, '2023-11-30 10:53:41', NULL, NULL, NULL, 1),
	(42, 10, 4, 'asdasd', 2, 'ad', 'asd', 'asd', 104, 'Pendiente', NULL, NULL, '2023-11-30 11:01:03', NULL, NULL, NULL, 1),
	(43, 10, 3, 'asdas', 1, 'asda', 'asd', 'asdasd', 104, 'Pendiente', NULL, NULL, '2023-11-30 11:04:33', NULL, NULL, NULL, 1),
	(44, 8, 3, 'adasd', 2, 'asd', 'asd', 'adasd', 104, 'Pendiente', NULL, NULL, '2023-11-30 11:05:21', NULL, NULL, NULL, 1),
	(45, 10, 4, 'asdadasd', 1, 'asdasd', 'asdas', 'asdasdasdas', 104, 'Pendiente', NULL, NULL, '2023-11-30 11:06:43', NULL, NULL, NULL, 1),
	(46, 10, 4, 'sadasd', 2, 'asdasd', 'asdasd', 'asdasdas', 104, 'Pendiente', NULL, NULL, '2023-11-30 11:07:05', NULL, NULL, NULL, 1),
	(47, 10, 4, 'asdasd', 1, 'asdsad', 'asda', 'asdasd', 104, 'Pendiente', NULL, NULL, '2023-11-30 15:43:17', NULL, NULL, NULL, 1),
	(48, 2, 3, 'te', 2, '123131231', '123123123123', 'aweqeqeqweqeqeqeqwew', 117, 'Terminado', 'asdasd asd asd asd adasd asd asd', 116, '2023-12-06 12:58:35', NULL, NULL, '2023-12-06 17:08:22', 1),
	(49, 2, 7, '123', 2, '123', '123', '123123123', 117, 'Terminado', 'dasdadasdada', 116, '2023-12-06 17:21:13', NULL, NULL, '2023-12-06 17:24:35', 1),
	(50, 2, 7, 'asdasd', 2, 'asdasd', 'asdasd', 'asdasda', 117, 'Terminado', 'hola ', 116, '2023-12-06 17:21:32', NULL, NULL, '2023-12-06 18:05:03', 1),
	(51, 2, 2, 'asdasdasd', 1, 'asdasda', 'asdasdasd', 'asdasdasdadada', 117, 'Pendiente', NULL, NULL, '2023-12-06 17:21:45', NULL, NULL, NULL, 1),
	(52, 2, 8, 'asdasdasd', 1, 'asdasd', 'asdasd', 'asdsadad', 117, 'Pendiente', NULL, NULL, '2023-12-06 17:22:02', NULL, NULL, NULL, 1),
	(53, 2, 6, 'asdasd', 1, 'asdasd', 'asdad', 'asdasdadada', 117, 'Pendiente', NULL, NULL, '2023-12-06 17:22:21', NULL, NULL, NULL, 1),
	(54, 8, 5, '123', 2, '1234', '12312', '123123', 117, 'Pendiente', NULL, NULL, '2023-12-09 10:08:17', NULL, NULL, NULL, 1),
	(55, 2, 13, '123', 1, '423234123', 'Anderson', 'Test', 117, 'Terminado', 'test', 116, '2023-12-09 10:15:34', NULL, NULL, '2023-12-09 10:19:52', 1),
	(56, 8, 4, '123', 2, '123', '123', '123123', 117, 'Pendiente', NULL, NULL, '2023-12-09 10:29:54', NULL, NULL, NULL, 1),
	(57, 8, 3, '123', 2, '123', '132', '13', 117, 'Pendiente', NULL, NULL, '2023-12-09 10:30:35', NULL, NULL, NULL, 1),
	(58, 10, 3, '123', 2, '123', '123', '1231312312', 117, 'Pendiente', NULL, NULL, '2023-12-10 10:56:33', NULL, NULL, NULL, 1),
	(59, 10, 3, '123', 2, '123', '123', '123123', 104, 'Pendiente', NULL, NULL, '2024-03-01 16:50:00', NULL, NULL, NULL, 1),
	(60, 9, 4, '123', 2, '123', '123', '123', 104, 'Pendiente', NULL, NULL, '2024-03-01 16:50:39', NULL, NULL, NULL, 1),
	(61, 3, 4, '123', 1, '123', '123', '123', 104, 'Pendiente', NULL, NULL, '2024-03-01 16:51:20', NULL, NULL, NULL, 1),
	(62, 3, 4, '123', 1, '123', '123', '123123', 104, 'Pendiente', NULL, NULL, '2024-03-01 16:52:47', NULL, NULL, NULL, 1),
	(63, 10, 4, '123', 2, '123', '123', '123123', 104, 'Pendiente', NULL, NULL, '2024-03-01 16:53:25', NULL, NULL, NULL, 1),
	(64, 9, 4, 'asdsda', 1, 'asd', 'asd', 'asdasd', 104, 'Pendiente', NULL, NULL, '2024-03-01 16:54:32', NULL, NULL, NULL, 1),
	(65, 8, 4, '123123', 1, '123', '123', '123', 104, 'Pendiente', NULL, NULL, '2024-03-01 16:57:06', NULL, NULL, NULL, 1),
	(66, 2, 2, '123', 1, '123', '123', '123', 104, 'Pendiente', NULL, NULL, '2024-03-01 16:57:47', NULL, NULL, NULL, 1),
	(67, 10, 3, 'asdas', 2, 'asd', 'asd', 'asdasd', 104, 'Pendiente', NULL, NULL, '2024-03-01 16:58:20', NULL, NULL, NULL, 1),
	(68, 10, 3, '123123', 2, '12312', '123', '123123', 104, 'Pendiente', NULL, NULL, '2024-03-01 17:01:33', NULL, NULL, NULL, 1),
	(69, 9, 4, '123123', 1, '123123', '12312', '12312312', 104, 'Pendiente', NULL, NULL, '2024-03-01 17:02:02', NULL, NULL, NULL, 1),
	(70, 10, 4, '123', 3, '123', '123', '123', 104, 'Pendiente', NULL, NULL, '2024-03-01 17:04:27', NULL, NULL, NULL, 1),
	(71, 8, 2, '123123', 2, '13123', '123', '123123', 104, 'Pendiente', NULL, NULL, '2024-03-01 17:04:47', NULL, NULL, NULL, 1);

-- Volcando estructura para tabla mesadepartes.tm_menu
DROP TABLE IF EXISTS `tm_menu`;
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
REPLACE INTO `tm_menu` (`men_id`, `men_nom`, `men_nom_vista`, `men_icon`, `men_ruta`, `fech_crea`, `fech_modi`, `fech_elim`, `est`) VALUES
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

-- Volcando estructura para tabla mesadepartes.tm_rol
DROP TABLE IF EXISTS `tm_rol`;
CREATE TABLE IF NOT EXISTS `tm_rol` (
  `rol_id` int NOT NULL AUTO_INCREMENT,
  `rol_nom` varchar(50) COLLATE utf8mb3_spanish_ci DEFAULT NULL,
  `fech_crea` datetime DEFAULT CURRENT_TIMESTAMP,
  `fech_modi` datetime DEFAULT NULL,
  `fech_elim` datetime DEFAULT NULL,
  `est` int DEFAULT '1',
  PRIMARY KEY (`rol_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_spanish_ci;

-- Volcando datos para la tabla mesadepartes.tm_rol: ~2 rows (aproximadamente)
REPLACE INTO `tm_rol` (`rol_id`, `rol_nom`, `fech_crea`, `fech_modi`, `fech_elim`, `est`) VALUES
	(1, 'Persona', '2023-12-05 12:44:00', NULL, NULL, 1),
	(2, 'Colaborador', '2023-12-05 12:44:10', NULL, NULL, 1),
	(3, 'Administrador', '2023-12-05 12:44:17', NULL, NULL, 1);

-- Volcando estructura para tabla mesadepartes.tm_tipo
DROP TABLE IF EXISTS `tm_tipo`;
CREATE TABLE IF NOT EXISTS `tm_tipo` (
  `tip_id` int NOT NULL AUTO_INCREMENT,
  `tip_nom` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_spanish_ci DEFAULT NULL,
  `fech_crea` datetime DEFAULT CURRENT_TIMESTAMP,
  `fech_modi` datetime DEFAULT NULL,
  `fech_elim` datetime DEFAULT NULL,
  `est` int DEFAULT '1',
  PRIMARY KEY (`tip_id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_spanish_ci;

-- Volcando datos para la tabla mesadepartes.tm_tipo: ~7 rows (aproximadamente)
REPLACE INTO `tm_tipo` (`tip_id`, `tip_nom`, `fech_crea`, `fech_modi`, `fech_elim`, `est`) VALUES
	(1, 'Natural', '2023-12-01 19:20:14', NULL, NULL, 1),
	(2, 'Juridico', '2023-12-01 19:20:14', NULL, NULL, 1),
	(3, 'Otro', '2023-12-01 19:20:14', NULL, NULL, 1),
	(10, 'test3', '2023-12-01 20:43:24', '2023-12-01 20:55:11', '2023-12-01 21:04:55', 0),
	(11, 'test2', '2023-12-01 20:55:02', NULL, '2023-12-01 21:05:03', 0),
	(12, 'test45', '2023-12-01 20:55:22', '2023-12-01 20:57:25', '2023-12-01 21:04:50', 0),
	(13, 'test000', '2023-12-01 20:58:49', '2023-12-01 20:58:57', '2023-12-01 21:00:31', 0),
	(14, 'test1', '2023-12-01 21:05:33', NULL, '2023-12-01 21:05:38', 0);

-- Volcando estructura para tabla mesadepartes.tm_tramite
DROP TABLE IF EXISTS `tm_tramite`;
CREATE TABLE IF NOT EXISTS `tm_tramite` (
  `tra_id` int NOT NULL AUTO_INCREMENT,
  `tra_nom` varchar(150) COLLATE utf8mb3_spanish_ci NOT NULL,
  `tra_descrip` varchar(300) COLLATE utf8mb3_spanish_ci NOT NULL,
  `fech_crea` datetime DEFAULT CURRENT_TIMESTAMP,
  `fech_modi` datetime DEFAULT NULL,
  `fech_elim` datetime DEFAULT NULL,
  `est` int DEFAULT '1',
  PRIMARY KEY (`tra_id`)
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_spanish_ci;

-- Volcando datos para la tabla mesadepartes.tm_tramite: ~21 rows (aproximadamente)
REPLACE INTO `tm_tramite` (`tra_id`, `tra_nom`, `tra_descrip`, `fech_crea`, `fech_modi`, `fech_elim`, `est`) VALUES
	(1, 'Recepción de Correspondencia Externa', 'Registro y distribución de la correspondencia enviada por clientes, proveedores u otras entidades externas.', '2023-12-01 22:48:17', NULL, NULL, 1),
	(2, 'Registro de Quejas o Reclamos de Clientes', 'Proceso para gestionar y dar respuesta a las quejas o reclamos de los clientes.', '2023-12-01 22:48:17', NULL, NULL, 1),
	(3, 'Solicitud de Información Pública', 'Gestión de solicitudes de información pública por parte de entidades gubernamentales u otros solicitantes externos.', '2023-12-01 22:48:17', NULL, NULL, 1),
	(4, 'Registro de Contratos y Acuerdos', 'Archivo y seguimiento de los contratos y acuerdos firmados con clientes, proveedores u otras partes externas.', '2023-12-01 22:48:17', NULL, NULL, 1),
	(5, 'Solicitud de Autorización para Eventos', 'Trámite para obtener permisos y autorizaciones necesarias para la realización de eventos.', '2023-12-01 22:48:17', NULL, NULL, 1),
	(6, 'Solicitud de Registro de Proveedores', 'Proceso para incorporar nuevos proveedores al sistema de la empresa.', '2023-12-01 22:48:17', NULL, NULL, 1),
	(7, 'Solicitud de Certificaciones o Documentos Oficiales', 'Obtención de certificaciones y documentos oficiales emitidos por la empresa.', '2023-12-01 22:48:17', NULL, NULL, 1),
	(8, 'Registro de Visitantes', 'Proceso para registrar la entrada y salida de visitantes a las instalaciones de la empresa.', '2023-12-01 22:48:17', NULL, NULL, 1),
	(9, 'Solicitud de Facturas o Documentación Financiera', 'Petición de facturas, recibos u otra documentación financiera por parte de clientes u otras entidades.', '2023-12-01 22:48:17', NULL, NULL, 1),
	(10, 'Solicitud de Autorización para Viajes de Negocios', 'Trámite para obtener autorización y coordinar detalles relacionados con los viajes de negocios de los empleados.', '2023-12-01 22:48:17', NULL, NULL, 1),
	(11, 'Solicitud de Material de Oficina', 'Pedido de suministros y material necesario para el funcionamiento de las distintas áreas.', '2023-12-01 22:48:17', NULL, NULL, 1),
	(12, 'Solicitud de Permiso o Licencia', 'Gestión de permisos y licencias para ausencias programadas de los empleados.', '2023-12-01 22:48:17', NULL, NULL, 1),
	(13, 'Reclamo de Gastos', 'Presentación y revisión de gastos realizados por empleados en nombre de la empresa.', '2023-12-01 22:48:17', NULL, NULL, 1),
	(14, 'Solicitud de Equipamiento o Tecnología', 'Pedido de nuevas herramientas, equipos o tecnologías para mejorar las operaciones internas.', '2023-12-01 22:48:17', NULL, NULL, 1),
	(15, 'Solicitud de Mantenimiento', 'Reporte y seguimiento de solicitudes de mantenimiento para equipos o instalaciones.', '2023-12-01 22:48:17', NULL, NULL, 1),
	(16, 'Solicitud de Capacitación', 'Registro para participar en programas de formación y capacitación.', '2023-12-01 22:48:17', NULL, NULL, 1),
	(17, 'Solicitud de Cambio de Turno o Horario', 'Gestión de cambios en los horarios laborales de los empleados.', '2023-12-01 22:48:17', NULL, NULL, 1),
	(18, 'Solicitud de Vacaciones', 'Proceso para solicitar y coordinar períodos de vacaciones.', '2023-12-01 22:48:17', NULL, NULL, 1),
	(19, 'Reclamo de Incidentes Laborales', 'Informe y seguimiento de incidentes laborales, como accidentes o problemas de seguridad.', '2023-12-01 22:48:17', NULL, NULL, 1),
	(20, 'Solicitud de Compra de Insumos', 'Registro de solicitudes para adquirir insumos necesarios para las operaciones.', '2023-12-01 22:48:17', NULL, NULL, 1),
	(21, 'Otro', 'Otro', '2023-12-01 22:48:17', NULL, NULL, 1),
	(22, 'test2', 'test2', '2023-12-01 22:58:54', '2023-12-01 22:59:06', '2023-12-01 22:59:14', 0);

-- Volcando estructura para tabla mesadepartes.tm_usuario
DROP TABLE IF EXISTS `tm_usuario`;
CREATE TABLE IF NOT EXISTS `tm_usuario` (
  `usu_id` int NOT NULL AUTO_INCREMENT,
  `usu_nomape` varchar(90) COLLATE utf8mb3_spanish_ci NOT NULL,
  `usu_correo` varchar(50) COLLATE utf8mb3_spanish_ci NOT NULL,
  `usu_pass` varchar(200) CHARACTER SET utf8mb3 COLLATE utf8mb3_spanish_ci DEFAULT NULL,
  `fech_crea` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `usu_img` varchar(500) COLLATE utf8mb3_spanish_ci DEFAULT NULL,
  `rol_id` int DEFAULT NULL,
  `fech_modi` datetime DEFAULT NULL,
  `fech_elim` datetime DEFAULT NULL,
  `fech_acti` datetime DEFAULT NULL,
  `est` int NOT NULL DEFAULT '2',
  PRIMARY KEY (`usu_id`)
) ENGINE=InnoDB AUTO_INCREMENT=119 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_spanish_ci;

-- Volcando datos para la tabla mesadepartes.tm_usuario: ~1 rows (aproximadamente)
REPLACE INTO `tm_usuario` (`usu_id`, `usu_nomape`, `usu_correo`, `usu_pass`, `fech_crea`, `usu_img`, `rol_id`, `fech_modi`, `fech_elim`, `fech_acti`, `est`) VALUES
	(104, 'AnderCode', 'davisanderson87@gmail.com', 'WhqngTAQ/foRYzqt0q2Nq5P5v99zoI8kbbRv89lfnMc=', '2023-11-24 22:46:03', 'https://lh3.googleusercontent.com/a/ACg8ocK0YDYkcL0iCtif1D1sXQM_3zW_wbm6RmiWsJtddXh-vw=s96-c', 3, NULL, NULL, NULL, 1),
	(116, 'Colaborador 1', 'davis_anderson_87@hotmail.com', '5JBWfphomFN3c22A13PnqfYQg9UrOBPXH3JCcdbCJAw=', '2023-12-04 21:21:44', '../../assets/picture/avatar.png', 2, '2023-12-05 21:36:04', NULL, NULL, 1),
	(117, 'Anderson Bastidas', 'davisandersonbastidas@gmail.com', 'SmSINV2dCf6PPC/YyUTz3S6X5uEUFkBP/KtnyMg+n5o=', '2023-12-06 12:54:25', 'https://lh3.googleusercontent.com/a/ACg8ocK8oC9uEATJoSe2sncZLYeyqqU0VLv5K3hJ885n3vxhe9s=s96-c', 1, NULL, NULL, NULL, 1),
	(118, 'Ander Code', 'andercode87@gmail.com', 'IZLcSzC0PzDOI3v4c6+gGE9WXT/W9oHbU907DDp1wTs=', '2023-12-31 20:18:32', 'https://lh3.googleusercontent.com/a/ACg8ocLqFfiwWkr8Wr9uqnwRekRm5TEgBh5dCdLuAksBeQQ1=s96-c', 1, NULL, NULL, NULL, 1);

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
