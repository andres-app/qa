<?php
class Requisito extends Conectar
{
    // ============================================================
    // LISTAR TODOS LOS REQUISITOS ACTIVOS
    // ============================================================
    public function get_requisitos()
    {
        $conectar = parent::conexion();
        parent::set_names();

        $sql = "SELECT 
                    id_requisito,
                    codigo,
                    nombre,
                    version,
                    descripcion,
                    CASE 
                        WHEN estado = 1 THEN 'Activo'
                        ELSE 'Inactivo'
                    END AS estado,
                    DATE_FORMAT(fecha_creacion, '%Y-%m-%d %H:%i') AS fecha_creacion
                FROM requisito
                WHERE estado = 1
                ORDER BY id_requisito DESC";

        $stmt = $conectar->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // ============================================================
    // OBTENER REQUISITO POR ID
    // ============================================================
    public function get_requisito_por_id($id_requisito)
    {
        $conectar = parent::conexion();
        parent::set_names();

        $sql = "SELECT 
                    id_requisito,
                    codigo,
                    nombre,
                    version,
                    descripcion,
                    estado
                FROM requisito
                WHERE id_requisito = ?";

        $stmt = $conectar->prepare($sql);
        $stmt->execute([$id_requisito]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // ============================================================
    // INSERTAR NUEVO REQUISITO
    // ============================================================
    public function insertar_requisito($codigo, $nombre, $version, $descripcion)
    {
        $conectar = parent::conexion();
        parent::set_names();

        $sql = "INSERT INTO requisito
                (codigo, nombre, version, descripcion, estado, creado_por, fecha_creacion)
                VALUES (?, ?, ?, ?, 1, ?, NOW())";

        $stmt = $conectar->prepare($sql);
        $creado_por = isset($_SESSION["usu_nombre"]) ? $_SESSION["usu_nombre"] : 'admin';

        try {
            return $stmt->execute([$codigo, $nombre, $version, $descripcion, $creado_por]);
        } catch (PDOException $e) {
            error_log("Error al insertar requisito: " . $e->getMessage());
            return false;
        }
    }

    // ============================================================
    // EDITAR REQUISITO EXISTENTE
    // ============================================================
    public function editar_requisito($id_requisito, $codigo, $nombre, $version, $descripcion)
    {
        $conectar = parent::conexion();
        parent::set_names();

        $sql = "UPDATE requisito
                SET 
                    codigo = ?,
                    nombre = ?,
                    version = ?,
                    descripcion = ?,
                    actualizado_por = ?,
                    fecha_actualizacion = NOW()
                WHERE id_requisito = ?";

        $stmt = $conectar->prepare($sql);
        $actualizado_por = isset($_SESSION["usu_nombre"]) ? $_SESSION["usu_nombre"] : 'admin';

        try {
            return $stmt->execute([$codigo, $nombre, $version, $descripcion, $actualizado_por, $id_requisito]);
        } catch (PDOException $e) {
            error_log("Error al editar requisito: " . $e->getMessage());
            return false;
        }
    }

    // ============================================================
    // CAMBIAR ESTADO (ACTIVAR / DESACTIVAR)
    // ============================================================
    public function cambiar_estado($id_requisito, $estado)
    {
        $conectar = parent::conexion();
        parent::set_names();

        $sql = "UPDATE requisito 
                SET estado = ?, actualizado_por = ?, fecha_actualizacion = NOW()
                WHERE id_requisito = ?";

        $stmt = $conectar->prepare($sql);
        $actualizado_por = isset($_SESSION["usu_nombre"]) ? $_SESSION["usu_nombre"] : 'admin';

        try {
            return $stmt->execute([$estado, $actualizado_por, $id_requisito]);
        } catch (PDOException $e) {
            error_log("Error al cambiar estado requisito: " . $e->getMessage());
            return false;
        }
    }

    // ============================================================
    // OBTENER TOTAL DE REQUISITOS ACTIVOS
    // ============================================================
    public function get_total_requisitos()
    {
        $conectar = parent::conexion();
        parent::set_names();

        $sql = "SELECT COUNT(*) AS total FROM requisito WHERE estado = 1";
        $stmt = $conectar->prepare($sql);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
?>
