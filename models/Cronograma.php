<?php
require_once("../config/conexion.php");

class Cronograma extends Conectar {

    /* ============================
       LISTAR CRONOGRAMAS
    ============================ */
    public function listar_cronogramas() {
        $con = parent::conexion();
        $sql = "SELECT * FROM cronograma ORDER BY fecha_inicio ASC";
        $query = $con->prepare($sql);
        $query->execute();
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    /* ============================
       LISTAR ACTIVIDADES DE 1 CRONOGRAMA
    ============================ */
    public function listar_actividades($idcronograma) {
        $con = parent::conexion();
        $sql = "SELECT * FROM cronograma_actividad 
                WHERE idcronograma = ?
                ORDER BY fecha_inicio_prev ASC";
        $query = $con->prepare($sql);
        $query->execute([$idcronograma]);
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    /* ============================
       GUARDAR ACTIVIDAD
    ============================ */
    public function guardar_actividad($data) {
        $con = parent::conexion();
        $sql = "INSERT INTO cronograma_actividad
                (idcronograma, idpadre, nombre, responsable, fecha_inicio_prev,
                 fecha_fin_prev, color, nivel)
                VALUES (?,?,?,?,?,?,?,?)";

        $con->prepare($sql)->execute([
            $data["idcronograma"],
            $data["idpadre"],
            $data["nombre"],
            $data["responsable"],
            $data["fecha_inicio_prev"],
            $data["fecha_fin_prev"],
            $data["color"],
            $data["nivel"]
        ]);
    }

    /* ============================
       GUARDAR REPROGRAMACIÓN
    ============================ */
    public function guardar_historial($data) {
        $con = parent::conexion();
        $sql = "INSERT INTO cronograma_actividad_historial
                (idactividad, fecha_inicio_prev, fecha_fin_prev, 
                 fecha_inicio_reprogram, fecha_fin_reprogram, motivo)
                VALUES (?,?,?,?,?,?)";

        $con->prepare($sql)->execute([
            $data["idactividad"],
            $data["fecha_inicio_prev"],
            $data["fecha_fin_prev"],
            $data["fecha_inicio_reprogram"],
            $data["fecha_fin_reprogram"],
            $data["motivo"]
        ]);
    }
}
