<?php
    /* TODO: Definición de la clase Documento que extiende la clase Conectar */
    class Documento extends Conectar{

        /* TODO: Método para registrar un nuevo usuario en la base de datos */
        public function registrar_documento($area_id,$tra_id,$doc_externo,$tip_id,$doc_dni,$doc_nom,$doc_descrip,$usu_id){
            /* TODO: Obtener la conexión a la base de datos utilizando el método de la clase padre */
            $conectar = parent::conexion();
            /* TODO: Establecer el juego de caracteres a UTF-8 utilizando el método de la clase padre */
            parent::set_names();
            /* TODO: Consulta SQL para insertar un nuevo usuario en la tabla tm_usuario */
            $sql="INSERT INTO tm_documento
                (area_id,tra_id,doc_externo,tip_id,doc_dni,doc_nom,doc_descrip,usu_id)
                VALUES
                (?,?,?,?,?,?,?,?)";
            /* TODO:Preparar la consulta SQL */
            $sql=$conectar->prepare($sql);
            /* TODO: Vincular los valores a los parámetros de la consulta */
            $sql->bindValue(1,$area_id);
            $sql->bindValue(2,$tra_id);
            $sql->bindValue(3,$doc_externo);
            $sql->bindValue(4,$tip_id);
            $sql->bindValue(5,$doc_dni);
            $sql->bindValue(6,$doc_nom);
            $sql->bindValue(7,$doc_descrip);
            $sql->bindValue(8,$usu_id);
            /* TODO: Ejecutar la consulta SQL */
            $sql->execute();

            $sql1="select last_insert_id() as 'doc_id'";
            $sql1=$conectar->prepare($sql1);
            $sql1->execute();
            return $sql1->fetchAll(pdo::FETCH_ASSOC);
        }

        public function insert_documento_detalle($doc_id,$det_nom,$usu_id,$det_tipo){
            /* TODO: Obtener la conexión a la base de datos utilizando el método de la clase padre */
            $conectar = parent::conexion();
            /* TODO: Establecer el juego de caracteres a UTF-8 utilizando el método de la clase padre */
            parent::set_names();
            /* TODO: Consulta SQL para insertar un nuevo usuario en la tabla tm_usuario */
            $sql="INSERT INTO td_documento_detalle (doc_id,det_nom,usu_id,det_tipo)
                VALUES (?,?,?,?)";
            /* TODO:Preparar la consulta SQL */
            $sql=$conectar->prepare($sql);
            /* TODO: Vincular los valores a los parámetros de la consulta */
            $sql->bindValue(1,$doc_id);
            $sql->bindValue(2,$det_nom);
            $sql->bindValue(3,$usu_id);
            $sql->bindValue(4,$det_tipo);
            /* TODO: Ejecutar la consulta SQL */
            $sql->execute();
        }

        public function get_documento_x_id($doc_id){
            /* TODO: Obtener la conexión a la base de datos utilizando el método de la clase padre */
            $conectar = parent::conexion();
            /* TODO: Establecer el juego de caracteres a UTF-8 utilizando el método de la clase padre */
            parent::set_names();
            /* TODO: Consulta SQL para insertar un nuevo usuario en la tabla tm_usuario */
            $sql="CALL sp_l_documento_01(?);";
            /* TODO:Preparar la consulta SQL */
            $sql=$conectar->prepare($sql);
            /* TODO: Vincular los valores a los parámetros de la consulta */
            $sql->bindValue(1,$doc_id);
            /* TODO: Ejecutar la consulta SQL */
            $sql->execute();
            return $sql->fetchAll(pdo::FETCH_ASSOC);
        }

        public function get_documento_x_usu($usu_id){
            /* TODO: Obtener la conexión a la base de datos utilizando el método de la clase padre */
            $conectar = parent::conexion();
            /* TODO: Establecer el juego de caracteres a UTF-8 utilizando el método de la clase padre */
            parent::set_names();
            /* TODO: Consulta SQL para insertar un nuevo usuario en la tabla tm_usuario */
            $sql="CALL sp_l_documento_02(?);";
            /* TODO:Preparar la consulta SQL */
            $sql=$conectar->prepare($sql);
            /* TODO: Vincular los valores a los parámetros de la consulta */
            $sql->bindValue(1,$usu_id);
            /* TODO: Ejecutar la consulta SQL */
            $sql->execute();
            return $sql->fetchAll(pdo::FETCH_ASSOC);
        }

        public function get_documento_x_area($area_id,$doc_estado){
            /* TODO: Obtener la conexión a la base de datos utilizando el método de la clase padre */
            $conectar = parent::conexion();
            /* TODO: Establecer el juego de caracteres a UTF-8 utilizando el método de la clase padre */
            parent::set_names();
            /* TODO: Consulta SQL para insertar un nuevo usuario en la tabla tm_usuario */
            $sql="SELECT 
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
                WHERE tm_documento.area_id = ?
                AND tm_documento.doc_estado = ?";
            /* TODO:Preparar la consulta SQL */
            $sql=$conectar->prepare($sql);
            /* TODO: Vincular los valores a los parámetros de la consulta */
            $sql->bindValue(1,$area_id);
            $sql->bindValue(2,$doc_estado);
            /* TODO: Ejecutar la consulta SQL */
            $sql->execute();
            return $sql->fetchAll(pdo::FETCH_ASSOC);
        }

        public function get_documento_detalle_x_doc_id($doc_id,$det_tipo){
            /* TODO: Obtener la conexión a la base de datos utilizando el método de la clase padre */
            $conectar = parent::conexion();
            /* TODO: Establecer el juego de caracteres a UTF-8 utilizando el método de la clase padre */
            parent::set_names();
            /* TODO: Consulta SQL para insertar un nuevo usuario en la tabla tm_usuario */
            $sql="SELECT 
                td_documento_detalle.det_id,
                td_documento_detalle.doc_id,
                td_documento_detalle.det_nom,
                td_documento_detalle.usu_id,
                tm_usuario.usu_nomape,
                tm_usuario.usu_correo,
                tm_usuario.usu_img,
                td_documento_detalle.fech_crea 
                FROM td_documento_detalle
                INNER JOIN tm_usuario ON td_documento_detalle.usu_id = tm_usuario.usu_id
                WHERE
                td_documento_detalle.doc_id = ?
                AND td_documento_detalle.det_tipo = ?";
            /* TODO:Preparar la consulta SQL */
            $sql=$conectar->prepare($sql);
            /* TODO: Vincular los valores a los parámetros de la consulta */
            $sql->bindValue(1,$doc_id);
            $sql->bindValue(2,$det_tipo);
            /* TODO: Ejecutar la consulta SQL */
            $sql->execute();
            return $sql->fetchAll(pdo::FETCH_ASSOC);
        }

        public function actualizar_respuesta_documento($doc_id,$doc_respuesta,$doc_usu_terminado){
            /* TODO: Obtener la conexión a la base de datos utilizando el método de la clase padre */
            $conectar = parent::conexion();
            /* TODO: Establecer el juego de caracteres a UTF-8 utilizando el método de la clase padre */
            parent::set_names();
            /* TODO: Consulta SQL para insertar un nuevo usuario en la tabla tm_usuario */
            $sql="UPDATE tm_documento
                SET
                    doc_respuesta = ?,
                    doc_usu_terminado = ?,
                    fech_terminado = NOW(),
                    doc_estado='Terminado'
                WHERE
                    doc_id = ?";
            /* TODO:Preparar la consulta SQL */
            $sql=$conectar->prepare($sql);
            /* TODO: Vincular los valores a los parámetros de la consulta */
            $sql->bindValue(1,$doc_respuesta);
            $sql->bindValue(2,$doc_usu_terminado);
            $sql->bindValue(3,$doc_id);
            /* TODO: Ejecutar la consulta SQL */
            $sql->execute();
        }

        public function get_documento_x_usu_terminado($doc_usu_terminado){
            /* TODO: Obtener la conexión a la base de datos utilizando el método de la clase padre */
            $conectar = parent::conexion();
            /* TODO: Establecer el juego de caracteres a UTF-8 utilizando el método de la clase padre */
            parent::set_names();
            /* TODO: Consulta SQL para insertar un nuevo usuario en la tabla tm_usuario */
            $sql="SELECT 
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
                WHERE tm_documento.doc_usu_terminado = ?";
            /* TODO:Preparar la consulta SQL */
            $sql=$conectar->prepare($sql);
            /* TODO: Vincular los valores a los parámetros de la consulta */
            $sql->bindValue(1,$doc_usu_terminado);
            /* TODO: Ejecutar la consulta SQL */
            $sql->execute();
            return $sql->fetchAll(pdo::FETCH_ASSOC);
        }

    }
?>