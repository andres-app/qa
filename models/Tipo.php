<?php
    class Tipo extends Conectar{

        public function get_tipo(){
             /* TODO: Obtener la conexión a la base de datos utilizando el método de la clase padre */
             $conectar = parent::conexion();
             /* TODO: Establecer el juego de caracteres a UTF-8 utilizando el método de la clase padre */
             parent::set_names();
             /* TODO: Consulta SQL para insertar un nuevo usuario en la tabla tm_usuario */
             $sql="SELECT * FROM tm_tipo WHERE est=1
             ORDER BY tip_nom";
             /* TODO:Preparar la consulta SQL */
             $sql=$conectar->prepare($sql);
             /* TODO: Ejecutar la consulta SQL */
             $sql->execute();
             return $sql->fetchAll();
        }

        public function insert_tipo($tip_nom){
            /* TODO: Obtener la conexión a la base de datos utilizando el método de la clase padre */
            $conectar = parent::conexion();
            /* TODO: Establecer el juego de caracteres a UTF-8 utilizando el método de la clase padre */
            parent::set_names();
            /* TODO: Consulta SQL para insertar un nuevo usuario en la tabla tm_usuario */
            $sql="INSERT INTO tm_tipo (tip_nom) VALUES (?)";
            /* TODO:Preparar la consulta SQL */
            $sql=$conectar->prepare($sql);
            $sql->bindValue(1,$tip_nom);
            /* TODO: Ejecutar la consulta SQL */
            $sql->execute();
        }

        public function update_tipo($tip_id,$tip_nom){
            /* TODO: Obtener la conexión a la base de datos utilizando el método de la clase padre */
            $conectar = parent::conexion();
            /* TODO: Establecer el juego de caracteres a UTF-8 utilizando el método de la clase padre */
            parent::set_names();
            /* TODO: Consulta SQL para insertar un nuevo usuario en la tabla tm_usuario */
            $sql="UPDATE tm_tipo
            SET
                tip_nom = ?,
                fech_modi = NOW()
            WHERE
                tip_id = ?";
            /* TODO:Preparar la consulta SQL */
            $sql=$conectar->prepare($sql);
            $sql->bindValue(1,$tip_nom);
            $sql->bindValue(2,$tip_id);
            /* TODO: Ejecutar la consulta SQL */
            $sql->execute();
        }

        public function get_tipo_nombre($tip_nom){
            /* TODO: Obtener la conexión a la base de datos utilizando el método de la clase padre */
            $conectar = parent::conexion();
            /* TODO: Establecer el juego de caracteres a UTF-8 utilizando el método de la clase padre */
            parent::set_names();
            /* TODO: Consulta SQL para insertar un nuevo usuario en la tabla tm_usuario */
            $sql="SELECT * FROM tm_tipo
                WHERE tip_nom = ?";
            /* TODO:Preparar la consulta SQL */
            $sql=$conectar->prepare($sql);
            /* TODO: Vincular los valores a los parámetros de la consulta */
            $sql->bindValue(1,$tip_nom);
            /* TODO: Ejecutar la consulta SQL */
            $sql->execute();
            return $sql->fetchAll();
        }

        public function get_tipo_x_id($tip_id){
            /* TODO: Obtener la conexión a la base de datos utilizando el método de la clase padre */
            $conectar = parent::conexion();
            /* TODO: Establecer el juego de caracteres a UTF-8 utilizando el método de la clase padre */
            parent::set_names();
            /* TODO: Consulta SQL para insertar un nuevo usuario en la tabla tm_usuario */
            $sql="SELECT * FROM tm_tipo
                WHERE tip_id = ?";
            /* TODO:Preparar la consulta SQL */
            $sql=$conectar->prepare($sql);
            /* TODO: Vincular los valores a los parámetros de la consulta */
            $sql->bindValue(1,$tip_id);
            /* TODO: Ejecutar la consulta SQL */
            $sql->execute();
            return $sql->fetchAll();
        }

        public function eliminar_tipo($tip_id){
            /* TODO: Obtener la conexión a la base de datos utilizando el método de la clase padre */
            $conectar = parent::conexion();
            /* TODO: Establecer el juego de caracteres a UTF-8 utilizando el método de la clase padre */
            parent::set_names();
            /* TODO: Consulta SQL para insertar un nuevo usuario en la tabla tm_usuario */
            $sql="UPDATE tm_tipo
                    SET
                        est = 0,
                        fech_elim = NOW()
                    WHERE
                        tip_id = ?";
            /* TODO:Preparar la consulta SQL */
            $sql=$conectar->prepare($sql);
            /* TODO: Vincular los valores a los parámetros de la consulta */
            $sql->bindValue(1,$tip_id);
            /* TODO: Ejecutar la consulta SQL */
            $sql->execute();
        }
    }
?>