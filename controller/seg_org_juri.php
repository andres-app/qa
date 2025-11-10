<?php
require_once("../config/conexion.php");
require_once("../models/Reporte.php");

$reporte = new Reporte();

switch ($_GET["op"]) {

    // ============================================================
    // 📋 COMBO DE ÓRGANOS
    // ============================================================
    case "combo_organo":
        $datos = $reporte->get_casos_por_organo_jurisdiccional();
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($datos, JSON_UNESCAPED_UNICODE);
        break;

    // ============================================================
    // 📊 LISTAR DETALLE DE CASOS DE PRUEBA
    // ============================================================
    case "listar":
        $id_organo = isset($_POST["id_organo"]) ? $_POST["id_organo"] : "";
        $estado = isset($_POST["estado"]) ? $_POST["estado"] : "Todos";
    
        $datos = $reporte->get_detalle_por_organo($id_organo);
    
        if (!$datos || !is_array($datos)) {
            echo json_encode(["sEcho" => 1, "iTotalRecords" => 0, "iTotalDisplayRecords" => 0, "aaData" => []]);
            exit;
        }
    
        // Filtrar por estado si aplica
        if (!empty($estado) && strtolower($estado) !== "todos") {
            $datos = array_filter($datos, function ($r) use ($estado) {
                return strtolower($r["estado_ejecucion"]) === strtolower($estado);
            });
        }
    
        // Convertir filas en formato asociativo para DataTables
        $data = [];
        foreach ($datos as $row) {
            $badge = match (strtolower($row["estado_ejecucion"])) {
                "completado" => '<span class="badge bg-success">Completado</span>',
                "observado"  => '<span class="badge bg-danger">Observado</span>',
                "pendiente"  => '<span class="badge bg-warning text-dark">Pendiente</span>',
                default      => '<span class="badge bg-secondary">' . htmlspecialchars($row["estado_ejecucion"]) . '</span>'
            };
    
            $data[] = [
                "organo_jurisdiccional" => $row["organo_jurisdiccional"],
                "codigo_requerimiento"  => $row["codigo_requerimiento"],
                "nombre_requerimiento"  => $row["nombre_requerimiento"],
                "codigo_caso"           => $row["codigo_caso"],
                "nombre_caso"           => $row["nombre_caso"],
                "version"               => $row["version"],
                "estado_badge"          => $badge,
                "responsable"           => $row["usuario_registro"] ?? $row["creado_por"] ?? "Equipo QA",
                "fecha_registro"        => $row["fecha_registro"] ?? "N/D"
            ];
        }
    
        // Respuesta final para DataTables
        $results = [
            "sEcho" => 1,
            "iTotalRecords" => count($data),
            "iTotalDisplayRecords" => count($data),
            "aaData" => $data
        ];
    
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($results, JSON_UNESCAPED_UNICODE);
        exit;
    
}
?>
