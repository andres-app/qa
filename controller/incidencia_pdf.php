<?php
// Limpia cualquier salida previa para evitar "Some data has already been output"
if (ob_get_length()) { ob_end_clean(); }
ob_start();

require_once("../config/conexion.php");
require_once("../models/Incidencia.php");
require_once("../libs/tcpdf/tcpdf.php");

// ==============================
// OBTENER DATA
// ==============================
$id_incidencia = isset($_GET["id"]) ? intval($_GET["id"]) : 0;
if ($id_incidencia <= 0) {
    die("ID de incidencia inválido");
}

$inc  = new Incidencia();
$data = $inc->mostrar($id_incidencia);

if (!$data) {
    die("No se encontró la incidencia solicitada.");
}

// Helpers para evitar warnings
function v($array, $key, $default = "-") {
    return isset($array[$key]) && $array[$key] !== "" ? $array[$key] : $default;
}
function cb($actual, $value) {
    return ($actual === $value) ? "[ X ]" : "[   ]";
}

// Mapeo de campos (ajusta estos nombres a tu tabla)
$estado_inicial  = v($data, "estado_inicial", "PENDIENTE");
$fecha_inicial   = v($data, "fecha_inicial", "dd/mm/aaaa");
$estado_actual   = v($data, "estado_incidencia", "PENDIENTE");
$fecha_actual    = date("d/m/Y");

$ambiente        = v($data, "ambiente_pruebas", "Ambiente de pruebas v 1.0-SAJ – PJ / Registro de expediente");

$nro_incidencia  = v($data, "id_incidencia");
$nro_glpi        = v($data, "glpi");
$version_origen  = v($data, "version_origen");
$caso_prueba     = v($data, "caso_prueba");
$paso_cp         = v($data, "paso_cp");

$tipo_error      = v($data, "tipo_incidencia"); // Ej: Funcional, Datos, Diseño, Otros
$criticidad      = v($data, "prioridad");       // Ej: Alto, Medio, Bajo

$motor_bd        = v($data, "motor_bd");
$base_datos      = v($data, "base_datos");
$perfil          = v($data, "perfil");
$usuario         = v($data, "usuario");

$descripcion     = v($data, "descripcion", "");
$pasos           = v($data, "pasos", "");
$observaciones   = v($data, "observaciones", "");
$analista        = v($data, "analista", "");

// Checkboxes Tipo de Error
$cb_funcional = cb($tipo_error, "Funcional");
$cb_datos     = cb($tipo_error, "Datos");
$cb_diseno    = cb($tipo_error, "Diseño");
$cb_otros     = cb($tipo_error, "Otros");

// Checkboxes Criticidad
$cb_alto  = cb($criticidad, "Alto");
$cb_medio = cb($criticidad, "Medio");
$cb_bajo  = cb($criticidad, "Bajo");

// ==============================
// CONFIGURACIÓN DEL PDF
// ==============================
$pdf = new TCPDF("P", "mm", "A4", true, "UTF-8", false);
$pdf->setPrintHeader(false);
$pdf->setPrintFooter(false);

$pdf->SetCreator("Sistema QA - P.J");
$pdf->SetAuthor("Sistema QA");
$pdf->SetTitle("Informe de Pruebas INC-" . $nro_incidencia);

$pdf->SetMargins(15, 15, 15);
$pdf->SetAutoPageBreak(true, 15);
$pdf->AddPage();

// ==============================
// ESTILOS
// ==============================
$css = '
<style>
.titulo-principal {
    font-size: 16px;
    font-weight: bold;
    text-align: center;
}
.version-text {
    font-size: 11px;
    text-align: center;
}
.estado-label {
    font-size: 10px;
    font-weight: bold;
}
.estado-valor {
    font-size: 10px;
}
.tbl-estados td {
    font-size: 10px;
}
.tbl-analisis {
    border-collapse: collapse;
    font-size: 9px;
}
.tbl-analisis td {
    border: 0.5px solid #000000;
    padding: 3px;
}
.tbl-analisis .header-negro {
    background-color: #000000;
    color: #ffffff;
    font-weight: bold;
    text-align: left;
}
.tbl-analisis .label {
    background-color: #f0f0f0;
    font-weight: bold;
}
.seccion-titulo {
    font-size: 10px;
    font-weight: bold;
    margin-top: 6px;
}
.parrafo {
    font-size: 9px;
    text-align: justify;
}
</style>
';

// ==============================
// CONTENIDO HTML
// ==============================
$html = $css;

// Título
$html .= '
<div class="titulo-principal">INFORME DE PRUEBAS N° '.str_pad($nro_incidencia, 4, "0", STR_PAD_LEFT).'</div>
<div class="version-text">Versión</div>
<div class="version-text">1.0</div>
<br><br>
';

// Estados
$html .= '
<table class="tbl-estados" width="100%" cellpadding="2">
    <tr>
        <td width="50%">
            <span class="estado-label">Estado Inicial: </span>
            <span class="estado-valor">'.$estado_inicial.'</span>
        </td>
        <td width="50%" style="text-align:right;">
            <span class="estado-label">['.$fecha_inicial.']</span>
        </td>
    </tr>
    <tr>
        <td width="50%">
            <span class="estado-label">Estado Actual: </span>
            <span class="estado-valor">'.$estado_actual.'</span>
        </td>
        <td width="50%" style="text-align:right;">
            <span class="estado-label">['.$fecha_actual.']</span>
        </td>
    </tr>
</table>
<br>
<div class="parrafo">'.$ambiente.'</div>
<br>
';

// Tabla Documento de análisis (igual al formato)
$html .= '
<table class="tbl-analisis" width="100%" cellpadding="2">
    <tr>
        <td class="header-negro" colspan="4">Documento de análisis</td>
    </tr>

    <tr>
        <td class="label" width="20%">Nro. Incidencia</td>
        <td width="30%">'.$nro_incidencia.'</td>
        <td class="label" width="20%">N° GLPI</td>
        <td width="30%">'.$nro_glpi.'</td>
    </tr>

    <tr>
        <td class="label">Versión Origen</td>
        <td>'.$version_origen.'</td>
        <td class="label">Caso de Prueba</td>
        <td>'.$caso_prueba.'</td>
    </tr>

    <tr>
        <td class="label">Paso del CP</td>
        <td>'.$paso_cp.'</td>
        <td class="label"></td>
        <td></td>
    </tr>

    <tr>
        <td class="label" valign="top">Tipo de Error</td>
        <td valign="top">
            '.$cb_funcional.' Funcional<br>
            '.$cb_datos.' Datos<br>
            '.$cb_diseno.' Diseño<br>
            '.$cb_otros.' Otros
        </td>
        <td class="label" valign="top">Criticidad</td>
        <td valign="top">
            '.$cb_alto.' Alto<br>
            '.$cb_medio.' Medio<br>
            '.$cb_bajo.' Bajo
        </td>
    </tr>

    <tr>
        <td class="label">Motor de BD</td>
        <td>'.$motor_bd.'</td>
        <td class="label">Base de datos</td>
        <td>'.$base_datos.'</td>
    </tr>

    <tr>
        <td class="label">Perfil</td>
        <td>'.$perfil.'</td>
        <td class="label">Usuario</td>
        <td>'.$usuario.'</td>
    </tr>

    <tr>
        <td class="label" valign="top">Descripción<br>|Incidencia</td>
        <td colspan="3" valign="top">
            '.nl2br(htmlspecialchars($descripcion)).'
        </td>
    </tr>
</table>
<br>
';

// Secuencia de pasos
$html .= '
<div class="seccion-titulo">Secuencia de pasos realizados</div>
<table class="tbl-analisis" width="100%" cellpadding="2">
    <tr>
        <td width="5%" class="label" valign="top">1</td>
        <td width="95%" valign="top">
            '.nl2br(htmlspecialchars($pasos)).'
        </td>
    </tr>
</table>
<br>
';

// Observaciones
$html .= '
<div class="seccion-titulo">Observaciones.</div>
<div class="parrafo">'.nl2br(htmlspecialchars($observaciones)).'</div>
<br><br><br>
';

// Firma
$html .= '
<div style="font-size:9px; text-align:left;">
    Analista de Calidad<br>
    '.$analista.'
</div>
';

// ==============================
// GENERAR PDF
// ==============================
$pdf->writeHTML($html, true, false, true, false, '');
$pdf->Output("Informe_INC_".$nro_incidencia.".pdf", "I");

ob_end_flush();
