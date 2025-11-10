<?php
require_once("../config/conexion.php");
require_once("../models/Reporte.php");

$reporte = new Reporte();

switch ($_GET["op"]) {

    // ==========================================================
    //  COMBO DE ESPECIALIDADES ACTIVAS
    // ==========================================================
    case "combo_especialidad":
        $datos = $reporte->get_especialidades_activas();
        echo json_encode($datos);
        break;

    // ==========================================================
    //  LISTAR CASOS DE PRUEBA POR ESPECIALIDAD
    // ==========================================================
    case "listar":
        $id_especialidad = isset($_POST["id_especialidad"]) ? $_POST["id_especialidad"] : "";
        $estado = isset($_POST["estado"]) ? $_POST["estado"] : "";

        $datos = $reporte->get_casos_por_especialidad($id_especialidad, $estado);
        echo json_encode($datos);
        break;
}
?>
