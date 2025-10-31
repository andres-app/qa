<?php
// Clase Mantenimiento que extiende de la clase Conectar, para manejar operaciones con la base de datos.
class Mantenimiento extends Conectar
{
    /**
     * Método para obtener todos los mantenimientos registrados en la base de datos.
     * 
     * @return array Listado de mantenimientos con los siguientes datos:
     *               - id: Identificador del mantenimiento.
     *               - vehiculo_id: ID del vehículo asociado.
     *               - fecha_mantenimiento: Fecha del mantenimiento.
     *               - fecha_proximo_mantenimiento: Fecha del próximo mantenimiento.
     *               - kilometraje_actual: Kilometraje actual del vehículo.
     *               - precio: Costo del mantenimiento.
     *               - detalle: Detalle del mantenimiento.
     *               - realizado: Estado del mantenimiento (0 = Pendiente, 1 = Realizado).
     *               - observaciones: Observaciones adicionales.
     */
    public function get_mantenimientos()
    {
        $conectar = parent::conexion();
        parent::set_names();
        
        // Consulta para obtener los mantenimientos junto con la placa del vehículo
        $sql = "SELECT m.id, m.fecha_mantenimiento, v.placa, m.kilometraje_actual, m.precio, 
                       m.fecha_proximo_mantenimiento, m.realizado
                FROM mantenimiento m
                JOIN vehiculos v ON m.vehiculo_id = v.id
                ORDER BY m.id DESC";
        
        $stmt = $conectar->prepare($sql);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    
    /**
     * Método para insertar un nuevo mantenimiento en la base de datos.
     * 
     * @param int $vehiculo_id ID del vehículo.
     * @param string $fecha_mantenimiento Fecha del mantenimiento.
     * @param int $kilometraje_actual Kilometraje actual del vehículo.
     * @param float $precio Costo del mantenimiento.
     * @param string $detalle Detalle del mantenimiento.
     * @param string $observaciones Observaciones adicionales.
     * 
     * @return bool True si la inserción fue exitosa, false en caso de error.
     */
    public function insertar_mantenimiento($vehiculo_id, $fecha_mantenimiento, $km_proximo_mantenimiento, $kilometraje_actual, $precio, $detalle, $observaciones)
    {
        $conectar = parent::conexion();
        parent::set_names();

        // Consulta SQL para insertar un nuevo mantenimiento en la base de datos
        $sql = "INSERT INTO mantenimiento (vehiculo_id, fecha_mantenimiento, fecha_proximo_mantenimiento, kilometraje_actual, precio, detalle, observaciones, realizado) 
                VALUES (?, ?, ?, ?, ?, ?, ?, 0)";

        $stmt = $conectar->prepare($sql);

        if ($stmt->execute([$vehiculo_id, $fecha_mantenimiento, $km_proximo_mantenimiento, $kilometraje_actual, $precio, $detalle, $observaciones])) {
            return true;
        } else {
            $error = $stmt->errorInfo();
            error_log("Error en la consulta de mantenimiento: " . $error[2]);
            return false;
        }
    }

    /**
     * Método para actualizar los datos de un mantenimiento.
     */
    public function actualizar_mantenimiento($id, $vehiculo_id, $fecha_mantenimiento, $km_proximo_mantenimiento, $kilometraje_actual, $precio, $detalle, $observaciones, $realizado)
    {
        $conectar = parent::conexion();
        parent::set_names();

        $sql = "UPDATE mantenimiento 
                SET vehiculo_id = ?, fecha_mantenimiento = ?, fecha_proximo_mantenimiento = ?, kilometraje_actual = ?, precio = ?, detalle = ?, observaciones = ?, realizado = ?
                WHERE id = ?";

        $stmt = $conectar->prepare($sql);

        try {
            $stmt->execute([$vehiculo_id, $fecha_mantenimiento, $km_proximo_mantenimiento, $kilometraje_actual, $precio, $detalle, $observaciones, $realizado, $id]);
            return true;
        } catch (PDOException $e) {
            error_log("Error en la consulta de actualización: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Método para eliminar un mantenimiento de la base de datos.
     */
    public function eliminar_mantenimiento($id)
    {
        $conectar = parent::conexion();
        parent::set_names();

        $sql = "DELETE FROM mantenimiento WHERE id = ?";
        $stmt = $conectar->prepare($sql);

        try {
            $stmt->execute([$id]);
            return true;
        } catch (PDOException $e) {
            error_log("Error al eliminar el mantenimiento: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Método para obtener los próximos mantenimientos de la tabla mantenimiento.
     */
    public function get_proximos_mantenimientos()
    {
        $conectar = parent::conexion();
        parent::set_names();
    
        $sql = "SELECT v.id, v.placa, m.fecha_proximo_mantenimiento,
                       CASE 
                           WHEN m.fecha_proximo_mantenimiento < CURDATE() THEN 'Vencido'
                           ELSE 'Próximo'
                       END AS estado_mantenimiento
                FROM vehiculos v
                JOIN mantenimiento m ON v.id = m.vehiculo_id
                WHERE v.estado = 1 
                AND m.realizado = 0
                ORDER BY m.fecha_proximo_mantenimiento ASC";
    
        $stmt = $conectar->prepare($sql);
        $stmt->execute();
    
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
