<?php
require_once("../config/conexion.php");
require_once("../models/Incidencia.php");

$incidencia = new Incidencia();

switch ($_GET["op"]) {

    case "listar":
        $datos = $incidencia->listar();
        echo json_encode(["aaData" => $datos]);
        break;

    case "guardar":
        $incidencia->insertar($_POST);
        echo json_encode(["status" => "ok", "msg" => "Incidencia registrada correctamente"]);
        break;

    case "mostrar":
        $row = $incidencia->mostrar($_POST["id_incidencia"]);
        echo json_encode($row);
        break;

    case "actualizar_estado":
        $incidencia->actualizar_estado($_POST["id_incidencia"], $_POST["estado_incidencia"]);
        echo json_encode(["status" => "ok"]);
        break;
}
?>
