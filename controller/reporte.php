<?php
require_once("../config/conexion.php");
require_once("../models/Reporte.php");

$reporte = new Reporte();

header('Content-Type: application/json; charset=utf-8');

switch ($_GET["op"]) {

    // ============================================================
    // 📊 LISTAR CASOS DE PRUEBA (para DataTable con filtros)
    // ============================================================
    case "listar":
        $id_organo = $_POST["id_organo"] ?? "";
        $estado = $_POST["estado"] ?? "Todos";

        $datos = $reporte->get_detalle_por_organo($id_organo, $estado);

        if (!$datos) {
            echo json_encode(["aaData" => []]);
            exit;
        }

        $data = [];
        foreach ($datos as $row) {
            $badge = match (strtolower($row["estado_ejecucion"])) {
                "completado" => '<span class="badge bg-success">Completado</span>',
                "pendiente"  => '<span class="badge bg-warning text-dark">Pendiente</span>',
                "observado"  => '<span class="badge bg-danger">Observado</span>',
                default      => '<span class="badge bg-secondary">' . $row["estado_ejecucion"] . '</span>'
            };

            $data[] = [
                "organo_jurisdiccional" => $row["organo_jurisdiccional"],
                "codigo_requerimiento"  => $row["codigo_requerimiento"],
                "nombre_requerimiento"  => $row["nombre_requerimiento"],
                "codigo_caso"           => $row["codigo_caso"],
                "nombre_caso"           => $row["nombre_caso"],
                "version"               => $row["version"],
                "estado_badge"          => $badge,
                "responsable"           => $row["responsable"] ?? $row["usuario_registro"] ?? 'Equipo QA',
                "fecha_registro"        => $row["fecha_registro"]
            ];
        }

        echo json_encode([
            "sEcho" => 1,
            "iTotalRecords" => count($data),
            "iTotalDisplayRecords" => count($data),
            "aaData" => $data
        ], JSON_UNESCAPED_UNICODE);
        exit;


    // ============================================================
    // 📋 DETALLE DESDE EL GRÁFICO (para redirección)
    // ============================================================
    case "reporte_organo_detalle":
        $id_organo = $_POST["id_organo"] ?? null;
        if (!$id_organo) {
            echo json_encode(["error" => "ID de órgano no recibido"]);
            exit;
        }

        $datos = $reporte->get_detalle_por_organo($id_organo);

        echo json_encode($datos, JSON_UNESCAPED_UNICODE);
        exit;


    // ============================================================
    // 📊 TRAZABILIDAD GENERAL
    // ============================================================
    case "reporte_trazabilidad":
        $datos = $reporte->reporte_trazabilidad();
        echo json_encode($datos, JSON_UNESCAPED_UNICODE);
        exit;


    // ============================================================
    // 📈 COBERTURA
    // ============================================================
    case "reporte_cobertura":
        $datos = $reporte->reporte_cobertura();
        echo json_encode($datos, JSON_UNESCAPED_UNICODE);
        exit;


    default:
        echo json_encode(["error" => "Operación no válida"]);
        exit;
}
?>
