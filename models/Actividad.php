<?php
class Actividad extends Conectar {

    public function listar() {
        $conectar = parent::conexion();
        parent::set_names();

        $sql = "SELECT a.*, u.usu_nomape AS colaborador
                FROM actividad a
                LEFT JOIN tm_usuario u ON a.colaborador_id = u.usu_id
                ORDER BY a.id_actividad DESC";
        $stmt = $conectar->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function insertar($data) {
        $conectar = parent::conexion();
        parent::set_names();

        $sql = "INSERT INTO actividad 
                (colaborador_id, actividad, descripcion, fecha_recepcion, fecha_inicio, 
                 fecha_respuesta, estado, avance, prioridad)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conectar->prepare($sql);
        $stmt->execute([
            $data["colaborador_id"],
            $data["actividad"],
            $data["descripcion"],
            $data["fecha_recepcion"],
            $data["fecha_inicio"],
            $data["fecha_respuesta"],
            $data["estado"],
            $data["avance"],
            $data["prioridad"]
        ]);
    }

    public function mostrar($id_actividad) {
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

    public function actualizar_estado($id_actividad, $estado, $avance) {
        $conectar = parent::conexion();
        parent::set_names();

        $sql = "UPDATE actividad 
                SET estado = ?, avance = ?
                WHERE id_actividad = ?";
        $stmt = $conectar->prepare($sql);
        $stmt->execute([$estado, $avance, $id_actividad]);
    }

    public function get_correlativo() {
        $conectar = parent::conexion();
        parent::set_names();

        $sql = "SELECT IFNULL(MAX(id_actividad), 0) + 1 AS id FROM actividad";
        $stmt = $conectar->prepare($sql);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return (int)$row["id"];
    }
}
?>
