<?php
require_once("../config/conexion.php");
require_once("../models/Actividad.php");

$actividad = new Actividad();
header('Content-Type: application/json; charset=utf-8');

switch ($_GET["op"]) {

    case "listar":
        echo json_encode(["aaData" => $actividad->listar()]);
        break;

    case "guardar":
        $actividad->insertar($_POST);
        echo json_encode(["status" => "ok", "msg" => "Actividad registrada correctamente"]);
        break;

    case "mostrar":
        echo json_encode($actividad->mostrar($_POST["id_actividad"]));
        break;

    case "actualizar_estado":
        $actividad->actualizar_estado($_POST["id_actividad"], $_POST["estado"], $_POST["avance"]);
        echo json_encode(["status" => "ok"]);
        break;

    case "correlativo":
        $data = $actividad->get_correlativo();
        echo json_encode(["id" => $data]);
        break;

    default:
        echo json_encode(["error" => "Operación no válida"]);
        break;
}
?>
