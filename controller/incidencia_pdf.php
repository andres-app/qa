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

// ==========================================================
// CONFIGURACIÓN DEL PDF
// ==========================================================
$pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);
$pdf->SetCreator('Sistema QA - Poder Judicial');
$pdf->SetAuthor('Equipo QA4');
$pdf->SetTitle('Informe de Pruebas - Incidencia');
$pdf->SetMargins(15, 20, 15);
$pdf->AddPage();

// ==========================================================
// ENCABEZADO PRINCIPAL
// ==========================================================
$html = '
<h3 style="text-align:center; font-weight:bold;">INFORME DE PRUEBAS N° ' . $data["id_incidencia"] . '</h3>
<p style="text-align:center;">Versión ' . htmlspecialchars($data["version_origen"] ?? '1.0') . '</p>
<hr>
<table border="1" cellpadding="4" width="100%">
    <tr style="background-color:#f2f2f2; font-weight:bold;">
        <td width="35%">N° Incidencia</td>
        <td width="65%">INC-' . $data["id_incidencia"] . '</td>
    </tr>
    <tr>
        <td><b>Módulo del Sistema</b></td>
        <td>' . htmlspecialchars($data["modulo"]) . '</td>
    </tr>
    <tr>
        <td><b>Tipo de Incidencia</b></td>
        <td>' . htmlspecialchars($data["tipo_incidencia"]) . '</td>
    </tr>
    <tr>
        <td><b>Prioridad</b></td>
        <td>' . htmlspecialchars($data["prioridad"]) . '</td>
    </tr>
    <tr>
        <td><b>Base de Datos</b></td>
        <td>' . htmlspecialchars($data["base_datos"]) . '</td>
    </tr>
    <tr>
        <td><b>Versión del Sistema</b></td>
        <td>' . htmlspecialchars($data["version_origen"]) . '</td>
    </tr>
    <tr>
        <td><b>Analista QA</b></td>
        <td>' . htmlspecialchars($data["analista"]) . '</td>
    </tr>
    <tr>
        <td><b>Estado Actual</b></td>
        <td>' . htmlspecialchars($data["estado_incidencia"]) . '</td>
    </tr>
</table>
<br>
<h4 style="background-color:#f2f2f2; padding:6px;">Descripción de la Incidencia</h4>
<p>' . nl2br(htmlspecialchars($data["descripcion"])) . '</p>
<h4 style="background-color:#f2f2f2; padding:6px;">Acción Recomendada / Correctiva</h4>
<p>' . nl2br(htmlspecialchars($data["accion_recomendada"])) . '</p>
<hr>
<p style="font-size:10px; text-align:center; margin-top:15px;">Informe generado automáticamente por el Sistema QA - Poder Judicial del Perú</p>
';

$pdf->writeHTML($html, true, false, true, false, '');
$pdf->Output('Incidencia_' . $data["id_incidencia"] . '.pdf', 'I');
