<?php
require_once("../../config/conexion.php");
require_once("../../models/Reporte.php");
require_once("../../models/Requerimiento.php");
require_once("../../models/Rol.php");

$rol = new Rol();
$datos = $rol->validar_menu_x_rol($_SESSION["rol_id"], "iniciocolaborador");

if (isset($_SESSION["usu_id"]) && count($datos) > 0) {

    $requerimiento = new Requerimiento();
    $data_total = $requerimiento->get_total_requerimientos();
    $total_requerimientos = isset($data_total["total"]) ? (int) $data_total["total"] : 0;

    $reporte = new Reporte();

    // === Totales generales ===
    $data_casos = $reporte->get_total_casos_prueba();
    $total_casos_prueba = (int) ($data_casos["total"] ?? 0);

    $porcentaje_data = $reporte->get_porcentaje_casos_ejecutados();
    $porcentaje_casos_ejecutados = $porcentaje_data["porcentaje"] ?? 0;

    // === Análisis por Funcionalidad ===
    $analisis_funcionalidad = $reporte->get_analisis_funcionalidad();

    // Limpiar y agrupar datos
    $analisis_funcionalidad = array_filter($analisis_funcionalidad, fn($r) => !empty($r["organo_jurisdiccional"]));
    $agrupado = [];

    foreach ($analisis_funcionalidad as $row) {
        $org = $row["organo_jurisdiccional"];
        $func = $row["funcionalidad"];
        if (!isset($agrupado[$org][$func])) {
            $agrupado[$org][$func] = [
                "total_requisitos" => (int) $row["total_requisitos"],
                "total_requerimientos" => (int) $row["total_requerimientos"]
            ];
        } else {
            $agrupado[$org][$func]["total_requisitos"] += (int) $row["total_requisitos"];
            $agrupado[$org][$func]["total_requerimientos"] += (int) $row["total_requerimientos"];
        }
    }

    $analisis_funcionalidad_limpio = [];
    foreach ($agrupado as $org => $funcs) {
        foreach ($funcs as $func => $datos) {
            $analisis_funcionalidad_limpio[] = [
                "organo_jurisdiccional" => $org,
                "funcionalidad" => $func,
                "total_requisitos" => $datos["total_requisitos"],
                "total_requerimientos" => $datos["total_requerimientos"]
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

    // === Casos por órgano jurisdiccional ===
    $casos_por_organo = $reporte->get_casos_por_organo_jurisdiccional();
    $labels_organo = array_column($casos_por_organo, "organo_jurisdiccional");
    $valores_organo = array_map(fn($r) => (int) $r["total_casos"], $casos_por_organo);
    $ids_organo = array_column($casos_por_organo, "id_organo");

    // === Seguimiento por especialidad ===
    $seguimiento_especialidad = $reporte->get_seguimiento_por_especialidad();
    $labels_especialidad = array_column($seguimiento_especialidad, "especialidad");
    $data_aprobado = array_column($seguimiento_especialidad, "completado");
    $data_en_ejecucion = array_column($seguimiento_especialidad, "en_ejecucion");
    $data_pendiente = array_column($seguimiento_especialidad, "pendiente");

    // === Resumen consolidado ===
    $resumen = $reporte->get_resumen_por_especialidad() ?? [];
    ?>
    <!doctype html>
    <html lang="es">

    <head>
        <title>Dashboard - Matriz General</title>
        <?php require_once("../html/head.php") ?>
        <link rel="stylesheet" href="homecolaborador.css">
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    </head>

    <body>
        <div id="layout-wrapper">
            <?php require_once("../html/header.php") ?>
            <?php require_once("../html/menu.php") ?>

            <div class="main-content">
                <div class="page-content">
                    <div class="container-fluid">

                        <h4 class="mb-4 fw-bold text-secondary">Dashboard - Matriz General</h4>

                        <!-- KPIs Superiores -->
                        <div class="row mb-4 g-3">
                            <div class="col-md-4">
                                <div class="card kpi-card text-center shadow-sm">
                                    <div class="card-body">
                                        <h6>Total Requerimientos</h6>
                                        <h2><?= $total_requerimientos; ?></h2>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card kpi-card text-center shadow-sm">
                                    <div class="card-body">
                                        <h6>Total Casos de Prueba</h6>
                                        <h2><?= $total_casos_prueba; ?></h2>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card kpi-card text-center shadow-sm">
                                    <div class="card-body">
                                        <h6>% Casos Ejecutados</h6>
                                        <div class="d-flex justify-content-center align-items-end gap-2">
                                            <h2 class="text-info mb-0"><?= (int) $porcentaje_casos_ejecutados; ?>%</h2>
                                            <span class="text-muted fw-semibold" style="font-size: 1rem;">
                                                (<?= $porcentaje_data["ejecutados"]; ?>)
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Gráficos principales -->
                        <div class="row g-3">
                            <div class="col-md-4">
                                <div class="card shadow-sm">
                                    <div class="card-header text-center bg-light fw-semibold">
                                        Seguimiento por Órgano Jurisdiccional
                                    </div>
                                    <div class="card-body">
                                        <div class="chart-container">
                                            <canvas id="chartCasosOrgano"></canvas>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-8">
                                <div class="card shadow-sm">
                                    <div class="card-header text-center bg-light fw-semibold">
                                        Seguimiento por Especialidad
                                    </div>
                                    <div class="card-body">
                                        <div class="chart-container">
                                            <canvas id="chartEspecialidad"></canvas>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- === ANÁLISIS POR FUNCIONALIDAD === -->
                        <div class="row mt-4">
                            <div class="col-12">
                                <div class="card shadow-sm">
                                    <div class="card-header text-center bg-light fw-semibold">
                                        Análisis por Funcionalidad (Requisitos vs Requerimientos)
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <!-- Gráfico -->
                                            <div class="col-md-7">
                                                <div class="chart-container" style="height:400px;">
                                                    <canvas id="chartAnalisisFuncionalidad"></canvas>
                                                </div>
                                            </div>

                                            <!-- Tabla -->
                                            <div class="col-md-5">
                                                <div class="table-responsive">
                                                    <table id="tablaAnalisisFuncionalidad"
                                                        class="table table-sm table-bordered align-middle text-center shadow-sm">
                                                        <thead class="table-light">
                                                            <tr>
                                                                <th>Órgano Jurisdiccional</th>
                                                                <th>Funcionalidad</th>
                                                                <th>Requisitos</th>
                                                                <th>Requerimientos</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php
                                                            $agrupado_tabla = [];
                                                            foreach ($analisis_funcionalidad_limpio as $row) {
                                                                $agrupado_tabla[$row["organo_jurisdiccional"]][] = $row;
                                                            }
                                                            foreach ($agrupado_tabla as $organo => $filas):
                                                                $rowspan = count($filas);
                                                                $primera = true;
                                                                foreach ($filas as $fila): ?>
                                                                    <tr>
                                                                        <?php if ($primera): ?>
                                                                            <td rowspan="<?= $rowspan; ?>"
                                                                                class="fw-semibold bg-light text-start align-middle px-3">
                                                                                <?= htmlspecialchars($organo); ?>
                                                                            </td>
                                                                            <?php $primera = false; ?>
                                                                        <?php endif; ?>
                                                                        <td class="text-start"><?= $fila["funcionalidad"]; ?></td>
                                                                        <td><?= $fila["total_requisitos"]; ?></td>
                                                                        <td><?= $fila["total_requerimientos"]; ?></td>
                                                                    </tr>
                                                                <?php endforeach; endforeach; ?>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- === Resumen Consolidado === -->
                        <div class="row mt-4">
                            <div class="col-12">
                                <div class="card shadow-sm">
                                    <div class="card-header text-center bg-light fw-semibold">
                                        Resumen Consolidado por Especialidad
                                    </div>
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table class="table table-bordered align-middle text-center">
                                                <thead class="table-light">
                                                    <tr>
                                                        <th>Especialidad</th>
                                                        <th>Total Requerimientos</th>
                                                        <th>Total Casos de Prueba</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php foreach ($resumen as $row): ?>
                                                        <tr>
                                                            <td><?= $row["especialidad"]; ?></td>
                                                            <td><?= $row["total_requerimientos"]; ?></td>
                                                            <td><?= $row["total_casos_prueba"]; ?></td>
                                                        </tr>
                                                    <?php endforeach; ?>
                                                </tbody>
                                            
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>


                    </div>

                </div>
                <?php require_once("../html/footer.php") ?>
            </div>
        </div>

        <?php require_once("../html/sidebar.php") ?>
        <div class="rightbar-overlay"></div>
        <?php require_once("../html/js.php") ?>

        <!-- Variables PHP accesibles desde JS -->
        <script>
            // Variables PHP exportadas a JS
            const dataOrgano = {
                ids: <?= json_encode($ids_organo); ?>,
                labels: <?= json_encode($labels_organo); ?>,
                valores: <?= json_encode($valores_organo); ?>
            };


            const dataEspecialidad = {
                labels: <?= json_encode($labels_especialidad); ?>,
                completado: <?= json_encode($data_aprobado); ?>,
                observado: <?= json_encode($data_en_ejecucion); ?>,
                pendiente: <?= json_encode($data_pendiente); ?>
            };

            const analisisData = <?= json_encode($analisis_funcionalidad_limpio); ?>;
        </script>
        <script src="homecolaborador.js"></script>
    </body>

    </html>
    <?php
} else {
    header("Location:" . Conectar::ruta() . "index.php");
}
?>