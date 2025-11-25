<?php
class Actividad extends Conectar
{

    public function listar()
    {
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
    public function insertar($data)
    {
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
    public function actualizar($data)
    {
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

    public function mostrar($id_actividad)
    {
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

    public function actualizar_estado($id, $nuevo_estado, $avance_front, $fecha_inicio_front, $fecha_respuesta_front, $observacion)
    {
        $conectar = parent::conexion();
        parent::set_names();

        // 1) OBTENER LA ACTIVIDAD ACTUAL
        $sql = "SELECT estado, fecha_inicio, fecha_respuesta FROM actividad WHERE id_actividad = ?";
        $stmt = $conectar->prepare($sql);
        $stmt->execute([$id]);
        $actual = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$actual) {
            return false;
        }

        $estado_actual = $actual["estado"];
        $fecha_inicio_actual = $actual["fecha_inicio"];
        $fecha_respuesta_actual = $actual["fecha_respuesta"];

        // ================================
        // 2) BLOQUEAR SI YA ESTÁ CERRADO
        // ================================
        if ($estado_actual === "Cerrado") {
            return false; // NO HACER NADA
        }

        // ==================================
        // 3) MANTENER FECHA INICIO CORRECTA
        // ==================================
        if ($estado_actual === "Pendiente" && $nuevo_estado === "En Progreso") {
            // Primera vez que pasa a En Progreso → Registra fecha inicio
            $fecha_inicio = date("Y-m-d H:i:s");
        } else {
            // Conservar la fecha inicio existente
            $fecha_inicio = $fecha_inicio_actual;
        }

        // ==========================================================
        // 4) MANTENER FECHA RESPUESTA O ASIGNARLA SEGÚN CORRESPONDA
        // ==========================================================
        if (($estado_actual === "En Progreso" && $nuevo_estado === "Atendido") ||
            ($estado_actual === "Atendido" && $nuevo_estado === "Cerrado")
        ) {

            // Se registra fecha respuesta cuando pasa a Atendido por primera vez
            if ($fecha_respuesta_actual == null) {
                $fecha_respuesta = date("Y-m-d H:i:s");
            } else {
                $fecha_respuesta = $fecha_respuesta_actual;
            }
        } else {
            // No cambiar la fecha de respuesta
            $fecha_respuesta = $fecha_respuesta_actual;
        }

        // ==========================
        // 5) CALCULAR AVANCE REAL
        // ==========================
        if ($nuevo_estado === "Pendiente") $avance = "0%";
        if ($nuevo_estado === "En Progreso") $avance = "50%";
        if ($nuevo_estado === "Atendido") $avance = "100%";
        if ($nuevo_estado === "Cerrado") $avance = "100%";

        // ===============================
        // 6) ACTUALIZAR EN LA BASE DE DATOS
        // ===============================
        $sql = "UPDATE actividad 
                SET 
                    estado = ?,
                    avance = ?,
                    fecha_inicio = ?,
                    fecha_respuesta = ?,
                    observacion = ?
                WHERE id_actividad = ?";

        $stmt = $conectar->prepare($sql);
        return $stmt->execute([
            $nuevo_estado,
            $avance,
            $fecha_inicio,
            $fecha_respuesta,
            $observacion,
            $id
        ]);
    }


    public function get_correlativo()
    {
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
