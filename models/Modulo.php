<?php
class Modulo extends Conectar {

    public function listar_activos() {
        $conectar = parent::conexion();
        parent::set_names();

        $sql = "SELECT id_modulo, nombre 
                FROM modulos 
                WHERE estado = 1
                ORDER BY nombre ASC";

        $query = $conectar->prepare($sql);
        $query->execute();
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }
}
