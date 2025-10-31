<?php
class Vehiculo extends Conectar
{
    // LISTAR (usa activos)
    public function get_activos()
    {
        $conectar = parent::conexion();
        parent::set_names();

        $sql = "SELECT 
                    a.id,
                    a.serie       AS placa,         -- mapeo para el front
                    a.marca,
                    a.modelo,
                    YEAR(a.fecha_registro) AS anio,  -- mapeo para el front
                    NULL AS fecha_mantenimiento,     -- no existe en activos
                    NULL AS fecha_proximo_mantenimiento
                FROM activos a
                WHERE a.estado = 1
                ORDER BY a.id DESC";

        $stmt = $conectar->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // TOTAL (usa activos)
    public function get_total_activos()
    {
        $conectar = parent::conexion();
        $sql = "SELECT COUNT(*) AS total FROM activos WHERE estado = 1";
        $stmt = $conectar->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return (int)$result['total'];
    }

    // MOSTRAR POR ID (usa activos) con alias para el front
    public function get_vehiculo_por_id($id)
    {
        $conectar = parent::conexion();
        parent::set_names();

        $sql = "SELECT 
                    a.id,
                    a.serie AS placa,
                    a.marca,
                    a.modelo,
                    YEAR(a.fecha_registro) AS anio,
                    a.observaciones AS poliza,     -- solo para no romper el front
                    a.estado
                FROM activos a
                WHERE a.id = ?";
        $stmt = $conectar->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // INSERTAR (ajustado a columnas reales de activos)
    public function insertar_vehiculo($placa, $marca, $modelo, $anio, $color, $motor, $combustible, $tipo_vehiculo, $poliza, $estado)
    {
        $conectar = parent::conexion();
        parent::set_names();

        // Usamos: serie, marca, modelo, fecha_registro, observaciones, estado
        $sql = "INSERT INTO activos (serie, marca, modelo, fecha_registro, observaciones, estado)
                VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $conectar->prepare($sql);
        // fecha_registro: creamos una fecha con el año recibido (si viene vacío usamos NOW())
        $fecha = $anio ? ($anio . "-01-01") : date('Y-m-d');

        return $stmt->execute([$placa, $marca, $modelo, $fecha, $poliza, $estado ? $estado : 1]);
    }

    // EDITAR (ajustado a columnas reales de activos)
    public function editar_vehiculo($id, $placa, $marca, $modelo, $anio, $color, $motor, $combustible, $tipo_vehiculo, $poliza, $estado)
    {
        $conectar = parent::conexion();
        parent::set_names();

        $sql = "UPDATE activos
                SET serie = ?, marca = ?, modelo = ?, fecha_registro = ?, observaciones = ?, estado = ?
                WHERE id = ?";
        $stmt = $conectar->prepare($sql);
        $fecha = $anio ? ($anio . "-01-01") : date('Y-m-d');

        try {
            return $stmt->execute([$placa, $marca, $modelo, $fecha, $poliza, $estado, $id]);
        } catch (PDOException $e) {
            error_log("Error actualización activos: " . $e->getMessage());
            return false;
        }
    }

    // CAMBIAR ESTADO (usa activos)
    public function cambiar_estado($id, $estado)
    {
        $conectar = parent::conexion();
        parent::set_names();

        $sql = "UPDATE activos SET estado = ? WHERE id = ?";
        $stmt = $conectar->prepare($sql);
        try {
            return $stmt->execute([$estado, $id]);
        } catch (PDOException $e) {
            error_log("Error cambiar estado activo: " . $e->getMessage());
            return false;
        }
    }

    // Si aún no tienes tabla mantenimiento, deja esto devolviendo vacío para no romper llamadas
    public function get_proximos_mantenimientos()
    {
        return [];
    }
}
