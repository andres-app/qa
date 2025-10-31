<?php
require_once("../config/conexion.php");
require_once("../models/Vehiculo.php");

// Crear instancia del modelo Vehículo
$vehiculo = new Vehiculo();
$total_activos = $vehiculo->get_total_activos(); // Obtenemos el total de vehículos


// Obtener vehículos con próximos mantenimientos
$proximos_mantenimientos = $vehiculo->get_proximos_mantenimientos();


// Evaluar el valor del parámetro "op" para determinar qué operación realizar
switch ($_GET["op"]) {

    // Caso para listar todos los vehículos
    case "listar":
        // Obtener los datos de los vehículos desde el modelo
        $datos = $vehiculo->get_activos();

        // Verificar si hubo algún error al obtener los datos
        if ($datos === false) {
            echo json_encode(["error" => "Error al obtener los vehículos"]);
            exit;
        }

        // Array para almacenar los datos formateados
        $data = array();

        // Recorrer los datos obtenidos y formatearlos para el DataTable
        foreach ($datos as $row) {
            $sub_array = array();
            $sub_array[] = $row["id"]; // ID del vehículo
            $sub_array[] = $row["placa"]; // Placa del vehículo
            $sub_array[] = $row["marca"]; // Marca del vehículo
            $sub_array[] = $row["modelo"]; // Modelo del vehículo
            $sub_array[] = $row["anio"]; // Año del vehículo
            $sub_array[] = isset($row["fecha_mantenimiento"]) ? $row["fecha_mantenimiento"] : "No realizado"; // Fecha del último mantenimiento
            $sub_array[] = isset($row["fecha_proximo_mantenimiento"]) ? $row["fecha_proximo_mantenimiento"] : "No programado"; // Fecha del próximo mantenimiento

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

    // Caso para insertar un nuevo vehículo
    case "insertar":
        // Capturar los datos enviados desde el formulario
        $placa = isset($_POST["vehiculo_placa"]) ? $_POST["vehiculo_placa"] : null;
        $marca = isset($_POST["vehiculo_marca"]) ? $_POST["vehiculo_marca"] : null;
        $modelo = isset($_POST["vehiculo_modelo"]) ? $_POST["vehiculo_modelo"] : null;
        $anio = isset($_POST["vehiculo_anio"]) ? $_POST["vehiculo_anio"] : null;
        $color = isset($_POST["vehiculo_color"]) ? $_POST["vehiculo_color"] : null;
        $motor = isset($_POST["vehiculo_motor"]) ? $_POST["vehiculo_motor"] : null;
        $combustible = isset($_POST["vehiculo_combustible"]) ? $_POST["vehiculo_combustible"] : null;
        $tipo = isset($_POST["vehiculo_tipo"]) ? $_POST["vehiculo_tipo"] : null;
        $poliza = isset($_POST["vehiculo_poliza"]) ? $_POST["vehiculo_poliza"] : null;
        $estado = isset($_POST["vehiculo_estado"]) ? $_POST["vehiculo_estado"] : null;

        // Insertar el nuevo vehículo en la base de datos usando el modelo
        if ($vehiculo->insertar_vehiculo($placa, $marca, $modelo, $anio, $color, $motor, $combustible, $tipo, $poliza, $estado)) {
            echo json_encode(["success" => "Vehículo registrado correctamente."]);
        } else {
            echo json_encode(["error" => "Error al registrar el vehículo."]);
        }
        break;

    // Caso para editar un vehículo existente
    case "editar":
        // Capturar los datos enviados por el formulario
        $id = $_POST["vehiculo_id"];
        $placa = $_POST["vehiculo_placa"];
        $marca = $_POST["vehiculo_marca"];
        $modelo = $_POST["vehiculo_modelo"];
        $anio = $_POST["vehiculo_anio"];
        $color = $_POST["vehiculo_color"];
        $motor = $_POST["vehiculo_motor"];
        $combustible = $_POST["vehiculo_combustible"];
        $tipo_vehiculo = $_POST["vehiculo_tipo"];
        $poliza = $_POST["vehiculo_poliza"];
        $estado = $_POST["vehiculo_estado"];

        // Llamar al método editar_vehiculo del modelo
        if ($vehiculo->editar_vehiculo($id, $placa, $marca, $modelo, $anio, $color, $motor, $combustible, $tipo_vehiculo, $poliza, $estado)) {
            echo json_encode(["success" => "Vehículo actualizado correctamente."]);
        } else {
            echo json_encode(["error" => "Error al actualizar el vehículo."]);
        }
        break;

    case "mostrar":
        if (isset($_POST["vehiculo_id"])) {
            $datos = $vehiculo->get_vehiculo_por_id($_POST["vehiculo_id"]);
            if ($datos) {
                echo json_encode($datos);
            } else {
                echo json_encode(["error" => "No se encontraron datos para el ID del vehículo."]);
            }
        } else {
            echo json_encode(["error" => "No se proporcionó un ID de vehículo válido."]);
        }
        break;

    case "eliminar":
        if (isset($_POST["vehiculo_id"])) {
            $id = $_POST["vehiculo_id"];
            if ($vehiculo->cambiar_estado($id, 0)) {
                echo json_encode(["success" => "Vehículo eliminado correctamente."]);
            } else {
                echo json_encode(["error" => "Error al eliminar el vehículo."]);
            }
        } else {
            echo json_encode(["error" => "No se proporcionó un ID de vehículo válido."]);
        }
        break;

    default:
        echo json_encode(["error" => "Operación no válida."]);
        break;
}
?>
