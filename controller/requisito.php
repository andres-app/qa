<?php
require_once("../config/conexion.php");
require_once("../models/Requisito.php");

$requisito = new Requisito();

switch ($_GET["op"]) {

    // 🧾 LISTAR TODOS LOS REQUISITOS
    case "listar":
        $datos = $requisito->get_requisitos();

        if ($datos === false) {
            echo json_encode(["error" => "Error al obtener los requisitos"]);
            exit;
        }

        $data = array();

        foreach ($datos as $row) {
            $sub_array = array();
            $sub_array[] = $row["id_requisito"]; 
            $sub_array[] = $row["codigo"];
            $sub_array[] = $row["nombre"];
            $sub_array[] = $row["version"];
            $sub_array[] = '
                <button type="button" class="btn btn-soft-warning btn-sm" onClick="editar(' . $row["id_requisito"] . ')">
                    <i class="bx bx-edit-alt font-size-16 align-middle"></i>
                </button>
                <button type="button" class="btn btn-soft-danger btn-sm" onClick="eliminar(' . $row["id_requisito"] . ')">
                    <i class="bx bx-trash-alt font-size-16 align-middle"></i>
                </button>
            ';
            $data[] = $sub_array;
        }

        $results = array(
            "sEcho" => 1,
            "iTotalRecords" => count($data),
            "iTotalDisplayRecords" => count($data),
            "aaData" => $data
        );

        header('Content-Type: application/json');
        echo json_encode($results);
        break;

    // 💾 GUARDAR O EDITAR
    case "guardar":
        $id = isset($_POST["id_requisito"]) ? intval($_POST["id_requisito"]) : 0;
        $codigo = trim($_POST["codigo"]);
        $nombre = trim($_POST["nombre"]);
        $version = trim($_POST["version"]);
        $descripcion = trim($_POST["descripcion"]);

        if ($id > 0) {
            $ok = $requisito->editar_requisito($id, $codigo, $nombre, $version, $descripcion);
            echo json_encode(["success" => $ok ? "Requisito actualizado correctamente." : "Error al actualizar el requisito."]);
        } else {
            $ok = $requisito->insertar_requisito($codigo, $nombre, $version, $descripcion);
            echo json_encode(["success" => $ok ? "Requisito registrado correctamente." : "Error al registrar el requisito."]);
        }
        break;

    // 🔍 MOSTRAR
    case "mostrar":
        if (isset($_POST["id"])) {
            $datos = $requisito->get_requisito_por_id($_POST["id"]);
            echo json_encode($datos);
        } else {
            echo json_encode(["error" => "ID no proporcionado."]);
        }
        break;

    // ❌ ELIMINAR (Cambio de estado lógico)
    case "eliminar":
        if (isset($_POST["id"])) {
            $ok = $requisito->cambiar_estado($_POST["id"], 0);
            echo json_encode(["success" => $ok ? "Requisito eliminado correctamente." : "Error al eliminar el requisito."]);
        } else {
            echo json_encode(["error" => "ID no proporcionado."]);
        }
        break;

    default:
        echo json_encode(["error" => "Operación no válida"]);
        break;
}
?>
