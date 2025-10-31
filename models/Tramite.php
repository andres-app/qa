<?php
    class Tramite extends Conectar{

        public function get_tramite(){
             /* TODO: Obtener la conexión a la base de datos utilizando el método de la clase padre */
             $conectar = parent::conexion();
             /* TODO: Establecer el juego de caracteres a UTF-8 utilizando el método de la clase padre */
             parent::set_names();
             /* TODO: Consulta SQL para insertar un nuevo usuario en la tabla tm_usuario */
             $sql="SELECT * FROM tm_tramite WHERE est=1";
             /* TODO:Preparar la consulta SQL */
             $sql=$conectar->prepare($sql);
             /* TODO: Ejecutar la consulta SQL */
             $sql->execute();
             return $sql->fetchAll();
        }

        public function insert_tramite($tra_nom,$tra_descrip){
            /* TODO: Obtener la conexión a la base de datos utilizando el método de la clase padre */
            $conectar = parent::conexion();
            /* TODO: Establecer el juego de caracteres a UTF-8 utilizando el método de la clase padre */
            parent::set_names();
            /* TODO: Consulta SQL para insertar un nuevo usuario en la tabla tm_usuario */
            $sql="INSERT INTO tm_tramite (tra_nom,tra_descrip) VALUES (?,?)";
            /* TODO:Preparar la consulta SQL */
            $sql=$conectar->prepare($sql);
            $sql->bindValue(1,$tra_nom);
            $sql->bindValue(2,$tra_descrip);
            /* TODO: Ejecutar la consulta SQL */
            $sql->execute();
        }

        public function update_tramite($tra_id,$tra_nom,$tra_descrip){
            /* TODO: Obtener la conexión a la base de datos utilizando el método de la clase padre */
            $conectar = parent::conexion();
            /* TODO: Establecer el juego de caracteres a UTF-8 utilizando el método de la clase padre */
            parent::set_names();
            /* TODO: Consulta SQL para insertar un nuevo usuario en la tabla tm_usuario */
            $sql="UPDATE tm_tramite
            SET
                tra_nom = ?,
                tra_descrip = ?,
                fech_modi = NOW()
            WHERE
                tra_id = ?";
            /* TODO:Preparar la consulta SQL */
            $sql=$conectar->prepare($sql);
            $sql->bindValue(1,$tra_nom);
            $sql->bindValue(2,$tra_descrip);
            $sql->bindValue(3,$tra_id);
            /* TODO: Ejecutar la consulta SQL */
            $sql->execute();
        }

        public function get_tra_nombre($tra_nom){
            /* TODO: Obtener la conexión a la base de datos utilizando el método de la clase padre */
            $conectar = parent::conexion();
            /* TODO: Establecer el juego de caracteres a UTF-8 utilizando el método de la clase padre */
            parent::set_names();
            /* TODO: Consulta SQL para insertar un nuevo usuario en la tabla tm_usuario */
            $sql="SELECT * FROM tm_tramite
                WHERE tra_nom = ?";
            /* TODO:Preparar la consulta SQL */
            $sql=$conectar->prepare($sql);
            /* TODO: Vincular los valores a los parámetros de la consulta */
            $sql->bindValue(1,$tra_nom);
            /* TODO: Ejecutar la consulta SQL */
            $sql->execute();
            return $sql->fetchAll();
        }

        public function get_tra_x_id($tra_id){
            /* TODO: Obtener la conexión a la base de datos utilizando el método de la clase padre */
            $conectar = parent::conexion();
            /* TODO: Establecer el juego de caracteres a UTF-8 utilizando el método de la clase padre */
            parent::set_names();
            /* TODO: Consulta SQL para insertar un nuevo usuario en la tabla tm_usuario */
            $sql="SELECT * FROM tm_tramite
                WHERE tra_id = ?";
            /* TODO:Preparar la consulta SQL */
            $sql=$conectar->prepare($sql);
            /* TODO: Vincular los valores a los parámetros de la consulta */
            $sql->bindValue(1,$tra_id);
            /* TODO: Ejecutar la consulta SQL */
            $sql->execute();
            return $sql->fetchAll();
        }

        public function eliminar_tramite($tra_id){
            /* TODO: Obtener la conexión a la base de datos utilizando el método de la clase padre */
            $conectar = parent::conexion();
            /* TODO: Establecer el juego de caracteres a UTF-8 utilizando el método de la clase padre */
            parent::set_names();
            /* TODO: Consulta SQL para insertar un nuevo usuario en la tabla tm_usuario */
            $sql="UPDATE tm_tramite
                    SET
                        est = 0,
                        fech_elim = NOW()
                    WHERE
                        tra_id = ?";
            /* TODO:Preparar la consulta SQL */
            $sql=$conectar->prepare($sql);
            /* TODO: Vincular los valores a los parámetros de la consulta */
            $sql->bindValue(1,$tra_id);
            /* TODO: Ejecutar la consulta SQL */
            $sql->execute();
        }
    }
?>