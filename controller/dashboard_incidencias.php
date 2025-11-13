<?php
require_once("../config/conexion.php");
require_once("../models/Incidencia.php");

$inc = new Incidencia();

header('Content-Type: application/json');

switch ($_GET["op"]) {

    case "por_documento":
        echo json_encode($inc->incidencias_por_documento());
        break;

    case "por_modulo":
        echo json_encode($inc->incidencias_por_modulo());
        break;

    case "por_mes":
        echo json_encode($inc->incidencias_por_mes());
        break;

    case "total":
        echo json_encode(["total" => $inc->contar_todas()]);
        break;
}
