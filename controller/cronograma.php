<?php
require_once("../config/conexion.php");
require_once("../models/Cronograma.php");

$cron = new Cronograma();

switch ($_GET["op"]) {

    case "listar_cronogramas":
        $data = $cron->listar_cronogramas();
        echo json_encode($data);
        break;

    case "listar_actividades":
        $id = $_POST["idcronograma"];
        echo json_encode($cron->listar_actividades($id));
        break;

    case "guardar_actividad":
        $cron->guardar_actividad($_POST);
        echo "ok";
        break;

    case "guardar_historial":
        $cron->guardar_historial($_POST);
        echo "ok";
        break;
}
