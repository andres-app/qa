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
            $sub_array[] = $row["id_caso"];            // 0
            $sub_array[] = $row["codigo"];             // 1
            $sub_array[] = $row["nombre"];             // 2
            $sub_array[] = $row["requerimiento"];      // 3
            $sub_array[] = $row["tipo_prueba"];        // 4
            $sub_array[] = $row["estado_ejecucion"];   // 5
            $sub_array[] = $row["version"];            // 6
            $sub_array[] = $row["fecha_creacion"];     // 7

            // 🔒 Condición: si está completado, bloquear clic
            $estado = strtolower(trim($row["estado_ejecucion"])); // 🔍 asegura comparación exacta

            if ($estado === "completado") {
                // Caso completado → botones visibles pero bloqueados
                $acciones = '
                    <button type="button" class="btn btn-soft-warning btn-sm"
                        style="opacity:0.6; cursor:not-allowed;"
                        title="No se puede editar un caso completado"
                        onclick="Swal.fire({
                            icon: \'warning\',
                            title: \'Acción no permitida\',
                            text: \'Este caso ya está completado y no puede ser editado.\'
                        })">
                        <i class="bx bx-edit-alt font-size-16 align-middle"></i>
                    </button>
                    <button type="button" class="btn btn-soft-danger btn-sm"
                        style="opacity:0.6; cursor:not-allowed;"
                        title="No se puede eliminar un caso completado"
                        onclick="Swal.fire({
                            icon: \'warning\',
                            title: \'Acción no permitida\',
                            text: \'Este caso ya está completado y no puede ser eliminado.\'
                        })">
                        <i class="bx bx-trash-alt font-size-16 align-middle"></i>
                    </button>
                ';
            } else {
                // Caso pendiente o en ejecución → botones activos
                $acciones = '
                    <button type="button" class="btn btn-soft-warning btn-sm"
                        title="Editar caso de prueba"
                        onclick="editar(' . $row["id_caso"] . ')">
                        <i class="bx bx-edit-alt font-size-16 align-middle"></i>
                    </button>
                    <button type="button" class="btn btn-soft-danger btn-sm"
                        title="Eliminar caso de prueba"
                        onclick="eliminar(' . $row["id_caso"] . ')">
                        <i class="bx bx-trash-alt font-size-16 align-middle"></i>
                    </button>
                ';
            }
            
            $sub_array[] = $acciones;

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

        $id = intval($_POST["id_caso_prueba"] ?? 0);
        $codigo = trim($_POST["codigo"] ?? '');
        $nombre = trim($_POST["nombre"] ?? '');
        $tipo_prueba = trim($_POST["tipo_prueba"] ?? '');
        $version = trim($_POST["version"] ?? '');
        $elaborado_por = trim($_POST["elaborado_por"] ?? '');
        $descripcion = trim($_POST["descripcion"] ?? '');
        $id_requerimiento = intval($_POST["id_requerimiento"] ?? 0);
        $estado_ejecucion = "Pendiente";
        $fecha_ejecucion = date('Y-m-d');

        if ($id > 0) {
            $ok = $caso->editar_caso(
                $id,
                $codigo,
                $nombre,
                $tipo_prueba,
                $version,
                $elaborado_por,
                $descripcion,
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
                $descripcion,
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
            exit;
        }

        // Obtener las especialidades y órganos asociados al requerimiento
        $especialidades = $caso->get_especialidades_por_requerimiento($datos["id_requerimiento"]);
        $organos = $caso->get_organos_por_requerimiento($datos["id_requerimiento"]);

        // Agregar a la respuesta los nombres concatenados
        $datos["especialidades_asociadas"] = !empty($especialidades)
            ? implode(", ", array_column($especialidades, "nombre"))
            : "No asociadas";

        $datos["organos_asociados"] = !empty($organos)
            ? implode(", ", array_column($organos, "organo_jurisdiccional"))
            : "No asociados";

        echo json_encode($datos);
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