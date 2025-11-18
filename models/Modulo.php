<?php
class Modulo extends Conectar {

    public function listar() {
        $conectar = parent::conexion();
        parent::set_names();

        $sql = "SELECT * FROM modulos WHERE estado = 1 ORDER BY nombre ASC";
        $stmt = $conectar->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function listar_activos() {
        return $this->listar();
    }

    public function mostrar($id_modulo) {
        $conectar = parent::conexion();
        parent::set_names();

        $sql = "SELECT * FROM modulos WHERE id_modulo = ?";
        $stmt = $conectar->prepare($sql);
        $stmt->execute([$id_modulo]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function insertar($nombre) {
        $conectar = parent::conexion();
        parent::set_names();

        $sql = "INSERT INTO modulos (nombre, estado) VALUES (?, 1)";
        $stmt = $conectar->prepare($sql);
        $stmt->execute([$nombre]);
    }

    public function editar($id_modulo, $nombre) {
        $conectar = parent::conexion();
        parent::set_names();

        $sql = "UPDATE modulos SET nombre = ? WHERE id_modulo = ?";
        $stmt = $conectar->prepare($sql);
        $stmt->execute([$nombre, $id_modulo]);
    }

    public function eliminar($id_modulo) {
        $conectar = parent::conexion();
        parent::set_names();

        $sql = "UPDATE modulos SET estado = 0 WHERE id_modulo = ?";
        $stmt = $conectar->prepare($sql);
        $stmt->execute([$id_modulo]);
    }
}
