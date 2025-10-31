<?php
class Iteraciones extends Conectar
{
    public function get_iteraciones_por_caso($id_caso)
    {
        $conectar = parent::conexion();
        parent::set_names();

        $sql = "SELECT 
                    id_iteracion, id_caso, ejecutor_id, ejecutor_nombre,
                    DATE_FORMAT(fecha_ejecucion, '%Y-%m-%d %H:%i') AS fecha_ejecucion,
                    resultado, comentario, locked
                FROM iteracion
                WHERE id_caso = ?
                ORDER BY id_iteracion ASC";
        $stmt = $conectar->prepare($sql);
        $stmt->bindValue(1, $id_caso);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function insert_iteracion_full($id_caso, $ejecutor_nombre, $fecha_ejecucion, $resultado, $comentario, $cerrar)
    {
        $conectar = parent::conexion();
        parent::set_names();

        $sql = "INSERT INTO iteracion
                (id_caso, ejecutor_nombre, fecha_ejecucion, resultado, comentario, locked)
                VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $conectar->prepare($sql);
        $stmt->bindValue(1, $id_caso);
        $stmt->bindValue(2, $ejecutor_nombre);
        $stmt->bindValue(3, $fecha_ejecucion ?: null);
        $stmt->bindValue(4, $resultado);
        $stmt->bindValue(5, $comentario);
        $stmt->bindValue(6, $cerrar ? 1 : 0);
        $stmt->execute();
    }
}
