<?php
require_once("../config/conexion.php");
require_once("../models/Especialidad.php");

$especialidad = new Especialidad();

switch ($_GET["op"]) {

    case "combo_especialidad_json":
        $datos = $especialidad->get_especialidades_activas();
        echo json_encode($datos);
        break;
}
?>
