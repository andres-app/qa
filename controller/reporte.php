<?php
require_once("../config/conexion.php");
require_once("../models/Reporte.php");

$reporte = new Reporte();

switch ($_GET["op"]) {

    case "reporte_trazabilidad":
        $datos = $reporte->reporte_trazabilidad();
        break;

    case "reporte_cobertura":
        $datos = $reporte->reporte_cobertura();
        break;

    case "reporte_casos_por_requerimiento":
        $datos = $reporte->reporte_casos_por_requerimiento();
        break;

    default:
        echo json_encode(["error" => "Operación no válida."]);
        exit;
}

$results = array(
    "sEcho" => 1,
    "iTotalRecords" => count($datos),
    "iTotalDisplayRecords" => count($datos),
    "aaData" => $datos
);

echo json_encode($results);
?>
