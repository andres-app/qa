# Sis_QA_Web
# Sis_QA_Web
# Sis_QA_Web

==================================================================================================================
CREAR LOS STORED PROCEDURE

DELIMITER $$
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_l_documento_01`(IN `xdoc_id` INT)
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
        CONCAT(DATE_FORMAT(tm_documento.fech_crea,'%m'),'-',DATE_FORMAT(tm_documento.fech_crea,'%Y'),'-',tm_documento.doc_id) AS nrotramite
    FROM tm_documento
    INNER JOIN tm_area ON tm_documento.area_id = tm_area.area_id
    INNER JOIN tm_tramite ON tm_documento.tra_id = tm_tramite.tra_id
    INNER JOIN tm_tipo ON tm_documento.tip_id = tm_tipo.tip_id
    INNER JOIN tm_usuario ON tm_documento.usu_id = tm_usuario.usu_id
    LEFT JOIN (
        SELECT doc_id, COUNT(*) AS cant
        FROM td_documento_detalle 
        WHERE doc_id = xdoc_id
        GROUP BY doc_id
    ) contador ON tm_documento.doc_id = contador.doc_id
    WHERE tm_documento.doc_id = xdoc_id;
END$$
DELIMITER ;

DELIMITER $$
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_i_area_01`(IN `xusu_id` INT)
BEGIN
    DECLARE areaCount INT;

    -- Contar el número de áreas ya asociadas al usuario
    SELECT COUNT(*) INTO areaCount 
    FROM td_area_detalle 
    WHERE usu_id = xusu_id;

    -- Si no hay áreas asociadas, insertar todas las áreas activas
    IF areaCount = 0 THEN
        INSERT INTO td_area_detalle (usu_id, area_id)
        SELECT xusu_id, area_id 
        FROM tm_area 
        WHERE est = 1;
    ELSE
        -- Si ya hay áreas asociadas, insertar solo las nuevas áreas no presentes
        INSERT INTO td_area_detalle (usu_id, area_id)
        SELECT xusu_id, area_id 
        FROM tm_area 
        WHERE est = 1 
        AND area_id NOT IN (
            SELECT area_id 
            FROM td_area_detalle 
            WHERE usu_id = xusu_id
        );
    END IF;

    -- Seleccionar la información de las áreas asociadas al usuario
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
        AND tm_area.est = 1;
END$$
DELIMITER ;

DELIMITER $$
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_i_rol_01`(IN `xrol_id` INT)
BEGIN
    DECLARE rolCount INT;

    -- Contar el número de menús ya asociados al rol
    SELECT COUNT(*) INTO rolCount 
    FROM td_menu_detalle 
    WHERE rol_id = xrol_id;

    -- Si no hay menús asociados, insertar todos los menús activos
    IF rolCount = 0 THEN
        INSERT INTO td_menu_detalle (rol_id, men_id)
        SELECT xrol_id, men_id 
        FROM tm_menu 
        WHERE est = 1;
    ELSE
        -- Si ya hay menús asociados, insertar solo los nuevos menús no presentes
        INSERT INTO td_menu_detalle (rol_id, men_id)
        SELECT xrol_id, men_id 
        FROM tm_menu 
        WHERE est = 1 
        AND men_id NOT IN (
            SELECT men_id 
            FROM td_menu_detalle 
            WHERE rol_id = xrol_id
        );
    END IF;

    -- Seleccionar la información de los menús asociados al rol
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
        AND tm_menu.est = 1;
END$$
DELIMITER ;

DELIMITER $$
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_l_documento_02`(IN `xusu_id` INT)
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
        CONCAT(DATE_FORMAT(tm_documento.fech_crea,'%m'),'-',DATE_FORMAT(tm_documento.fech_crea,'%Y'),'-',tm_documento.doc_id) AS nrotramite
    FROM tm_documento
    INNER JOIN tm_area ON tm_documento.area_id = tm_area.area_id
    INNER JOIN tm_tramite ON tm_documento.tra_id = tm_tramite.tra_id
    INNER JOIN tm_tipo ON tm_documento.tip_id = tm_tipo.tip_id
    INNER JOIN tm_usuario ON tm_documento.usu_id = tm_usuario.usu_id
    WHERE tm_documento.usu_id = xusu_id;
END$$
DELIMITER ;

==================================================================================================================