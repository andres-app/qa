<?php
require_once("../config/conexion.php");
require_once("../models/Proceso.php");
$proceso = new Proceso();

switch ($_GET["op"]) {
    case "listar":
        $datos = $proceso->get_procesos();

        if ($datos === false) {
            echo json_encode(["error" => "Error al obtener los procesos"]);
            exit;
        }

        $data = array();
        foreach ($datos as $row) {
            $sub_array = array();
            $sub_array[] = $row["sinad"]; // Este es el nombre del proceso
            $sub_array[] = $row["nombre"]; // Puedes incluir esta columna o modificarla si tienes otra columna
            $sub_array[] = $row["fecha_inicio"]; // La fecha de creación
            $sub_array[] = '<button type="button" class="btn btn-soft-warning waves-effect waves-light btn-sm" onClick="editar(' . $row["id"] . ')"><i class="bx bx-edit-alt font-size-16 align-middle"></i></button>
                            <button type="button" class="btn btn-soft-danger waves-effect waves-light btn-sm" onClick="eliminar(' . $row["id"] . ')"><i class="bx bx-trash-alt font-size-16 align-middle"></i></button>';
            $data[] = $sub_array;
        }

        // Asegúrate de que el JSON es válido y no hay salida adicional
        $results = array(
            "sEcho" => 1,
            "iTotalRecords" => count($data),
            "iTotalDisplayRecords" => count($data),
            "aaData" => $data
        );

        header('Content-Type: application/json'); // Esto es importante para asegurarse de que la respuesta sea JSON
        echo json_encode($results);
        break;

    case "mostrar":
        // Otros casos...
        break;

    case "eliminar":
        // Otros casos...
        break;

    default:
        echo json_encode(["error" => "Operación no válida."]);
        break;
}
