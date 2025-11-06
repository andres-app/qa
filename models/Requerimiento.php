<?php
class Requerimiento extends Conectar
{
    // ============================================================
    // LISTAR TODOS LOS REQUERIMIENTOS ACTIVOS
    // ============================================================
    public function get_requerimientos()
    {
        $conectar = parent::conexion();
        parent::set_names();

        $sql = "SELECT 
                    id_requerimiento,
                    codigo,
                    nombre,
                    tipo,
                    prioridad,
                    estado_validacion,
                    version,
                    DATE_FORMAT(fecha_creacion, '%Y-%m-%d %H:%i') AS fecha_creacion
                FROM requerimiento
                WHERE estado = 1
                ORDER BY id_requerimiento DESC";

        $stmt = $conectar->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // ============================================================
    // OBTENER REQUERIMIENTO POR ID
    // ============================================================
    public function get_requerimiento_por_id($id_requerimiento)
    {
        $conectar = parent::conexion();
        parent::set_names();

        $sql = "SELECT 
                    id_requerimiento,
                    codigo,
                    nombre,
                    tipo,
                    prioridad,
                    estado_validacion,
                    version,
                    funcionalidad
                FROM requerimiento
                WHERE id_requerimiento = ?";

        $stmt = $conectar->prepare($sql);
        $stmt->execute([$id_requerimiento]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // ============================================================
    // INSERTAR NUEVO REQUERIMIENTO
    // ============================================================
    public function insertar_requerimiento($codigo, $nombre, $tipo, $prioridad, $estado_validacion, $version, $funcionalidad)
    {
        $conectar = parent::conexion();
        parent::set_names();

        $sql = "INSERT INTO requerimiento
                (codigo, nombre, tipo, prioridad, estado_validacion, version, funcionalidad, estado, creado_por, fecha_creacion)
                VALUES (?, ?, ?, ?, ?, ?, ?, 1, ?, NOW())";

        $stmt = $conectar->prepare($sql);
        $creado_por = isset($_SESSION["usu_nombre"]) ? $_SESSION["usu_nombre"] : 'Equipo de Calidad';

        try {
            return $stmt->execute([$codigo, $nombre, $tipo, $prioridad, $estado_validacion, $version, $funcionalidad, $creado_por]);
        } catch (PDOException $e) {
            error_log("Error al insertar requerimiento: " . $e->getMessage());
            return false;
        }
    }

    // ============================================================
    // EDITAR REQUERIMIENTO EXISTENTE
    // ============================================================
    public function editar_requerimiento($id, $codigo, $nombre, $tipo, $prioridad, $estado_validacion, $version, $funcionalidad)
    {
        $conectar = parent::conexion();
        parent::set_names();

        $sql = "UPDATE requerimiento
                SET 
                    codigo = ?,
                    nombre = ?,
                    tipo = ?,
                    prioridad = ?,
                    estado_validacion = ?,
                    version = ?,
                    funcionalidad = ?,
                    actualizado_por = ?,
                    fecha_actualizacion = NOW()
                WHERE id_requerimiento = ?";

        $stmt = $conectar->prepare($sql);
        $actualizado_por = isset($_SESSION["usu_nombre"]) ? $_SESSION["usu_nombre"] : 'Equipo de Calidad';

        try {
            return $stmt->execute([$codigo, $nombre, $tipo, $prioridad, $estado_validacion, $version, $funcionalidad, $actualizado_por, $id]);
        } catch (PDOException $e) {
            error_log("Error al editar requerimiento: " . $e->getMessage());
            return false;
        }
    }

    // ============================================================
    // CAMBIAR ESTADO (ELIMINAR LÓGICO)
    // ============================================================
    public function cambiar_estado($id_requerimiento, $estado)
    {
        $conectar = parent::conexion();
        parent::set_names();

        $sql = "UPDATE requerimiento 
                SET estado = ?, actualizado_por = ?, fecha_actualizacion = NOW()
                WHERE id_requerimiento = ?";

        $stmt = $conectar->prepare($sql);
        $actualizado_por = isset($_SESSION["usu_nombre"]) ? $_SESSION["usu_nombre"] : 'Equipo de Calidad';

        try {
            return $stmt->execute([$estado, $actualizado_por, $id_requerimiento]);
        } catch (PDOException $e) {
            error_log("Error al cambiar estado requerimiento: " . $e->getMessage());
            return false;
        }
    }

    // ============================================================
    // OBTENER TOTAL DE REQUERIMIENTOS ACTIVOS
    // ============================================================
    public function get_total_requerimientos()
    {
        $conectar = parent::conexion();
        parent::set_names();

        $sql = "SELECT COUNT(*) AS total FROM requerimiento WHERE estado = 1";
        $stmt = $conectar->prepare($sql);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // ============================================================
// LISTAR REQUERIMIENTOS ACTIVOS PARA COMBO SELECT
// ============================================================
    public function get_requerimientos_combo()
    {
        $conectar = parent::conexion();
        parent::set_names();

        $sql = "SELECT id_requerimiento, codigo, nombre 
            FROM requerimiento 
            WHERE estado = 1 
            ORDER BY codigo ASC";

        $stmt = $conectar->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

        // ============================================================
    // OBTENER ESPECIALIDADES Y ÓRGANOS JURISDICCIONALES ASOCIADOS
    // ============================================================
    public function get_relaciones($id_requerimiento)
    {
        $conectar = parent::conexion();
        parent::set_names();

        $sql = "
            SELECT 
                GROUP_CONCAT(DISTINCT e.nombre ORDER BY e.nombre SEPARATOR ', ') AS especialidades,
                GROUP_CONCAT(DISTINCT o.nombre ORDER BY o.nombre SEPARATOR ', ') AS organos
            FROM requerimiento r
            LEFT JOIN requerimiento_especialidad re ON r.id_requerimiento = re.id_requerimiento
            LEFT JOIN especialidad e ON re.id_especialidad = e.id_especialidad
            LEFT JOIN requerimiento_organo ro ON r.id_requerimiento = ro.id_requerimiento
            LEFT JOIN organo_jurisdiccional o ON ro.id_organo = o.id_organo
            WHERE r.id_requerimiento = ?
            GROUP BY r.id_requerimiento
        ";

        $stmt = $conectar->prepare($sql);
        $stmt->execute([$id_requerimiento]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }


}
?>