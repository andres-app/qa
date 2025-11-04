<?php
class Especialidad extends Conectar
{
    public function get_especialidades_activas()
    {
        $conectar = parent::conexion();
        parent::set_names();

        $sql = "SELECT id_especialidad, nombre 
                FROM especialidad 
                WHERE estado = 1 
                ORDER BY id_especialidad ASC";

        $stmt = $conectar->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
