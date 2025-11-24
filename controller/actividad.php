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
        $id = isset($_POST["id_actividad"]) ? intval($_POST["id_actividad"]) : 0;

        $data = [
            "id_actividad" => $id,
            "colaborador_id" => $_POST["colaborador_id"],
            "actividad" => $_POST["actividad"],
            "descripcion" => $_POST["descripcion"],
            "fecha_recepcion" => $_POST["fecha_recepcion"],
            "prioridad" => $_POST["prioridad"]
        ];

        if ($id > 0) {
            // ✅ EDITAR
            $actividad->actualizar($data);
            echo json_encode([
                "status" => "ok",
                "msg" => "Actividad actualizada correctamente"
            ]);
        } else {
            // ✅ NUEVO
            $actividad->insertar($data);
            echo json_encode([
                "status" => "ok",
                "msg" => "Actividad registrada correctamente"
            ]);
        }
        break;

    case "mostrar":
        echo json_encode($actividad->mostrar($_POST["id_actividad"]));
        break;

        case "actualizar_estado":

            file_put_contents("debug_estado.txt", print_r($_POST, true));  // 👈 LOG DEFINITIVO
        
            $estado = $_POST["estado"];
        
            $actividad->actualizar_estado(
                $_POST["id_actividad"],
                $_POST["estado"],
                $_POST["avance"],
                $_POST["fecha_inicio"] ?? null,
                $_POST["fecha_respuesta"] ?? null,
                $_POST["observacion"] ?? null
            );
        
            echo json_encode(["status" => "ok"]);
            break;
        



    case "correlativo":
        $data = $actividad->get_correlativo();
        echo json_encode(["id" => $data]);
        break;

    case "eliminar":
        $id = $_POST["id_actividad"];
        $result = $actividad->eliminar($id);

        if ($result) {
            echo json_encode([
                "status" => "ok",
                "msg" => "Actividad anulada correctamente"
            ]);
        } else {
            echo json_encode([
                "status" => "error",
                "msg" => "No se pudo anular"
            ]);
        }
        break;





    default:
        echo json_encode(["error" => "Operación no válida"]);
        break;
}
?>