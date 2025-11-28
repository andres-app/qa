<?php
require_once("../../config/conexion.php");
require_once("../../models/Reporte.php");
require_once("../../models/Requerimiento.php");
require_once("../../models/Rol.php");
require_once("../../models/Incidencia.php");
require_once("../../models/Homecolaborador.php"); // NUEVO

// Validar acceso al módulo
$rol = new Rol();
$datos = $rol->validar_menu_x_rol($_SESSION["rol_id"], "iniciocolaborador");

if (isset($_SESSION["usu_id"]) && count($datos) > 0) {

    // ================================
    //  OBTENER TODOS LOS DATOS DEL DASHBOARD DESDE EL MODELO
    // ================================
    $data = Homecolaborador::obtenerDatosDashboard();

    // Crear variables: $total_requerimientos, $total_casos_prueba, $porcentaje_casos_ejecutados, etc.
    extract($data);
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

                    <!-- ============================= -->
                    <!-- KPIs SUPERIORES -->
                    <!-- ============================= -->
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
                                        <h2 class="text-info mb-0"><?= (int)$porcentaje_casos_ejecutados; ?>%</h2>
                                        <span class="text-muted fw-semibold" style="font-size: 1rem;">
                                            (<?= $porcentaje_data["ejecutados"]; ?>)
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- ============================= -->
                    <!-- ANÁLISIS DE CASOS DE PRUEBA -->
                    <!-- ============================= -->
                    <div class="card shadow-sm border-0 mt-5">
                        <div class="card-header bg-dark text-white py-3">
                            <h4 class="mb-0 text-white">
                                <i class="fas fa-tasks me-2"></i> Análisis de Casos de Prueba
                            </h4>
                        </div>

                        <div class="card-body">
                            <div class="row g-3">

                                <!-- Casos por Órgano -->
                                <div class="col-md-4">
                                    <div class="card shadow-sm h-100">
                                        <div class="card-header text-center bg-light fw-semibold">
                                            Casos de Prueba por Órgano Jurisdiccional
                                        </div>
                                        <div class="card-body">
                                            <div class="chart-container d-flex justify-content-center align-items-center"
                                                 style="height: 380px;">
                                                <canvas id="chartCasosOrgano"></canvas>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Casos por Especialidad -->
                                <div class="col-md-8">
                                    <div class="card shadow-sm h-100">
                                        <div class="card-header text-center bg-light fw-semibold">
                                            Casos de Prueba por Especialidad
                                        </div>
                                        <div class="card-body">
                                            <div class="chart-container-lg">
                                                <canvas id="chartEspecialidad"></canvas>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>

                    <!-- ============================= -->
                    <!-- ANÁLISIS POR FUNCIONALIDAD -->
                    <!-- ============================= -->
                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="card shadow-sm">
                                <div class="card-header text-center bg-light fw-semibold">
                                    Análisis por Funcionalidad (Requisitos vs Requerimientos)
                                </div>
                                <div class="card-body">
                                    <div class="row">

                                        <div class="col-md-7">
                                            <div class="chart-container-lg">
                                                <canvas id="chartAnalisisFuncionalidad"></canvas>
                                            </div>
                                        </div>

                                        <div class="col-md-5 d-flex justify-content-center align-items-center">
                                            <div class="table-responsive">
                                                <table class="table table-sm table-bordered align-middle text-center shadow-sm">
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
                                                                        <td rowspan="<?= $rowspan; ?>" class="fw-semibold bg-light text-start align-middle px-3">
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

                    <!-- ============================= -->
                    <!-- RESUMEN CONSOLIDADO -->
                    <!-- ============================= -->
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

                    <!-- =================================================== -->
                    <!-- 🎯 NUEVO DASHBOARD DE INCIDENCIAS -->
                    <!-- =================================================== -->

                    <div class="card shadow-sm border-0 mt-5">
                        <div class="card-header bg-dark text-white py-3">
                            <h4 class="mb-0 text-white">
                                <i class="fas fa-chart-pie me-2"></i> Análisis de Incidencias
                            </h4>
                        </div>

                        <div class="card-body">
                            <div class="row mt-3">

                                <!-- Incidencias por Documentación -->
                                <div class="col-md-6 mb-4">
                                    <div class="card shadow-sm h-100">
                                        <div class="card-header bg-light text-center fw-semibold">
                                            Incidencias por Documentación
                                        </div>
                                        <div class="card-body">
                                            <div class="chart-container">
                                                <canvas id="chartDocumento"></canvas>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Incidencias por Módulo -->
                                <div class="col-md-6 mb-4">
                                    <div class="card shadow-sm h-100">
                                        <div class="card-header bg-light text-center fw-semibold">
                                            Distribución de Incidencias por Módulo
                                        </div>
                                        <div class="card-body">
                                            <div class="chart-container">
                                                <canvas id="chartModulo"></canvas>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Incidencias por Documento y Analista -->
                                <div class="col-md-6 mb-4">
                                    <div class="card shadow-sm h-100">
                                        <div class="card-header bg-light text-center fw-semibold">
                                            Incidencias por Documento y Analista
                                        </div>

                                        <div class="card-body p-0">
                                            <div class="table-responsive" style="max-height:350px; overflow-y:auto;">
                                                <table id="tablaDocAnalista"
                                                       class="table table-hover table-striped align-middle mb-0">
                                                    <thead class="table-dark">
                                                        <tr>
                                                            <th>Documento</th>
                                                            <th>Analista</th>
                                                            <th>N°</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody></tbody>
                                                    <tfoot class="fw-bold bg-light">
                                                        <tr>
                                                            <td colspan="2" class="text-end">TOTAL</td>
                                                            <td id="totalGeneral" class="text-primary text-center"></td>
                                                        </tr>
                                                    </tfoot>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Incidencias por Mes -->
                                <div class="card shadow-sm mt-4 mb-5">
                                    <div class="card-header bg-light text-center fw-semibold">
                                        Evolución de Incidencias por Mes
                                    </div>
                                    <div class="card-body">
                                        <div class="chart-container-lg">
                                            <canvas id="chartMes"></canvas>
                                        </div>
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

    <script>
        function formatearMeses(periodos) {
            const nombres = [
                "Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio",
                "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"
            ];

            return periodos.map(p => {
                const [anio, mes] = p.split("-");
                return `${nombres[parseInt(mes) - 1]} ${anio}`;
            });
        }

        const dataOrgano = {
            ids: <?= json_encode($ids_organo); ?>,
            labels: <?= json_encode($labels_organo); ?>,
            valores: <?= json_encode($valores_organo); ?>
        };

        const dataEspecialidad = {
            labels: <?= json_encode($labels_especialidad); ?>,
            completado: <?= json_encode(array_map('intval', $data_completado)); ?>,
            observado: <?= json_encode(array_map('intval', $data_observado)); ?>,
            pendiente: <?= json_encode(array_map('intval', $data_pendiente)); ?>
        };

        const analisisData = <?= json_encode($analisis_funcionalidad_limpio); ?>;

        const docLabels = <?= json_encode(array_column($inc_por_doc, "documento")); ?>;
        const docData = <?= json_encode(array_column($inc_por_doc, "total")); ?>;

        const modLabels = <?= json_encode(array_column($inc_por_mod, "modulo")); ?>;
        const modData = <?= json_encode(array_column($inc_por_mod, "total")); ?>;

        const mesLabels = <?= json_encode(array_column($inc_por_mes, "periodo")); ?>;
        const mesData = <?= json_encode(array_column($inc_por_mes, "total")); ?>;
        const mesLabelsBonitos = formatearMeses(mesLabels);

        const docAnalistaData = <?= json_encode($inc_doc_analista); ?>;
    </script>

    <script src="homecolaborador.js"></script>
</body>

</html>

<?php
} else {
    header("Location:" . Conectar::ruta() . "index.php");
}
?>
    