<?php
require_once("../config/conexion.php");
require_once("../models/Mantenimiento.php");

// Crear una instancia del modelo Mantenimiento
$mantenimiento = new Mantenimiento();

// Evaluar el valor del parámetro "op" para determinar qué operación realizar
switch ($_GET["op"]) {

    // Caso para listar todos los mantenimientos
    case "listar":
        // Obtener los datos de los mantenimientos desde el modelo
        $datos = $mantenimiento->get_mantenimientos();

        // Verificar si hubo algún error al obtener los datos
        if ($datos === false) {
            echo json_encode(["error" => "Error al obtener los mantenimientos"]);
            exit;
        }

        // Array para almacenar los datos formateados
        $data = array();

        // Recorrer los datos obtenidos y formatearlos para el DataTable
        foreach ($datos as $row) {
            $sub_array = array();
            $sub_array[] = $row["id"];
            $sub_array[] = $row["fecha_mantenimiento"];
            $sub_array[] = $row["placa"];  // Cambiado a 'placa'
            $sub_array[] = $row["kilometraje_actual"];
            $sub_array[] = $row["precio"];
            $sub_array[] = $row["fecha_proximo_mantenimiento"];
            $sub_array[] = $row["realizado"] ? "Realizado" : "Pendiente";

            // Botones de acción (editar y eliminar) para cada fila
            $sub_array[] = '
    <button type="button" class="btn btn-soft-info waves-effect waves-light btn-sm" onClick="previsualizar(' . $row["id"] . ')">
        <i class="bx bx-show-alt font-size-16 align-middle"></i>
    </button>
    <button type="button" class="btn btn-soft-warning waves-effect waves-light btn-sm" onClick="editar(' . $row["id"] . ')">
        <i class="bx bx-edit-alt font-size-16 align-middle"></i>
    </button>
    <button type="button" class="btn btn-soft-danger waves-effect waves-light btn-sm" onClick="eliminar(' . $row["id"] . ')">
        <i class="bx bx-trash-alt font-size-16 align-middle"></i>
    </button>';

            // Agregar la fila formateada al array de datos
            $data[] = $sub_array;
        }


        // Preparar los resultados en el formato esperado por el DataTable
        $results = array(
            "sEcho" => 1,
            "iTotalRecords" => count($data),
            "iTotalDisplayRecords" => count($data),
            "aaData" => $data
        );

        // Enviar los resultados como JSON
        header('Content-Type: application/json');
        echo json_encode($results);
        break;

    // Caso para insertar un nuevo mantenimiento
    case "insertar":
        // Capturar los datos enviados desde el formulario
        $vehiculo_id = $_POST["vehiculo_id"];
        $fecha_mantenimiento = $_POST["fecha_mantenimiento"];
        $km_proximo_mantenimiento = $_POST["km_proximo_mantenimiento"];
        $kilometraje_actual = $_POST["kilometraje_actual"];
        $precio = $_POST["precio"];
        $detalle = $_POST["detalle"];
        $observaciones = $_POST["observaciones"];

        // Insertar el nuevo mantenimiento en la base de datos usando el modelo
        if ($mantenimiento->insertar_mantenimiento($vehiculo_id, $fecha_mantenimiento, $km_proximo_mantenimiento, $kilometraje_actual, $precio, $detalle, $observaciones)) {
            echo json_encode(["success" => "Mantenimiento registrado correctamente."]);
        } else {
            echo json_encode(["error" => "Error al registrar el mantenimiento."]);
        }
        break;

    // Caso para editar un mantenimiento existente
    case "editar":
        // Capturar los datos enviados por el formulario
        $id = $_POST["id"];
        $vehiculo_id = $_POST["vehiculo_id"];
        $fecha_mantenimiento = $_POST["fecha_mantenimiento"];
        $km_proximo_mantenimiento = $_POST["km_proximo_mantenimiento"];
        $kilometraje_actual = $_POST["kilometraje_actual"];
        $precio = $_POST["precio"];
        $detalle = $_POST["detalle"];
        $observaciones = $_POST["observaciones"];
        $realizado = $_POST["realizado"];

        // Llamar al método actualizar_mantenimiento del modelo
        if ($mantenimiento->actualizar_mantenimiento($id, $vehiculo_id, $fecha_mantenimiento, $km_proximo_mantenimiento, $kilometraje_actual, $precio, $detalle, $observaciones, $realizado)) {
            echo json_encode(["success" => "Mantenimiento actualizado correctamente."]);
        } else {
            echo json_encode(["error" => "Error al actualizar el mantenimiento."]);
        }
        break;

    // Caso para mostrar un mantenimiento específico
    case "mostrar":
        if (isset($_POST["id"])) {
            $datos = $mantenimiento->get_mantenimiento_por_id($_POST["id"]);
            if ($datos) {
                echo json_encode($datos);
            } else {
                echo json_encode(["error" => "No se encontraron datos para el ID del mantenimiento."]);
            }
        } else {
            echo json_encode(["error" => "No se proporcionó un ID de mantenimiento válido."]);
        }
        break;

    // Caso para eliminar un mantenimiento (marcar como inactivo o eliminarlo físicamente)
    case "eliminar":
        if (isset($_POST["id"])) {
            $id = $_POST["id"];
            if ($mantenimiento->eliminar_mantenimiento($id)) {
                echo json_encode(["success" => "Mantenimiento eliminado correctamente."]);
            } else {
                echo json_encode(["error" => "Error al eliminar el mantenimiento."]);
            }
        } else {
            echo json_encode(["error" => "No se proporcionó un ID de mantenimiento válido."]);
        }
        break;

    default:
        echo json_encode(["error" => "Operación no válida."]);
        break;
}
?>