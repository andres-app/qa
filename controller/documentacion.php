<?php
require_once("../config/conexion.php");
require_once("../models/Documentacion.php");

$documentacion = new Documentacion();

header('Content-Type: application/json; charset=utf-8');

switch ($_GET["op"]) {

    // 🟩 Listar registros para DataTable
    case "listar":
        echo json_encode(["aaData" => $documentacion->listar()]);
        break;

    // 🟦 Registrar o actualizar
    case "guardar":
        if (empty($_POST["id_documentacion"])) {
            $documentacion->insertar(
                $_POST["nombre"],
                $_POST["descripcion"],
                $_POST["fecha_recepcion"],
                $_POST["tipo_documento"]
            );
            echo json_encode(["status" => "ok", "msg" => "Documentación registrada correctamente"]);
        } else {
            $documentacion->actualizar(
                $_POST["id_documentacion"],
                $_POST["nombre"],
                $_POST["descripcion"],
                $_POST["fecha_recepcion"],
                $_POST["tipo_documento"]
            );
            echo json_encode(["status" => "ok", "msg" => "Documentación actualizada correctamente"]);
        }
        break;

    // 🟨 Mostrar un registro específico
    case "mostrar":
        echo json_encode($documentacion->mostrar($_POST["id_documentacion"]));
        break;

    // 🟧 Eliminar (lógico)
    case "eliminar":
        $documentacion->eliminar($_POST["id_documentacion"]);
        echo json_encode(["status" => "ok", "msg" => "Documento eliminado correctamente"]);
        break;

    // 🟪 Combo para otros módulos (ej: incidencias)
    case "combo":
        echo json_encode($documentacion->combo());
        break;

    default:
        echo json_encode(["error" => "Operación no reconocida"]);
        break;
}
?>
