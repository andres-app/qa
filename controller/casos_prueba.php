<?php
require_once("../config/conexion.php");
require_once("../models/Casos_prueba.php");

$caso = new Casos_prueba();

switch ($_GET["op"]) {

    // 🧾 LISTAR CASOS DE PRUEBA
    case "listar":
        $datos = $caso->get_casos();

        if ($datos === false) {
            echo json_encode(["error" => "Error al obtener los casos de prueba"]);
            exit;
        }

        $data = [];
        foreach ($datos as $row) {
            $sub_array = [];
            $sub_array[] = $row["id_caso"];
            $sub_array[] = $row["codigo"];
            $sub_array[] = $row["nombre"];
            $sub_array[] = $row["requerimiento"];
            $sub_array[] = $row["tipo_prueba"];
            $sub_array[] = $row["estado_ejecucion"];
            $sub_array[] = $row["version"];
            $sub_array[] = $row["fecha_creacion"];
            $sub_array[] = '
                <button type="button" class="btn btn-soft-warning btn-sm" onClick="editar(' . $row["id_caso"] . ')">
                    <i class="bx bx-edit-alt font-size-16 align-middle"></i>
                </button>
                <button type="button" class="btn btn-soft-danger btn-sm" onClick="eliminar(' . $row["id_caso"] . ')">
                    <i class="bx bx-trash-alt font-size-16 align-middle"></i>
                </button>
            ';
            $data[] = $sub_array;
        }

        echo json_encode([
            "sEcho" => 1,
            "iTotalRecords" => count($data),
            "iTotalDisplayRecords" => count($data),
            "aaData" => $data
        ]);
        break;

    // 💾 GUARDAR / EDITAR CASO
    case "guardar":
        header('Content-Type: application/json; charset=utf-8');
    
        $id = isset($_POST["id_caso_prueba"]) ? intval($_POST["id_caso_prueba"]) : 0;
        $codigo           = trim($_POST["codigo"] ?? '');
        $nombre           = trim($_POST["nombre"] ?? '');
        $tipo_prueba      = trim($_POST["tipo_prueba"] ?? '');
        $version          = trim($_POST["version"] ?? '');
        $elaborado_por    = trim($_POST["elaborado_por"] ?? '');
        $descripcion      = trim($_POST["descripcion"] ?? '');   // ✅ <--- AQUI EL FIX
        $especialidad_id  = intval($_POST["especialidad_id"] ?? 0);
        $id_requerimiento = intval($_POST["id_requerimiento"] ?? 0);
        $estado_ejecucion = "Pendiente";
        $fecha_ejecucion  = date('Y-m-d');
    
        if ($id > 0) {
            $ok = $caso->editar_caso(
                $id,
                $codigo,
                $nombre,
                $tipo_prueba,
                $version,
                $elaborado_por,
                $descripcion,          // ✅ pásalo
                $especialidad_id,
                $id_requerimiento,
                $estado_ejecucion,
                $fecha_ejecucion
            );
            echo json_encode(["success" => $ok ? "Caso de prueba actualizado correctamente." : "Error al actualizar el caso de prueba."]);
        } else {
            $ok = $caso->insertar_caso(
                $codigo,
                $nombre,
                $tipo_prueba,
                $version,
                $elaborado_por,
                $descripcion,          // ✅ pásalo
                $especialidad_id,
                $id_requerimiento,
                $estado_ejecucion,
                $fecha_ejecucion
            );
            echo json_encode(["success" => $ok ? "Caso de prueba registrado correctamente." : "Error al registrar el caso de prueba."]);
        }
        break;
    


    // 🔍 MOSTRAR
    case "mostrar":
        header('Content-Type: application/json; charset=utf-8');
    
        if (!isset($_POST["id"])) {
            echo json_encode(["error" => "ID no proporcionado."]);
            exit;
        }
    
        $datos = $caso->get_caso_por_id($_POST["id"]);
    
        if (!$datos) {
            echo json_encode(["error" => "No se encontró el caso de prueba."]);
        } else {
            echo json_encode($datos);
        }
        break;
    

    // ❌ ELIMINAR (estado lógico)
    case "eliminar":
        if (isset($_POST["id"])) {
            $ok = $caso->cambiar_estado($_POST["id"], 0);
            echo json_encode(["success" => $ok ? "Caso de prueba eliminado correctamente." : "Error al eliminar el caso de prueba."]);
        } else {
            echo json_encode(["error" => "ID no proporcionado."]);
        }
        break;

    default:
        echo json_encode(["error" => "Operación no válida"]);
        break;

    // 🔹 GENERAR CÓDIGO AUTOMÁTICO
    case "generar_codigo":
        if (isset($_POST["id_requerimiento"])) {
            $datos = $caso->generar_codigo_por_requerimiento($_POST["id_requerimiento"]);
            echo json_encode($datos);
        } else {
            echo json_encode(["error" => "ID de requerimiento no proporcionado."]);
        }
        break;

}
?>