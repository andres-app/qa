<?php
class Proceso extends Conectar
{
    public function get_procesos()
    {
        $conectar = parent::conexion();
        parent::set_names();

        // Consulta para seleccionar los campos correctos según tu tabla
        $sql = "SELECT id, nombre, sinad, direc, grupo, obtencion, estado_actual, fecha_inicio FROM procesos";
        $stmt = $conectar->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);  // Asegúrate de que se estén devolviendo los datos correctamente
    }

    
    public function insert_proceso($proceso_nombre, $proceso_descripcion)
    {
        $conectar = parent::conexion();
        parent::set_names();
        $sql = "INSERT INTO tm_proceso (proceso_nombre, proceso_descripcion) VALUES (?, ?)";
        $sql = $conectar->prepare($sql);
        $sql->bindValue(1, $proceso_nombre);
        $sql->bindValue(2, $proceso_descripcion);
        $sql->execute();
    }

    public function update_proceso($proceso_id, $proceso_nombre, $proceso_descripcion)
    {
        $conectar = parent::conexion();
        parent::set_names();
        $sql = "UPDATE tm_proceso SET proceso_nombre = ?, proceso_descripcion = ?, fech_modi = NOW() WHERE proceso_id = ?";
        $sql = $conectar->prepare($sql);
        $sql->bindValue(1, $proceso_nombre);
        $sql->bindValue(2, $proceso_descripcion);
        $sql->bindValue(3, $proceso_id);
        $sql->execute();
    }

    public function get_proceso_x_id($proceso_id)
    {
        $conectar = parent::conexion();
        parent::set_names();
        $sql = "SELECT * FROM tm_proceso WHERE proceso_id = ?";
        $sql = $conectar->prepare($sql);
        $sql->bindValue(1, $proceso_id);
        $sql->execute();
        return $sql->fetchAll();
    }

    public function eliminar_proceso($proceso_id)
    {
        $conectar = parent::conexion();
        parent::set_names();
        $sql = "UPDATE tm_proceso SET est = 0, fech_elim = NOW() WHERE proceso_id = ?";
        $sql = $conectar->prepare($sql);
        $sql->bindValue(1, $proceso_id);
        $sql->execute();
    }
}
?>