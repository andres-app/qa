<?php
require_once("../config/conexion.php");
require_once("../models/Iteraciones.php");
require_once("../models/Casos_prueba.php");

$iter = new Iteraciones();
$caso = new Casos_prueba();

switch ($_GET["op"]) {

    case "listar":
        $datos = $iter->get_iteraciones_por_caso($_POST["id_caso"]);
        echo json_encode([ "data" => $datos ]);
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

        // Lógica de estado del caso
        $estado_caso = "En ejecución"; // al menos
        if ($cerrar && $resultado === "Ejecutado") {
            $estado_caso = "Completado";
        } elseif ($resultado === "Observado") {
            $estado_caso = "En ejecución"; // se mantiene para permitir más iteraciones
        } else {
            $estado_caso = "En ejecución";
        }
        $caso->actualizar_estado_caso($id_caso, $estado_caso);

        echo json_encode(["success" => "Iteración registrada.", "estado_caso" => $estado_caso]);
        break;
}
