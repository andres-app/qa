<?php
require_once("../config/conexion.php");
require_once("../models/Iteraciones.php");
require_once("../models/Casos_prueba.php");

$iter = new Iteraciones();
$caso = new Casos_prueba();

switch ($_GET["op"]) {

    case "listar":
        $datos = $iter->get_iteraciones_por_caso($_POST["id_caso"]);
        echo json_encode(["data" => $datos]);
        break;

    case "guardar":
        $id_caso = $_POST["id_caso"];
        $ejecutor_nombre = $_POST["ejecutor_nombre"] ?? null;
        $fecha_ejecucion = $_POST["fecha_ejecucion"] ?? null;
        $resultado = $_POST["resultado"];
        $comentario = $_POST["comentario"] ?? null;
        $cerrar = isset($_POST["cerrar_caso"]) && $_POST["cerrar_caso"] == "1";

        // Insertar iteración
        $iter->insert_iteracion_full($id_caso, $ejecutor_nombre, $fecha_ejecucion, $resultado, $comentario, $cerrar);

        // =======================================================
// Lógica de estado del caso (actualizada)
// =======================================================
        if ($resultado === "Ejecutado") {
            // Si está ejecutado, se marca directamente como completado
            $estado_caso = "Completado";
            $cerrar = 1; // por consistencia, marcamos la iteración como cerrada también
        } elseif ($resultado === "Observado") {
            // Si hubo observaciones, se mantiene en ejecución
            $estado_caso = "En ejecución";
        } else {
            // Cualquier otro caso (por si en el futuro hay más estados)
            $estado_caso = "En ejecución";
        }

        $caso->actualizar_estado_caso($id_caso, $estado_caso);

        echo json_encode(["success" => "Iteración registrada.", "estado_caso" => $estado_caso]);
        break;
}
