<?php
require_once("../config/conexion.php");
require_once("../models/Incidencia.php");
require_once("../models/Documentacion.php");

$incidencia = new Incidencia();
$doc = new Documentacion();

header('Content-Type: application/json; charset=utf-8');

switch ($_GET["op"]) {

    case "listar":
        echo json_encode(["aaData" => $incidencia->listar()]);
        break;


    case "guardar":
        $nuevo_id = $incidencia->insertar($_POST);
        echo json_encode([
            "status" => "ok",
            "msg" => "Incidencia registrada correctamente",
            "id_incidencia" => $nuevo_id
        ]);
        break;

    case "editar":
        $incidencia->actualizar($_POST);
        echo json_encode(["status" => "ok", "msg" => "Incidencia actualizada correctamente"]);
        break;



    case "mostrar":
        echo json_encode($incidencia->mostrar($_POST["id_incidencia"]));
        break;

    case "actualizar_estado":
        $incidencia->actualizar_estado($_POST["id_incidencia"], $_POST["estado_incidencia"]);
        echo json_encode(["status" => "ok"]);
        break;

    case "correlativo":
        header('Content-Type: application/json; charset=utf-8');
        $data = $incidencia->get_correlativo();
        echo json_encode(["id_incidencia" => $data], JSON_UNESCAPED_UNICODE);
        break;

    case "combo_documentacion":
        echo json_encode($doc->listar());
        break;

    default:
        echo json_encode(["status" => "error", "msg" => "Operación no válida"]);
        break;
}
?>