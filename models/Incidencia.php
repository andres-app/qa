<?php
class Incidencia extends Conectar {

    public function listar() {
        $conectar = parent::conexion();
        parent::set_names();

        $sql = "SELECT i.*, u.usu_nomape AS analista
                FROM incidencia i
                LEFT JOIN tm_usuario u ON i.analista_id = u.usu_id
                ORDER BY i.id_incidencia DESC";
        $stmt = $conectar->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function insertar($data) {
        $conectar = parent::conexion();
        parent::set_names();

        $sql = "INSERT INTO incidencia
                (numero_incidencia, actividad, documentacion, descripcion, accion_recomendada,
                 fecha_recepcion, fecha_registro, fecha_respuesta, prioridad,
                 analista_id, tipo_incidencia, base_datos, version_origen, modulo, estado_incidencia)
                VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";

        $stmt = $conectar->prepare($sql);
        $stmt->execute([
            $data["numero_incidencia"],
            $data["actividad"],
            $data["documentacion"],
            $data["descripcion"],
            $data["accion_recomendada"],
            $data["fecha_recepcion"],
            $data["fecha_registro"],
            $data["fecha_respuesta"],
            $data["prioridad"],
            $data["analista_id"],
            $data["tipo_incidencia"],
            $data["base_datos"],
            $data["version_origen"],
            $data["modulo"],
            $data["estado_incidencia"]
        ]);
    }

    public function mostrar($id) {
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

    public function actualizar_estado($id, $estado) {
        $conectar = parent::conexion();
        parent::set_names();

        $sql = "UPDATE incidencia SET estado_incidencia = ? WHERE id_incidencia = ?";
        $stmt = $conectar->prepare($sql);
        $stmt->execute([$estado, $id]);
    }
}
?>
