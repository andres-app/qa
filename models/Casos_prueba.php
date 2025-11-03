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
                    cp.resultado_esperado,
                    cp.estado_ejecucion,
                    cp.version,
                    DATE_FORMAT(cp.fecha_creacion, '%Y-%m-%d %H:%i') AS fecha_creacion
                FROM caso_prueba cp
                LEFT JOIN requerimiento r ON cp.id_requerimiento = r.id_requerimiento
                WHERE cp.estado = 1
                ORDER BY cp.id_caso DESC";

        $stmt = $conectar->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // ============================================================
    // OBTENER CASO POR ID
    // ============================================================
// ============================================================
// OBTENER CASO POR ID
// ============================================================
    public function get_caso_por_id($id_caso)
    {
        $conectar = parent::conexion();
        parent::set_names();

        $sql = "SELECT 
                cp.id_caso,
                cp.codigo,
                cp.nombre,
                cp.estado_ejecucion AS estado,
                r.codigo AS requerimiento
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
        $resultado_esperado,
        $version,
        $elaborado_por,
        $especialidad_id,
        $id_requerimiento,
        $estado_ejecucion,
        $resultado,
        $fecha_ejecucion,
        $observaciones
    ) {
        $conectar = parent::conexion();
        parent::set_names();

        $sql = "INSERT INTO caso_prueba
                (codigo, nombre, tipo_prueba, resultado_esperado, version, elaborado_por, especialidad_id,
                 id_requerimiento, estado_ejecucion, resultado, fecha_ejecucion, observaciones, creado_por,
                 fecha_creacion, estado)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), 1)";

        $stmt = $conectar->prepare($sql);
        $creado_por = $_SESSION["usu_nombre"] ?? 'admin';

        try {
            return $stmt->execute([
                $codigo,
                $nombre,
                $tipo_prueba,
                $resultado_esperado,
                $version,
                $elaborado_por,
                $especialidad_id,
                $id_requerimiento,
                $estado_ejecucion,
                $resultado,
                $fecha_ejecucion,
                $observaciones,
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
        $resultado_esperado,
        $version,
        $elaborado_por,
        $especialidad_id,
        $id_requerimiento,
        $estado_ejecucion,
        $resultado,
        $fecha_ejecucion,
        $observaciones
    ) {
        $conectar = parent::conexion();
        parent::set_names();

        $sql = "UPDATE caso_prueba SET
                    codigo = ?, nombre = ?, tipo_prueba = ?, resultado_esperado = ?, version = ?,
                    elaborado_por = ?, especialidad_id = ?, id_requerimiento = ?,
                    estado_ejecucion = ?, resultado = ?, fecha_ejecucion = ?, observaciones = ?,
                    actualizado_por = ?, fecha_actualizacion = NOW()
                WHERE id_caso = ?";

        $stmt = $conectar->prepare($sql);
        $actualizado_por = $_SESSION["usu_nombre"] ?? 'admin';

        try {
            return $stmt->execute([
                $codigo,
                $nombre,
                $tipo_prueba,
                $resultado_esperado,
                $version,
                $elaborado_por,
                $especialidad_id,
                $id_requerimiento,
                $estado_ejecucion,
                $resultado,
                $fecha_ejecucion,
                $observaciones,
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
        $actualizado_por = $_SESSION["usu_nombre"] ?? 'admin';

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
        $stmt->bindValue(2, $_SESSION["usu_nombre"] ?? 'admin');
        $stmt->bindValue(3, $id_caso);
        $stmt->execute();
    }


}
?>