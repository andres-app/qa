<?php
class Actividad extends Conectar {

    public function listar() {
        $conectar = parent::conexion();
        parent::set_names();
    
        $sql = "SELECT 
                    a.id_actividad,
                    a.colaborador_id,
                    a.actividad,
                    a.descripcion,
                    a.fecha_recepcion,
                    a.fecha_inicio,
                    a.fecha_respuesta,
                    a.estado,
                    a.avance,
                    a.prioridad,
                    a.est AS estado_logico,
                    u.usu_nomape AS colaborador
                FROM actividad a
                LEFT JOIN tm_usuario u ON a.colaborador_id = u.usu_id
                WHERE a.est = 1
                ORDER BY a.id_actividad DESC";
    
        $stmt = $conectar->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    

    // ✅ INSERTAR NUEVA ACTIVIDAD
    public function insertar($data) {
        $conectar = parent::conexion();
        parent::set_names();

        $sql = "INSERT INTO actividad 
                (colaborador_id, actividad, descripcion, fecha_recepcion,
                 fecha_inicio, fecha_respuesta, estado, avance, prioridad)
                VALUES (?, ?, ?, ?, NULL, NULL, 'Pendiente', '0%', ?)";

        $stmt = $conectar->prepare($sql);
        $stmt->execute([
            $data["colaborador_id"],
            $data["actividad"],
            $data["descripcion"],
            $data["fecha_recepcion"],
            $data["prioridad"]
        ]);
    }

    // ✅ ACTUALIZAR ACTIVIDAD EXISTENTE (EDICIÓN)
    public function actualizar($data) {
        $conectar = parent::conexion();
        parent::set_names();

        $sql = "UPDATE actividad 
                SET colaborador_id = ?, 
                    actividad      = ?, 
                    descripcion    = ?, 
                    fecha_recepcion = ?, 
                    prioridad      = ?
                WHERE id_actividad = ?";

        $stmt = $conectar->prepare($sql);
        $stmt->execute([
            $data["colaborador_id"],
            $data["actividad"],
            $data["descripcion"],
            $data["fecha_recepcion"],
            $data["prioridad"],
            $data["id_actividad"]
        ]);
    }

    public function mostrar($id_actividad) {
        $conectar = parent::conexion();
        parent::set_names();

        $sql = "SELECT a.*, u.usu_nomape AS colaborador
                FROM actividad a
                LEFT JOIN tm_usuario u ON a.colaborador_id = u.usu_id
                WHERE a.id_actividad = ?";
        $stmt = $conectar->prepare($sql);
        $stmt->execute([$id_actividad]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function actualizar_estado($id, $estado, $avance, $fecha_inicio, $fecha_respuesta, $observacion)
    {
        $conectar = parent::conexion();
        parent::set_names();
    
        $sql = "UPDATE actividad 
                SET estado = ?, 
                    avance = ?, 
                    fecha_inicio = IFNULL(?, fecha_inicio),
                    fecha_respuesta = IFNULL(?, fecha_respuesta),
                    observacion = ?
                WHERE id_actividad = ?";
    
        $stmt = $conectar->prepare($sql);
        return $stmt->execute([
            $estado,
            $avance,
            $fecha_inicio,
            $fecha_respuesta,
            $observacion,
            $id
        ]);
    }
    
    

    public function get_correlativo() {
        $conectar = parent::conexion();
        parent::set_names();

        $sql = "SELECT IFNULL(MAX(id_actividad), 0) + 1 AS id FROM actividad";
        $stmt = $conectar->prepare($sql);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return (int)$row["id"];
    }

    public function eliminar($id_actividad)
    {
        $conectar = parent::conexion();
        parent::set_names();
    
        $sql = "UPDATE actividad SET est = 0 WHERE id_actividad = ?";
        $stmt = $conectar->prepare($sql);
        return $stmt->execute([$id_actividad]);
    }
    
    
}

?>
