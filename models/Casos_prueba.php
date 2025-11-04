<?php
class Casos_prueba extends Conectar
{
    // ============================================================
    // LISTAR CASOS ACTIVOS
    // ============================================================
    public function get_casos()
    {
        $conectar = parent::conexion();
        parent::set_names();

        $sql = "SELECT 
                    cp.id_caso,
                    cp.codigo,
                    cp.nombre,
                    r.codigo AS requerimiento,
                    cp.tipo_prueba,
                    e.nombre AS especialidad,   -- ✅ NUEVO
                    cp.estado_ejecucion,
                    cp.version,
                    DATE_FORMAT(cp.fecha_creacion, '%Y-%m-%d %H:%i') AS fecha_creacion
                FROM caso_prueba cp
                LEFT JOIN requerimiento r ON cp.id_requerimiento = r.id_requerimiento
                LEFT JOIN especialidad e ON cp.especialidad_id = e.id_especialidad  -- ✅ NUEVO
                WHERE cp.estado = 1
                ORDER BY cp.id_caso DESC";

        $stmt = $conectar->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    // ============================================================
// OBTENER CASO POR ID
// ============================================================
    public function get_caso_por_id($id_caso)
    {
        $conectar = parent::conexion();
        parent::set_names();

        $sql = "SELECT 
                cp.id_caso AS id_caso_prueba,
                cp.codigo,
                cp.nombre,
                cp.id_requerimiento,
                r.codigo AS requerimiento_codigo,
                r.nombre AS requerimiento_nombre,
                cp.tipo_prueba,
                cp.version,
                cp.estado_ejecucion,
                cp.especialidad_id,
                cp.elaborado_por,
                cp.descripcion,
                DATE_FORMAT(cp.fecha_ejecucion, '%Y-%m-%d') AS fecha_ejecucion
            FROM caso_prueba cp
            LEFT JOIN requerimiento r ON cp.id_requerimiento = r.id_requerimiento
            WHERE cp.id_caso = ?";

        $stmt = $conectar->prepare($sql);
        $stmt->bindValue(1, $id_caso);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }


    // ============================================================
    // INSERTAR CASO DE PRUEBA
    // ============================================================
    public function insertar_caso(
        $codigo,
        $nombre,
        $tipo_prueba,
        $version,
        $elaborado_por,
        $descripcion,       // 👈 nuevo parámetro
        $especialidad_id,
        $id_requerimiento,
        $estado_ejecucion,
        $fecha_ejecucion
    ) {
        $conectar = parent::conexion();
        parent::set_names();

        $sql = "INSERT INTO caso_prueba
                (codigo, nombre, tipo_prueba, version, elaborado_por, descripcion,
                 especialidad_id, id_requerimiento, estado_ejecucion, fecha_ejecucion, 
                 creado_por, fecha_creacion, estado)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), 1)";

        $stmt = $conectar->prepare($sql);
        $creado_por = $_SESSION["usu_nombre"] ?? 'Equipo de Calidad';

        try {
            return $stmt->execute([
                $codigo,
                $nombre,
                $tipo_prueba,
                $version,
                $elaborado_por,
                $descripcion,        // 👈 incluido
                $especialidad_id,
                $id_requerimiento,
                $estado_ejecucion,
                $fecha_ejecucion,
                $creado_por
            ]);
        } catch (PDOException $e) {
            error_log("Error al insertar caso de prueba: " . $e->getMessage());
            return false;
        }
    }


    // ============================================================
    // EDITAR CASO
    // ============================================================
    public function editar_caso(
        $id_caso,
        $codigo,
        $nombre,
        $tipo_prueba,
        $version,
        $elaborado_por,
        $descripcion,        // 👈 nuevo parámetro
        $especialidad_id,
        $id_requerimiento,
        $estado_ejecucion,
        $fecha_ejecucion
    ) {
        $conectar = parent::conexion();
        parent::set_names();

        $sql = "UPDATE caso_prueba SET
                    codigo = ?, nombre = ?, tipo_prueba = ?, version = ?,
                    elaborado_por = ?, descripcion = ?, especialidad_id = ?, id_requerimiento = ?,
                    estado_ejecucion = ?, fecha_ejecucion = ?,
                    actualizado_por = ?, fecha_actualizacion = NOW()
                WHERE id_caso = ?";

        $stmt = $conectar->prepare($sql);
        $actualizado_por = $_SESSION["usu_nombre"] ?? 'Equipo de Calidad';

        try {
            return $stmt->execute([
                $codigo,
                $nombre,
                $tipo_prueba,
                $version,
                $elaborado_por,
                $descripcion,        // 👈 incluido
                $especialidad_id,
                $id_requerimiento,
                $estado_ejecucion,
                $fecha_ejecucion,
                $actualizado_por,
                $id_caso
            ]);
        } catch (PDOException $e) {
            error_log("Error al editar caso de prueba: " . $e->getMessage());
            return false;
        }
    }


    // ============================================================
    // CAMBIAR ESTADO
    // ============================================================
    public function cambiar_estado($id_caso, $estado)
    {
        $conectar = parent::conexion();
        parent::set_names();

        $sql = "UPDATE caso_prueba
                SET estado = ?, actualizado_por = ?, fecha_actualizacion = NOW()
                WHERE id_caso = ?";

        $stmt = $conectar->prepare($sql);
        $actualizado_por = $_SESSION["usu_nombre"] ?? 'Equipo de Calidad';

        try {
            return $stmt->execute([$estado, $actualizado_por, $id_caso]);
        } catch (PDOException $e) {
            error_log("Error al cambiar estado: " . $e->getMessage());
            return false;
        }
    }

    public function actualizar_estado_caso($id_caso, $estado_ejecucion)
    {
        $conectar = parent::conexion();
        parent::set_names();

        $sql = "UPDATE caso_prueba 
                SET estado_ejecucion = ?, actualizado_por = ?, fecha_actualizacion = NOW()
                WHERE id_caso = ?";

        $stmt = $conectar->prepare($sql);
        $stmt->bindValue(1, $estado_ejecucion);
        $stmt->bindValue(2, $_SESSION["usu_nombre"] ?? 'Equipo de Calidad');
        $stmt->bindValue(3, $id_caso);
        $stmt->execute();
    }

    // ============================================================
// GENERAR CÓDIGO AUTOMÁTICO POR REQUERIMIENTO
// ============================================================
    public function generar_codigo_por_requerimiento($id_requerimiento)
    {
        $conectar = parent::conexion();
        parent::set_names();

        // 1️⃣ Obtener el código del requerimiento (ej: RFU-GRE-02)
        $sql_req = "SELECT codigo FROM requerimiento WHERE id_requerimiento = ?";
        $stmt_req = $conectar->prepare($sql_req);
        $stmt_req->execute([$id_requerimiento]);
        $req = $stmt_req->fetch(PDO::FETCH_ASSOC);

        if (!$req) {
            return ["error" => "Requerimiento no encontrado"];
        }

        $codigo_req = $req["codigo"];

        // 2️⃣ Extraer la parte 'GRE-02' del código (después de RFU-)
        preg_match('/[A-Z]+-\d+$/', $codigo_req, $matches);
        $parte_req = $matches ? $matches[0] : substr($codigo_req, -6);

        // 3️⃣ Contar los casos ya registrados para ese requerimiento
        $sql_cp = "SELECT COUNT(*) AS total FROM caso_prueba WHERE id_requerimiento = ?";
        $stmt_cp = $conectar->prepare($sql_cp);
        $stmt_cp->execute([$id_requerimiento]);
        $row = $stmt_cp->fetch(PDO::FETCH_ASSOC);

        // 4️⃣ Generar número correlativo con dos dígitos (01, 02, 03…)
        $nuevo_num = str_pad($row["total"] + 1, 2, "0", STR_PAD_LEFT);

        // 5️⃣ Armar código final
        $nuevo_codigo = "CP-" . $parte_req . "-" . $nuevo_num;

        return ["codigo" => $nuevo_codigo];
    }



}
?>