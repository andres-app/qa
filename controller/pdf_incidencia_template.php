<?php

/* ============================================================
   Helpers (evitan redeclaración cuando se incluye varias veces)
   ============================================================ */
if (!function_exists('v')) {
    function v($array, $key, $default = "-") {
        return isset($array[$key]) && $array[$key] !== "" ? $array[$key] : $default;
    }
}

if (!function_exists('cb')) {
    function cb($actual, $value) {
        return ($actual === $value) ? "[ X ]" : "[   ]";
    }
}

/* ============================================================
   MAPEO DE CAMPOS – IGUALITO QUE incidencia_pdf.php
   ============================================================ */

$estado_inicial = v($data, "estado_inicial", "PENDIENTE");
$fecha_inicial  = v($data, "fecha_inicial", "dd/mm/aaaa");
$estado_actual  = v($data, "estado_incidencia", "PENDIENTE");
$fecha_actual   = date("d/m/Y");

$ambiente       = v($data, "ambiente_pruebas", "");
$nro_incidencia = v($data, "correlativo_doc");
$nro_glpi       = v($data, "glpi");
$version_origen = v($data, "version_origen");
$caso_prueba    = v($data, "caso_prueba");
$paso_cp        = v($data, "paso_cp");

$tipo_error     = v($data, "tipo_incidencia");
$criticidad     = v($data, "prioridad");

$motor_bd       = v($data, "motor_bd");
$base_datos     = v($data, "base_datos");
$perfil         = v($data, "perfil");
$usuario        = v($data, "usuario");

$descripcion    = v($data, "descripcion");
$pasos          = v($data, "pasos");
$observaciones  = v($data, "observaciones");
$analista       = v($data, "analista");

$tipo_error     = v($data, "tipo_incidencia");
$criticidad     = v($data, "prioridad");

/* --- Regla especial: cuando es Documentacion, marcar como Otros --- */
$tipo = strtolower(trim($tipo_error));

if ($tipo === "documentacion" || $tipo === "documentación") {
    $tipo_error = "Otros";
}

if ($tipo === "otro") {
    $tipo_error = "Otros";
}


/* Checkboxes */
$cb_funcional = cb($tipo_error, "Funcional");
$cb_datos     = cb($tipo_error, "Datos");
$cb_diseno    = cb($tipo_error, "Diseño");
$cb_otros     = cb($tipo_error, "Otros");


$tipo_error     = v($data, "tipo_incidencia");
$criticidad     = v($data, "prioridad");

/* --- Regla especial: cuando es Documentacion, marcar como Otros --- */
$tipo = strtolower(trim($tipo_error));

if ($tipo === "documentacion" || $tipo === "documentación") {
    $tipo_error = "Otros";
}

if ($tipo === "otro") {
    $tipo_error = "Otros";
}

/* --- Normalización de criticidad --- */
$crit = strtolower(trim($criticidad));

if ($crit === "alta")  { $criticidad = "Alto"; }
if ($crit === "media") { $criticidad = "Medio"; }
if ($crit === "baja")  { $criticidad = "Bajo"; }

/* Checkboxes */
$cb_funcional = cb($tipo_error, "Funcional");
$cb_datos     = cb($tipo_error, "Datos");
$cb_diseno    = cb($tipo_error, "Diseño");
$cb_otros     = cb($tipo_error, "Otros");

$cb_alto      = cb($criticidad, "Alto");
$cb_medio     = cb($criticidad, "Medio");
$cb_bajo      = cb($criticidad, "Bajo");


/* ============================================================
   IMÁGENES
   ============================================================ */
$imagenes = [];
if (!empty($data["imagenes"])) {
    $imagenes = json_decode($data["imagenes"], true);
}

/* ============================================================
   CSS IGUALITO
   ============================================================ */
$html = '
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

/* ============================================================
   ENCABEZADO PODER JUDICIAL — IGUALITO
   ============================================================ */
$html .= '
<table width="100%" cellpadding="0" cellspacing="0">
    <tr>
        <td width="20%" style="border:0.5px solid #000; border-right:none; padding:8px; text-align:center;">
            <img src="https://tse4.mm.bing.net/th/id/OIP.OgB_zffZXepRWKo6PRkJjwHaHa?rs=1&pid=ImgDetMain&o=7&rm=3" style="height:65px;">
        </td>

        <td width="60%" style="border:0.5px solid #000; font-size:16px; font-weight:bold; text-align:center;">
            <div style="height:80px; display:flex; align-items:center; justify-content:center;">
                INFORME DE PRUEBAS N° 001
            </div>
        </td>

        <td width="20%" style="border:0.5px solid #000; text-align:center;">
            <div style="height:80px; display:flex; flex-direction:column; justify-content:center;">
                <b>Versión</b><br>1.0
            </div>
        </td>
    </tr>
</table>
<br>
';

/* ============================================================
   ESTADOS — BLOQUE NEGRO — IGUALITO
   ============================================================ */

$html .= '
<table width="100%" cellpadding="4" cellspacing="0" style="border-collapse:collapse; font-size:12px;">

    <tr style="background-color:#000; color:#FFF; font-weight:bold;">
        <td width="40%" align="right" style="border:0.5px solid #000;">Estado Inicial:</td>
        <td width="30%" align="left"  style="border:0.5px solid #000; color:#8DB4FF;">' . $estado_actual . '</td>
        <td width="30%" align="right" style="border:0.5px solid #000;">[' . $fecha_actual . ']</td>
    </tr>

    <tr style="background-color:#000; color:#FFF; font-weight:bold;">
        <td align="right" style="border:0.5px solid #000;">Estado Actual:</td>
        <td align="left"  style="border:0.5px solid #000; color:#8DB4FF;">' . $estado_inicial . '</td>
        <td align="right" style="border:0.5px solid #000;">[' . $fecha_inicial . ']</td>
    </tr>

</table>
<br>
';

/* ============================================================
   TABLA PRINCIPAL — IGUALITA
   ============================================================ */

$html .= '
<table class="tbl-analisis" width="100%">
    <tr>
        <td class="label" width="100%">' . v($data, "documentacion_nombre") . '</td>
    </tr>

    <tr>
        <td class="label" width="20%">Nro. Incidencia</td>
        <td width="30%">' . $nro_incidencia . '</td>
        <td class="label" width="20%">N° GLPI</td>
        <td width="30%">' . $nro_glpi . '</td>
    </tr>

    <tr>
        <td class="label">Versión Origen</td>
        <td>' . $version_origen . '</td>
        <td class="label">Caso de Prueba</td>
        <td>' . $caso_prueba . '</td>
    </tr>

    <tr>
        <td class="label">Paso del CP</td>
        <td>' . $paso_cp . '</td>
        <td class="label"></td>
        <td></td>
    </tr>

    <tr>
        <td class="label" valign="top">Tipo de Error</td>
        <td valign="top">
            ' . $cb_funcional . ' Funcional<br>
            ' . $cb_datos . ' Datos<br>
            ' . $cb_diseno . ' Diseño<br>
            ' . $cb_otros . ' Otros
        </td>

        <td class="label" valign="top">Criticidad</td>
        <td valign="top">
            ' . $cb_alto . ' Alto<br>
            ' . $cb_medio . ' Medio<br>
            ' . $cb_bajo . ' Bajo
        </td>
    </tr>

    <tr>
        <td class="label">Motor de BD</td>
        <td>' . $motor_bd . '</td>
        <td class="label">Base de datos</td>
        <td>' . $base_datos . '</td>
    </tr>

    <tr>
        <td class="label">Perfil</td>
        <td>' . $perfil . '</td>
        <td class="label">Usuario</td>
        <td>' . $usuario . '</td>
    </tr>

    <tr>
        <td class="label" valign="top">Descripción Incidencia</td>
        <td colspan="3" valign="top">' . nl2br(htmlspecialchars($descripcion)) . '</td>
    </tr>
</table>
<br>
';

/* ============================================================
   PASOS + IMÁGENES — IGUALITO
   ============================================================ */

$contenido_pasos = nl2br(htmlspecialchars($pasos));

if (!empty($imagenes)) {

    $contenido_pasos .= '<br><br><b>Evidencias gráficas (capturas):</b><br><br>';

    foreach ($imagenes as $img) {

        $rutaLocal = "../" . $img;

        if (file_exists($rutaLocal)) {

            $rutaAbs = realpath($rutaLocal);

            $contenido_pasos .= '
                <div style="text-align:center; margin-top:10px;">
                    <img src="' . $rutaAbs . '" width="520">
                </div><br>';
        }
    }
}

$html .= '
<div class="seccion-titulo">Secuencia de pasos realizados</div>

<table class="tbl-analisis" width="100%">
    <tr>
        <td width="5%" class="label">1</td>
        <td width="95%" valign="top">' . $contenido_pasos . '</td>
    </tr>
</table>
<br>
';

/* ============================================================
   OBSERVACIONES + FIRMA — IGUALITO
   ============================================================ */

$html .= '
<div class="seccion-titulo">Observaciones</div>
<div class="parrafo">' . nl2br(htmlspecialchars($observaciones)) . '</div>
<br><br>

<div style="font-size:9px; text-align:left;">
    Analista de Calidad<br>
    ' . $analista . '
</div>
';

/* ============================================================
   DEVOLVER HTML
   ============================================================ */
echo $html;
