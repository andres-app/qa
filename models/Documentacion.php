<?php
class Documentacion extends Conectar
{

    // 🟩 Listar todos los registros
    public function listar()
    {
        $conectar = parent::conexion();
        parent::set_names();

        $sql = "SELECT 
    id_documentacion,
    nombre,
    descripcion,
    fecha_recepcion,
    fecha_creacion, 
    tipo_documento,
    estado
FROM documentacion
WHERE estado = 1
ORDER BY fecha_recepcion DESC";


        $stmt = $conectar->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // 🟦 Obtener una documentación específica
    public function mostrar($id_documentacion)
    {
        $conectar = parent::conexion();
        parent::set_names();

        $sql = "SELECT * FROM documentacion WHERE id_documentacion = ?";
        $stmt = $conectar->prepare($sql);
        $stmt->execute([$id_documentacion]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // 🟨 Insertar nueva documentación
    public function insertar($nombre, $descripcion, $fecha_recepcion, $tipo_documento)
    {
        $conectar = parent::conexion();
        parent::set_names();

        $sql = "INSERT INTO documentacion (nombre, descripcion, fecha_recepcion, tipo_documento, estado)
                VALUES (?, ?, ?, ?, 1)";
        $stmt = $conectar->prepare($sql);
        $stmt->execute([$nombre, $descripcion, $fecha_recepcion, $tipo_documento]);
    }

    // 🟧 Actualizar registro existente
    public function actualizar($id_documentacion, $nombre, $descripcion, $fecha_recepcion, $tipo_documento)
    {
        $conectar = parent::conexion();
        parent::set_names();

        $sql = "UPDATE documentacion 
                SET nombre = ?, descripcion = ?, fecha_recepcion = ?, tipo_documento = ?
                WHERE id_documentacion = ?";
        $stmt = $conectar->prepare($sql);
        $stmt->execute([$nombre, $descripcion, $fecha_recepcion, $tipo_documento, $id_documentacion]);
    }

    // 🟥 Eliminación lógica
    public function eliminar($id_documentacion)
    {
        $conectar = parent::conexion();
        parent::set_names();

        $sql = "UPDATE documentacion SET estado = 0 WHERE id_documentacion = ?";
        $stmt = $conectar->prepare($sql);
        $stmt->execute([$id_documentacion]);
    }

    // 🟪 Combo para otros módulos (incidencias, requerimientos, etc.)
    public function combo()
    {
        $conectar = parent::conexion();
        parent::set_names();

        // 🔹 Devuelve nombre y fecha como campos separados (sin concatenar)
        $sql = "SELECT 
                    id_documentacion, 
                    nombre, 
                    fecha_recepcion
                FROM documentacion
                WHERE estado = 1
                ORDER BY fecha_recepcion DESC";
        $stmt = $conectar->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function listar_incidencias_x_documentacion($id_documentacion)
    {
        $conectar = parent::conexion();
        parent::set_names();

        $sql = "SELECT i.id_incidencia, i.actividad, i.descripcion, 
                   u.usu_nomape AS analista
            FROM incidencia i
            LEFT JOIN tm_usuario u ON u.usu_id = i.analista_id
            WHERE i.id_documentacion = ? AND i.estado = 1
            ORDER BY i.id_incidencia ASC";

        $stmt = $conectar->prepare($sql);
        $stmt->bindValue(1, $id_documentacion);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


}
?>