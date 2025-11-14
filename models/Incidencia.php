<?php
class Incidencia extends Conectar
{

    // 🟦 Listar incidencias (solo activas)
    public function listar()
    {
        $conectar = parent::conexion();
        parent::set_names();
    
        $sql = "SELECT 
                i.id_incidencia,
                i.correlativo_doc,
                i.actividad,
                d.nombre AS documentacion,   -- 👈 NUEVA COLUMNA AQUÍ
                i.modulo,
                i.descripcion,
                u.usu_nomape AS analista,
                i.prioridad,
                i.tipo_incidencia,
                i.fecha_registro,
                i.estado_incidencia
            FROM incidencia i
            LEFT JOIN tm_usuario u 
                ON i.analista_id = u.usu_id
            LEFT JOIN documentacion d 
                ON i.id_documentacion = d.id_documentacion  -- 👈 JOIN AQUÍ
            WHERE i.estado = 1
            ORDER BY i.id_incidencia DESC";
    
        $stmt = $conectar->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    

    // 🟧 Actualizar incidencia (funcional)
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
                modulo = ?, 
                estado_incidencia = ?      -- ✔ no se toca
            WHERE id_incidencia = ?";

        $stmt = $conectar->prepare($sql);
        $stmt->execute([
            $data["descripcion"],
            $data["accion_recomendada"],
            $data["tipo_incidencia"],
            $data["prioridad"],
            $data["base_datos"],
            $data["version_origen"],
            $data["modulo"],
            $data["estado_incidencia"],   // ✔ correcto
            $data["id_incidencia"]
        ]);
    }

    // 🟩 Insertar incidencia (estado siempre = 1)
    public function insertar($data)
    {
        $conectar = parent::conexion();
        parent::set_names();
    
        // 1️⃣ obtener correlativo inicial
        $correlativo_doc = !empty($data["correlativo_doc"])
            ? intval($data["correlativo_doc"])
            : $this->generar_correlativo_doc($data["id_documentacion"]);
    
        // 2️⃣ Intentar varias veces por si ocurre colisión
        $intentos = 3;
        while ($intentos > 0) {
    
            try {
    
                $sql = "INSERT INTO incidencia
                        (actividad, id_documentacion, correlativo_doc, descripcion, accion_recomendada,
                         fecha_recepcion, fecha_registro, fecha_respuesta, prioridad,
                         analista_id, tipo_incidencia, base_datos, version_origen, modulo,
                         estado_incidencia, estado)
                        VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,1)";
    
                $stmt = $conectar->prepare($sql);
                $stmt->execute([
                    $data["actividad"],
                    $data["id_documentacion"],
                    $correlativo_doc,
                    $data["descripcion"],
                    $data["accion_recomendada"],
                    $data["fecha_recepcion"],
                    $data["fecha_registro"],
                    $data["fecha_respuesta"] ?? null,
                    $data["prioridad"],
                    $data["analista_id"],
                    $data["tipo_incidencia"],
                    $data["base_datos"],
                    $data["version_origen"],
                    $data["modulo"],
                    $data["estado_incidencia"]
                ]);
    
                // ✔ Insert correcto → salir
                return $conectar->lastInsertId();
    
            } catch (PDOException $e) {
    
                // 🛑 Error 1062 = duplicado por colisión simultánea
                if ($e->errorInfo[1] == 1062) {
                    $correlativo_doc++;   // ➜ incrementar y reintentar
                    $intentos--;
                } else {
                    throw $e; // otro error, lo re-lanzamos
                }
            }
        }
    
        throw new Exception("No se pudo generar correlativo único");
    }
    
    

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

    // 🟧 Actualizar solo estado funcional
    public function actualizar_estado($id, $estado_incidencia)
    {
        $conectar = parent::conexion();
        parent::set_names();

        $sql = "UPDATE incidencia SET estado_incidencia = ? WHERE id_incidencia = ?";
        $stmt = $conectar->prepare($sql);
        $stmt->execute([$estado_incidencia, $id]);
    }

    // 🟥 Anular incidencia (estado = 0)
    public function anular($id_incidencia)
    {
        $conectar = parent::conexion();
        parent::set_names();

        $sql = "UPDATE incidencia SET estado = 0 WHERE id_incidencia = ?";
        $query = $conectar->prepare($sql);
        return $query->execute([$id_incidencia]);
    }

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
    public function incidencias_por_documento()
    {
        $conectar = parent::conexion();
        parent::set_names();

        $sql = "
            SELECT d.nombre AS documento,
                   COUNT(i.id_incidencia) AS total
            FROM documentacion d
            LEFT JOIN incidencia i ON i.id_documentacion = d.id_documentacion AND i.estado = 1
            WHERE d.estado = 1
            GROUP BY d.id_documentacion
            ORDER BY d.nombre ASC
        ";
        $stmt = $conectar->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function incidencias_por_modulo()
    {
        $conectar = parent::conexion();
        parent::set_names();

        $sql = "
        SELECT modulo,
               COUNT(id_incidencia) AS total
        FROM incidencia
        WHERE estado = 1
        GROUP BY modulo
        ORDER BY total DESC
    ";
        $stmt = $conectar->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function incidencias_por_mes()
{
    $conectar = parent::conexion();
    parent::set_names();

    $sql = "
        SELECT DATE_FORMAT(fecha_registro, '%Y-%m') AS periodo,
               COUNT(id_incidencia) AS total
        FROM incidencia
        WHERE estado = 1
        GROUP BY DATE_FORMAT(fecha_registro, '%Y-%m')
        ORDER BY periodo ASC
    ";
    $stmt = $conectar->prepare($sql);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

public function contar_todas()
{
    $conectar = parent::conexion();
    parent::set_names();
    $sql = "SELECT COUNT(*) total FROM incidencia WHERE estado = 1";
    return $conectar->query($sql)->fetchColumn();
}

public function generar_correlativo_doc($id_documentacion)
{
    $conectar = parent::conexion();
    parent::set_names();

    $sql = "SELECT IFNULL(MAX(correlativo_doc),0)+1 AS correlativo
            FROM incidencia
            WHERE id_documentacion = ?";

    $stmt = $conectar->prepare($sql);
    $stmt->execute([$id_documentacion]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    return intval($row["correlativo"]);
}


}
?>