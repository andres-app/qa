<?php
require_once("../config/conexion.php");
require_once("../models/Incidencia.php");
require_once("../models/Documentacion.php");

$incidencia = new Incidencia();
$doc = new Documentacion();

header('Content-Type: application/json; charset=utf-8');

switch ($_GET["op"]) {

    // ============================================================
    // LISTAR
    // ============================================================
    case "listar":

        $datos = $incidencia->listar();
    
        echo json_encode([
            "data" => $datos,
            "recordsTotal" => count($datos),
            "recordsFiltered" => count($datos)
        ]);
    
        break;
    

    // ============================================================
    // GUARDAR
    // ============================================================
    case "guardar":
        $nuevo_id = $incidencia->insertar($_POST);
        echo json_encode([
            "status" => "ok",
            "msg" => "Incidencia registrada correctamente",
            "id_incidencia" => $nuevo_id
        ]);
        break;

    // ============================================================
    // EDITAR
    // ============================================================
    case "editar":

        $data = [
            "id_incidencia" => $_POST["id_incidencia"],
            "descripcion" => $_POST["descripcion"],
            "accion_recomendada" => $_POST["accion_recomendada"],
            "tipo_incidencia" => $_POST["tipo_incidencia"],
            "prioridad" => $_POST["prioridad"],
            "base_datos" => $_POST["base_datos"],
            "version_origen" => $_POST["version_origen"],
            "id_modulo" => $_POST["id_modulo"],
            "estado_incidencia" => $_POST["estado_incidencia"]  // ✔ correcto
        ];

        $incidencia->actualizar($data);

        echo json_encode(["status" => "ok", "msg" => "Incidencia actualizada correctamente"]);
        break;


    // ============================================================
    // MOSTRAR
    // ============================================================
    case "mostrar":
        echo json_encode($incidencia->mostrar($_POST["id_incidencia"]));
        break;

    // ============================================================
    // ACTUALIZAR ESTADO
    // ============================================================
    case "actualizar_estado":
        $incidencia->actualizar_estado($_POST["id_incidencia"], $_POST["estado"]);
        echo json_encode(["status" => "ok"]);
        break;

    // ============================================================
    // CORRELATIVO
    // ============================================================
    case "correlativo":
        $data = $incidencia->get_correlativo();
        echo json_encode(["id_incidencia" => $data], JSON_UNESCAPED_UNICODE);
        break;

    // ============================================================
    // COMBO DOCUMENTACIÓN
    // ============================================================
    case "combo_documentacion":
        echo json_encode($doc->listar());
        break;

    // ============================================================
    // ANULAR (NO ELIMINAR)
    // ============================================================
    case "eliminar":

        $id = $_POST["id_incidencia"];

        $result = $incidencia->anular($id);

        if ($result) {
            echo json_encode(["success" => "Incidencia anulada correctamente"]);
        } else {
            echo json_encode(["error" => "No se pudo anular la incidencia"]);
        }
        break;

    case "correlativo_doc":
        $id_documentacion = $_POST["id_documentacion"];
        $nro = $incidencia->generar_correlativo_doc($id_documentacion);
        echo json_encode(["correlativo" => $nro]);
        break;




    // ============================================================
    // DEFAULT
    // ============================================================
    default:
        echo json_encode(["status" => "error", "msg" => "Operación no válida"]);
        break;
}
?>