<?php
require_once("../config/conexion.php");
require_once("../models/Modulo.php");

$modulo = new Modulo();

switch ($_GET["op"]) {

    case "combo":
        echo json_encode($modulo->listar_activos());
        break;

    default:
        echo json_encode(["error" => "Operación no válida"]);
        break;
}
