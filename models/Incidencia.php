<?php
class Incidencia extends Conectar
{

    // 🟦 Listar incidencias
    public function listar()
    {
        $conectar = parent::conexion();
        parent::set_names();

        $sql = "SELECT i.*, u.usu_nomape AS analista
                FROM incidencia i
                LEFT JOIN tm_usuario u ON i.analista_id = u.usu_id
                ORDER BY i.id_incidencia DESC";
        $stmt = $conectar->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // 🟧 Actualizar incidencia completa
    public function actualizar($data)
    {
        $conectar = parent::conexion();
        parent::set_names();

        $sql = "UPDATE incidencia SET 
                descripcion = ?, 
                accion_recomendada = ?, 
                tipo_incidencia = ?, 
                prioridad = ?, 
                base_datos = ?, 
                version_origen = ?, 
                estado_incidencia = ?
            WHERE id_incidencia = ?";
        $stmt = $conectar->prepare($sql);
        $stmt->execute([
            $data["descripcion"],
            $data["accion_recomendada"],
            $data["tipo_incidencia"],
            $data["prioridad"],
            $data["base_datos"],
            $data["version_origen"],
            $data["estado_incidencia"],
            $data["id_incidencia"]
        ]);
    }


    // 🟩 Insertar incidencia
    public function insertar($data)
    {
        $conectar = parent::conexion();
        parent::set_names();

        $sql = "INSERT INTO incidencia
                (actividad, id_documentacion, descripcion, accion_recomendada,
                 fecha_recepcion, fecha_registro, fecha_respuesta, prioridad,
                 analista_id, tipo_incidencia, base_datos, version_origen, modulo, estado_incidencia)
                VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?)";

        $stmt = $conectar->prepare($sql);
        $stmt->execute([
            $data["actividad"] ?? '',
            $data["id_documentacion"] ?? null,
            $data["descripcion"] ?? '',
            $data["accion_recomendada"] ?? '',
            $data["fecha_recepcion"] ?? date('Y-m-d'),
            $data["fecha_registro"] ?? date('Y-m-d'),
            $data["fecha_respuesta"] ?? null, // ✅ controlado
            $data["prioridad"] ?? 'Media',
            $data["analista_id"],
            $data["tipo_incidencia"] ?? '',
            $data["base_datos"] ?? '',
            $data["version_origen"] ?? '',
            $data["modulo"] ?? '',
            $data["estado_incidencia"] ?? 'Pendiente'
        ]);


        // Retornar el ID recién insertado
        return $conectar->lastInsertId();
    }

    // 🟨 Mostrar incidencia
    public function mostrar($id)
    {
        $conectar = parent::conexion();
        parent::set_names();

        $sql = "SELECT i.*, u.usu_nomape AS analista
                FROM incidencia i
                LEFT JOIN tm_usuario u ON i.analista_id = u.usu_id
                WHERE i.id_incidencia = ?";
        $stmt = $conectar->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // 🟧 Actualizar estado
    public function actualizar_estado($id, $estado)
    {
        $conectar = parent::conexion();
        parent::set_names();

        $sql = "UPDATE incidencia SET estado_incidencia = ? WHERE id_incidencia = ?";
        $stmt = $conectar->prepare($sql);
        $stmt->execute([$estado, $id]);
    }

    // 🟪 Obtener correlativo (ahora usa el próximo ID autoincrement)
    public function get_correlativo()
    {
        $conectar = parent::conexion();
        parent::set_names();

        $sql = "SELECT AUTO_INCREMENT AS siguiente
                FROM information_schema.TABLES
                WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'incidencia'";
        $stmt = $conectar->prepare($sql);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return (int) $row["siguiente"];
    }

    public function editar($id_incidencia, $descripcion, $accion_recomendada, $tipo_incidencia, $prioridad, $base_datos, $version_origen, $estado_incidencia)
    {
        $conectar = parent::conexion();
        parent::set_names();

        $sql = "UPDATE incidencia 
            SET descripcion = ?, 
                accion_recomendada = ?, 
                tipo_incidencia = ?, 
                prioridad = ?, 
                base_datos = ?, 
                version_origen = ?, 
                estado_incidencia = ?
            WHERE id_incidencia = ?";
        $stmt = $conectar->prepare($sql);
        $stmt->execute([$descripcion, $accion_recomendada, $tipo_incidencia, $prioridad, $base_datos, $version_origen, $estado_incidencia, $id_incidencia]);
    }


}
?>