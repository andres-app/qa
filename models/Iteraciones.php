<?php
class Iteraciones extends Conectar
{
    public function get_iteraciones_por_caso($id_caso)
    {
        $conectar = parent::conexion();
        parent::set_names();

        $sql = "SELECT 
        id_iteracion, numero_iteracion, id_caso, ejecutor_id, ejecutor_nombre,
        DATE_FORMAT(fecha_ejecucion, '%Y-%m-%d %H:%i') AS fecha_ejecucion,
        resultado, comentario, locked
    FROM iteracion
    WHERE id_caso = ?
    ORDER BY numero_iteracion ASC";

        $stmt = $conectar->prepare($sql);
        $stmt->bindValue(1, $id_caso);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function insert_iteracion_full($id_caso, $ejecutor_nombre, $fecha_ejecucion, $resultado, $comentario, $cerrar)
    {
        $conectar = parent::conexion();
        parent::set_names();

        // Obtener el número de iteración actual del caso
        $sql = "SELECT COUNT(*) AS total FROM iteracion WHERE id_caso = ?";
        $stmt = $conectar->prepare($sql);
        $stmt->bindValue(1, $id_caso);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $numero_iteracion = intval($row["total"]) + 1;

        // Insertar nueva iteración con el número calculado
        $sql = "INSERT INTO iteracion
                (id_caso, numero_iteracion, ejecutor_nombre, fecha_ejecucion, resultado, comentario, locked)
                VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conectar->prepare($sql);
        $stmt->bindValue(1, $id_caso);
        $stmt->bindValue(2, $numero_iteracion);
        $stmt->bindValue(3, $ejecutor_nombre);
        $stmt->bindValue(4, $fecha_ejecucion ?: null);
        $stmt->bindValue(5, $resultado);
        $stmt->bindValue(6, $comentario);
        $stmt->bindValue(7, $cerrar ? 1 : 0);
        $stmt->execute();
    }

}
