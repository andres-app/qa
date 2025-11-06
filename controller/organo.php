<?php
require_once("../config/conexion.php");
require_once("../models/Organo.php");

$organo = new Organo();

switch ($_GET["op"]) {

    // ============================================================
    // COMBO JSON DE ÓRGANOS
    // ============================================================
    case "combo_organo_json":
        header('Content-Type: application/json; charset=utf-8');
        $datos = $organo->get_organos_combo();
        echo json_encode($datos, JSON_UNESCAPED_UNICODE);
        break;

    default:
        echo json_encode(["error" => "Operación no válida"]);
        break;
}
?>
