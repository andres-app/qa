<?php
require_once(__DIR__ . "/../config/conexion.php");
require_once(__DIR__ . "/../models/Reporte.php");
require_once(__DIR__ . "/../models/Requerimiento.php");
require_once(__DIR__ . "/../models/Incidencia.php");
require_once(__DIR__ . "/../models/Rol.php");

// Validar acceso
$rol = new Rol();
$datos = $rol->validar_menu_x_rol($_SESSION["rol_id"], "iniciocolaborador");

if (!isset($_SESSION["usu_id"]) || count($datos) == 0) {
    header("Location:" . Conectar::ruta() . "index.php");
    exit();
}

// =======================
// MODELOS
// =======================
$req = new Requerimiento();
$rep = new Reporte();
$inc = new Incidencia();

// =======================
// DATOS PARA LA VISTA
// =======================
$data_total = $req->get_total_requerimientos();
$total_requerimientos = (int)($data_total["total"] ?? 0);

$data_casos = $rep->get_total_casos_prueba();
$total_casos_prueba = (int)($data_casos["total"] ?? 0);

$porcentaje_data = $rep->get_porcentaje_casos_ejecutados();
$porcentaje_casos_ejecutados = $porcentaje_data["porcentaje"] ?? 0;

$analisis_funcionalidad = $rep->get_analisis_funcionalidad();

// ==== LIMPIEZA EXACTA COMO TU CÓDIGO ORIGINAL ====
$analisis_funcionalidad = array_filter($analisis_funcionalidad, fn($r) => !empty($r["organo_jurisdiccional"]));

$agrupado = [];
foreach ($analisis_funcionalidad as $row) {
    $org = $row["organo_jurisdiccional"];
    $func = $row["funcionalidad"];

    if (!isset($agrupado[$org][$func])) {
        $agrupado[$org][$func] = [
            "total_requisitos" => (int)$row["total_requisitos"],
            "total_requerimientos" => (int)$row["total_requerimientos"]
        ];
    } else {
        $agrupado[$org][$func]["total_requisitos"] += (int)$row["total_requisitos"];
        $agrupado[$org][$func]["total_requerimientos"] += (int)$row["total_requerimientos"];
    }
}

$analisis_funcionalidad_limpio = [];
foreach ($agrupado as $org => $funcs) {
    foreach ($funcs as $func => $data) {
        $analisis_funcionalidad_limpio[] = [
            "organo_jurisdiccional" => $org,
            "funcionalidad" => $func,
            "total_requisitos" => $data["total_requisitos"],
            "total_requerimientos" => $data["total_requerimientos"]
        ];
    }
}

$inc_por_doc      = $inc->incidencias_por_documento();
$inc_doc_analista = $inc->incidencias_por_documento_analista();
$inc_por_mod      = $inc->incidencias_por_modulo();
$inc_por_mes      = $inc->incidencias_por_mes();

$resumen = $rep->get_resumen_por_especialidad();

// =======================
// Cargar la vista
// =======================
require_once(__DIR__ . "/../view/homecolaborador/index.php");
