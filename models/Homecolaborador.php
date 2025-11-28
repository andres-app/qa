<?php

require_once(__DIR__ . "/Reporte.php");
require_once(__DIR__ . "/Requerimiento.php");
require_once(__DIR__ . "/Incidencia.php");

class Homecolaborador
{
    /**
     * Obtiene todos los datos necesarios para el dashboard
     * usando EXACTAMENTE la misma lógica que tenías en el index.
     */
    public static function obtenerDatosDashboard()
    {
        // ============================
        // REQUERIMIENTOS
        // ============================
        $requerimiento = new Requerimiento();
        $data_total = $requerimiento->get_total_requerimientos();
        $total_requerimientos = isset($data_total["total"]) ? (int)$data_total["total"] : 0;

        // ============================
        // REPORTES
        // ============================
        $reporte = new Reporte();

        $data_casos = $reporte->get_total_casos_prueba();
        $total_casos_prueba = (int)($data_casos["total"] ?? 0);

        $porcentaje_data = $reporte->get_porcentaje_casos_ejecutados();
        $porcentaje_casos_ejecutados = $porcentaje_data["porcentaje"] ?? 0;

        // ============================
        // ANÁLISIS DE FUNCIONALIDAD
        // ============================
        $analisis_funcionalidad = $reporte->get_analisis_funcionalidad();

        $analisis_funcionalidad = array_filter(
            $analisis_funcionalidad,
            fn($r) => !empty($r["organo_jurisdiccional"])
        );

        $agrupado = [];

        foreach ($analisis_funcionalidad as $row) {
            $org  = $row["organo_jurisdiccional"];
            $func = $row["funcionalidad"];

            if (!isset($agrupado[$org][$func])) {
                $agrupado[$org][$func] = [
                    "total_requisitos"     => (int)$row["total_requisitos"],
                    "total_requerimientos" => (int)$row["total_requerimientos"]
                ];
            } else {
                $agrupado[$org][$func]["total_requisitos"]     += (int)$row["total_requisitos"];
                $agrupado[$org][$func]["total_requerimientos"] += (int)$row["total_requerimientos"];
            }
        }

        $analisis_funcionalidad_limpio = [];
        foreach ($agrupado as $org => $funcs) {
            foreach ($funcs as $func => $datos) {
                $analisis_funcionalidad_limpio[] = [
                    "organo_jurisdiccional" => $org,
                    "funcionalidad"         => $func,
                    "total_requisitos"      => $datos["total_requisitos"],
                    "total_requerimientos"  => $datos["total_requerimientos"]
                ];
            }
        }

        // Orden jerárquico
        $ordenJerarquico = [
            "Sala Suprema",
            "Sala Superior",
            "Juzgado Especializado",
            "Juzgado Mixto",
            "Juzgado de Paz Letrado",
            "Juzgado de Paz"
        ];

        usort($analisis_funcionalidad_limpio, function ($a, $b) use ($ordenJerarquico) {
            $posA = array_search($a["organo_jurisdiccional"], $ordenJerarquico);
            $posB = array_search($b["organo_jurisdiccional"], $ordenJerarquico);
            return $posA <=> $posB;
        });

        // ============================
        // CASOS POR ÓRGANO
        // ============================
        $casos_por_organo = $reporte->get_casos_por_organo_jurisdiccional();
        $labels_organo    = array_column($casos_por_organo, "organo_jurisdiccional");
        $valores_organo   = array_map(fn($r) => (int)$r["total_casos"], $casos_por_organo);
        $ids_organo       = array_column($casos_por_organo, "id_organo");

        // ============================
        // SEGUIMIENTO POR ESPECIALIDAD
        // ============================
        $seguimiento_especialidad = $reporte->get_seguimiento_por_especialidad();
        $labels_especialidad      = array_column($seguimiento_especialidad, "especialidad");
        $data_completado          = array_column($seguimiento_especialidad, "completado");
        $data_observado           = array_column($seguimiento_especialidad, "observado");
        $data_pendiente           = array_column($seguimiento_especialidad, "pendiente");

        // ============================
        // INCIDENCIAS
        // ============================
        $inc = new Incidencia();
        $inc_por_doc       = $inc->incidencias_por_documento();
        $inc_doc_analista  = $inc->incidencias_por_documento_analista();
        $inc_por_mod       = $inc->incidencias_por_modulo();
        $inc_por_mes       = $inc->incidencias_por_mes();
        $inc_total         = $inc->contar_todas();

        // ============================
        // RESUMEN CONSOLIDADO
        // ============================
        $resumen = $reporte->get_resumen_por_especialidad() ?? [];

        // ============================
        // RETORNAR TODO LISTO
        // ============================
        return compact(
            "total_requerimientos",
            "total_casos_prueba",
            "porcentaje_data",
            "porcentaje_casos_ejecutados",
            "analisis_funcionalidad_limpio",
            "labels_organo",
            "valores_organo",
            "ids_organo",
            "labels_especialidad",
            "data_completado",
            "data_observado",
            "data_pendiente",
            "inc_por_doc",
            "inc_doc_analista",
            "inc_por_mod",
            "inc_por_mes",
            "inc_total",
            "resumen"
        );
    }
}
