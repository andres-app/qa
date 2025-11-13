<?php
require_once("../config/conexion.php");
require_once("../models/Incidencia.php");
require_once("../libs/tcpdf/tcpdf.php");


$id_incidencia = isset($_GET["id"]) ? intval($_GET["id"]) : 0;
if ($id_incidencia <= 0) {
    die("ID de incidencia inválido");
}

$inc = new Incidencia();
$data = $inc->mostrar($id_incidencia);

if (!$data) {
    die("No se encontró la incidencia solicitada.");
}

// ==============================
// CONFIGURACIÓN DEL PDF
// ==============================
$pdf = new TCPDF("P", "mm", "A4", true, "UTF-8", false);

$pdf->SetCreator("Sistema QA - P.J");
$pdf->SetAuthor("Sistema QA");
$pdf->SetTitle("Informe de Pruebas INC-" . $data["id_incidencia"]);

// Quitar header y footer por defecto
$pdf->setPrintHeader(false);
$pdf->setPrintFooter(false);

$pdf->SetMargins(15, 20, 15);
$pdf->SetAutoPageBreak(true, 20);

$pdf->AddPage();

// ==============================
// ESTILOS
// ==============================
$style_title = 'font-size:16px; font-weight:bold; text-align:center;';
$style_sub = 'font-size:12px; text-align:center; color:#444; margin-bottom:10px;';
$style_th = 'background-color:#efefef; font-weight:bold; font-size:11px; border:1px solid #000; padding:4px;';
$style_td = 'font-size:11px; border:1px solid #000; padding:4px;';
$style_section = 'font-size:12px; font-weight:bold; margin-top:10px;';
$style_p = 'font-size:11px; text-align:justify;';

// ==============================
// TÍTULO PRINCIPAL
// ==============================
$html = '
<br><br>
<div style="'.$style_title.'">INFORME DE PRUEBAS N° '.$data["id_incidencia"].'</div>
<div style="'.$style_sub.'">Versión V1.0</div>
<br>
';

// ==============================
// TABLA PRINCIPAL
// ==============================
$html .= '
<table cellpadding="3">
    <tr>
        <td style="'.$style_th.' width:30%;">N° Incidencia</td>
        <td style="'.$style_td.' width:70%;">INC-'.$data["id_incidencia"].'</td>
    </tr>
    <tr>
        <td style="'.$style_th.'">Módulo del Sistema</td>
        <td style="'.$style_td.'">'.htmlspecialchars($data["modulo"]).'</td>
    </tr>
    <tr>
        <td style="'.$style_th.'">Tipo de Incidencia</td>
        <td style="'.$style_td.'">'.htmlspecialchars($data["tipo_incidencia"]).'</td>
    </tr>
    <tr>
        <td style="'.$style_th.'">Prioridad</td>
        <td style="'.$style_td.'">'.htmlspecialchars($data["prioridad"]).'</td>
    </tr>
    <tr>
        <td style="'.$style_th.'">Base de Datos</td>
        <td style="'.$style_td.'">'.htmlspecialchars($data["base_datos"]).'</td>
    </tr>
    <tr>
        <td style="'.$style_th.'">Versión del Sistema</td>
        <td style="'.$style_td.'">'.htmlspecialchars($data["version_origen"]).'</td>
    </tr>
    <tr>
        <td style="'.$style_th.'">Analista QA</td>
        <td style="'.$style_td.'">'.htmlspecialchars($data["analista"]).'</td>
    </tr>
    <tr>
        <td style="'.$style_th.'">Estado Actual</td>
        <td style="'.$style_td.'">'.htmlspecialchars($data["estado_incidencia"]).'</td>
    </tr>
</table>
<br>
';

// ==============================
// DESCRIPCIÓN
// ==============================
$html .= '
<div style="'.$style_section.'">Descripción de la Incidencia</div>
<p style="'.$style_p.'">'.nl2br(htmlspecialchars($data["descripcion"])).'</p>
';

// ==============================
// ACCIÓN RECOMENDADA
// ==============================
$html .= '
<div style="'.$style_section.'">Acción Recomendada / Correctiva</div>
<p style="'.$style_p.'">'.nl2br(htmlspecialchars($data["accion_recomendada"])).'</p>
';

// ==============================
// PIE DE PÁGINA
// ==============================
$html .= '
<br><br><br>
<div style="text-align:center; font-size:10px; color:#555;">
Informe generado automáticamente por el Sistema QA - Poder Judicial del Perú
</div>
';

$pdf->writeHTML($html, true, false, true, false, '');

$pdf->Output("Informe_INC_".$data["id_incidencia"].".pdf", "I");
?>