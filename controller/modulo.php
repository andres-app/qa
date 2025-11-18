<?php
require_once("../config/conexion.php");
require_once("../models/Modulo.php");

$mod = new Modulo();

switch ($_GET["op"]) {

    case "listar":
        $datos = $mod->listar();
        echo json_encode(["aaData" => $datos]);
        break;

    case "mostrar":
        $datos = $mod->mostrar($_POST["id_modulo"]);
        echo json_encode($datos);
        break;

    case "guardar":
        if (empty($_POST["id_modulo"])) {
            $mod->insertar($_POST["nombre"]);
            echo json_encode(["status" => "ok", "msg" => "Módulo registrado"]);
        } else {
            $mod->editar($_POST["id_modulo"], $_POST["nombre"]);
            echo json_encode(["status" => "ok", "msg" => "Módulo actualizado"]);
        }
        break;

    case "eliminar":
        $mod->eliminar($_POST["id_modulo"]);
        echo json_encode(["status" => "ok"]);
        break;

    case "combo":
        echo json_encode($mod->listar_activos());
        break;
}
