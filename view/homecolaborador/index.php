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

    // Limpiar datos: eliminar filas sin órgano jurisdiccional y ordenar
    $analisis_funcionalidad = array_filter($analisis_funcionalidad, function ($r) {
        return !empty($r["organo_jurisdiccional"]);
    });

    // Agrupar por órgano y funcionalidad (para evitar duplicados)
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

    // Convertir el agrupado a formato plano
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

    // Ordenar jerárquicamente los órganos jurisdiccionales
    $ordenJerarquico = [
        "Sala Suprema",
        "Sala Superior",
        "Juzgado Especializado",
        "Juzgado Mixto",
        "Juzgado de Paz Letrado",
        "Juzgado de Paz"
    ];

    // Ordenar los datos según jerarquía
    usort($analisis_funcionalidad_limpio, function ($a, $b) use ($ordenJerarquico) {
        $posA = array_search($a["organo_jurisdiccional"], $ordenJerarquico);
        $posB = array_search($b["organo_jurisdiccional"], $ordenJerarquico);
        return $posA <=> $posB;
    });



    // === Casos por órgano jurisdiccional ===
    $casos_por_organo = $reporte->get_casos_por_organo_jurisdiccional();
    $labels_organo = [];
    $valores_organo = [];

    foreach ($casos_por_organo as $row) {
        $labels_organo[] = $row["organo_jurisdiccional"];
        $valores_organo[] = (int) $row["total_casos"];
    }


    // === Seguimiento por especialidad ===
    $seguimiento_especialidad = $reporte->get_seguimiento_por_especialidad();
    $labels_especialidad = [];
    $data_aprobado = [];
    $data_en_ejecucion = [];
    $data_pendiente = [];

    if (is_array($seguimiento_especialidad)) {
        foreach ($seguimiento_especialidad as $row) {
            $labels_especialidad[] = $row["especialidad"];
            $data_aprobado[] = (int) ($row["completado"] ?? 0);
            $data_en_ejecucion[] = (int) ($row["en_ejecucion"] ?? 0);
            $data_pendiente[] = (int) ($row["pendiente"] ?? 0);
        }
    }

    // === Resumen consolidado ===
    $resumen = $reporte->get_resumen_por_especialidad();
    if (!is_array($resumen)) {
        $resumen = [];
    }

    ?>
    <!doctype html>
    <html lang="es">

    <head>
        <title>Análisis de Casos por Requerimiento</title>
        <?php require_once("../html/head.php") ?>
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <style>
            /* === Contenedores gráficos uniformes === */
            .chart-container {
                position: relative;
                width: 100%;
                height: 340px;
            }

            /* === KPI Cards === */
            .kpi-card .card-body {
                padding: 1.5rem;
            }

            .kpi-card h6 {
                color: #6b7280;
                margin-bottom: 0.5rem;
            }

            .kpi-card h2 {
                font-weight: 700;
                color: #1f2937;
            }

            .kpi-card h2.text-info {
                color: #2563eb !important;
            }

            .card {
                border-radius: 10px;
                border: 1px solid #e5e7eb;
                box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
            }

            /* === Tabla: Análisis por Funcionalidad === */
            #tablaAnalisisFuncionalidad {
                width: 100%;
                border-collapse: separate;
                border-spacing: 0;
                font-size: 0.9rem;
                color: #374151;
                background: #fff;
                border-radius: 10px;
                overflow: hidden;
                box-shadow: 0 1px 3px rgba(0, 0, 0, 0.08);
                margin-top: 10px;
            }

            #tablaAnalisisFuncionalidad thead {
                background: #f3f4f6;
                border-bottom: 2px solid #e5e7eb;
            }

            #tablaAnalisisFuncionalidad thead th {
                text-transform: uppercase;
                font-size: 0.8rem;
                font-weight: 600;
                letter-spacing: 0.05em;
                padding: 10px;
                text-align: center;
                color: #1f2937;
            }

            #tablaAnalisisFuncionalidad tbody tr:nth-child(even) {
                background-color: #f9fafb;
            }

            #tablaAnalisisFuncionalidad tbody tr:hover {
                background-color: #f1f5f9;
                transition: all 0.15s ease-in-out;
            }

            #tablaAnalisisFuncionalidad td {
                padding: 10px;
                text-align: center;
                border-top: 1px solid #f0f0f0;
            }

            #tablaAnalisisFuncionalidad tbody tr:last-child td {
                border-bottom: none;
            }

            /* Quitar márgenes extra del DataTable */
            .dataTables_wrapper {
                margin-top: 0 !important;
                padding-top: 0 !important;
            }

            /* Mejorar header de la card */
            .card-header {
                background: #f8fafc;
                border-bottom: 1px solid #e5e7eb;
                color: #334155;
                font-weight: 600;
                letter-spacing: 0.03em;
                text-transform: uppercase;
                font-size: 0.9rem;
            }

            /* Ajustes responsivos */
            @media (max-width: 768px) {

                #tablaAnalisisFuncionalidad th,
                #tablaAnalisisFuncionalidad td {
                    font-size: 0.8rem;
                    padding: 8px;
                }

                .chart-container {
                    height: 280px;
                }


            }

            /* Tabla de análisis por funcionalidad jerárquica */
            #tablaAnalisisFuncionalidad {
                width: 100%;
                font-size: 0.88rem;
                border-collapse: separate;
                border-spacing: 0;
                border-radius: 8px;
                overflow: hidden;
            }

            #tablaAnalisisFuncionalidad thead th {
                background: #f3f4f6;
                color: #374151;
                text-transform: uppercase;
                font-weight: 600;
                font-size: 0.8rem;
                border-bottom: 2px solid #e5e7eb;
            }

            #tablaAnalisisFuncionalidad tbody tr.table-primary {
                background-color: #e0f2fe !important;
            }

            #tablaAnalisisFuncionalidad tbody td {
                padding: 8px 10px;
                border-color: #f1f5f9;
            }

            #tablaAnalisisFuncionalidad tbody tr:hover td {
                background-color: #f8fafc;
                transition: 0.15s ease-in-out;
            }

            #tablaAnalisisFuncionalidad .text-start {
                text-align: left !important;
            }
        </style>

    </head>

    <body>
        <div id="layout-wrapper">
            <?php require_once("../html/header.php") ?>
            <?php require_once("../html/menu.php") ?>

            <div class="main-content">
                <div class="page-content">
                    <div class="container-fluid">

                        <h4 class="mb-4  fw-bold text-secondary">
                            Dashboard - Matriz General
                        </h4>

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
                                                            // Agrupar datos por órgano jurisdiccional
                                                            $agrupado_tabla = [];
                                                            foreach ($analisis_funcionalidad_limpio as $row) {
                                                                $org = $row["organo_jurisdiccional"];
                                                                if (!isset($agrupado_tabla[$org])) {
                                                                    $agrupado_tabla[$org] = [];
                                                                }
                                                                $agrupado_tabla[$org][] = $row;
                                                            }

                                                            // Renderizado con celdas combinadas
                                                            foreach ($agrupado_tabla as $organo => $filas):
                                                                $rowspan = count($filas);
                                                                $primera = true;
                                                                foreach ($filas as $fila):
                                                                    ?>
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
                                                <tfoot class="fw-bold table-light">
                                                    <tr>
                                                        <td>Total General</td>
                                                        <td>
                                                            <?= array_sum(array_column($resumen, 'total_requerimientos')); ?>
                                                        </td>
                                                        <td>
                                                            <?= array_sum(array_column($resumen, 'total_casos_prueba')); ?>
                                                        </td>
                                                    </tr>
                                                </tfoot>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>


                        <!-- Gráficos secundarios -->
                        <div class="row mt-4 g-3">
                            <div class="col-md-6">
                                <div class="card shadow-sm">
                                    <div class="card-header text-center bg-light fw-semibold">
                                        Línea de Tiempo de Ejecución
                                    </div>
                                    <div class="card-body">
                                        <div class="chart-container">
                                            <canvas id="chartLineaTiempo"></canvas>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="card shadow-sm">
                                    <div class="card-header text-center bg-light fw-semibold">
                                        Avance por Requerimiento
                                    </div>
                                    <div class="card-body">
                                        <div class="chart-container">
                                            <canvas id="chartAvance"></canvas>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>



                        <!-- Tabla Detallada -->
                        <div class="row mt-4 g-3">
                            <div class="col-12">
                                <div class="card shadow-sm">
                                    <div class="card-header text-center bg-light fw-semibold">
                                        Tabla Detallada de Seguimiento
                                    </div>
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table class="table table-bordered align-middle table-hover">
                                                <thead class="table-light">
                                                    <tr>
                                                        <th>Código Requerimiento</th>
                                                        <th>Nombre Requerimiento</th>
                                                        <th>Código Caso de Prueba</th>
                                                        <th>Estado</th>
                                                        <th>Fecha</th>
                                                        <th>Observaciones</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td>3.2.1.2</td>
                                                        <td>Registro</td>
                                                        <td>PR-001</td>
                                                        <td>PENDIENTE</td>
                                                        <td>12/01</td>
                                                        <td>En revisión</td>
                                                    </tr>
                                                    <tr>
                                                        <td>3.2.1.3</td>
                                                        <td>Consultas</td>
                                                        <td>PR-002</td>
                                                        <td>Observado</td>
                                                        <td>13/01</td>
                                                        <td>Pruebas en curso</td>
                                                    </tr>
                                                    <tr>
                                                        <td>3.2.1.5</td>
                                                        <td>Gestión</td>
                                                        <td>PR-003</td>
                                                        <td>COMPLETADO</td>
                                                        <td>14/01</td>
                                                        <td>Validado</td>
                                                    </tr>
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

        <!-- ========== Chart.js Scripts ========== -->
        <script>
            function gradient(ctx, color1, color2) {
                const grad = ctx.createLinearGradient(0, 0, 0, 300);
                grad.addColorStop(0, color1);
                grad.addColorStop(1, color2);
                return grad;
            }

            // === GRÁFICO: Seguimiento por Órgano Jurisdiccional (PIE) ===
            const ctxOrg = document.getElementById('chartCasosOrgano').getContext('2d');

            new Chart(ctxOrg, {
                type: 'pie',
                data: {
                    labels: <?= json_encode($labels_organo); ?>,
                    datasets: [{
                        data: <?= json_encode($valores_organo); ?>,
                        backgroundColor: [
                            'rgba(96, 165, 250, 0.8)',   // Azul medio
                            'rgba(147, 197, 253, 0.8)',  // Azul claro
                            'rgba(191, 219, 254, 0.8)',  // Azul muy claro
                            'rgba(219, 234, 254, 0.8)'   // Celeste pastel
                        ],
                        borderColor: '#ffffff',
                        borderWidth: 2
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: { color: '#4b5563', boxWidth: 15, padding: 15 }
                        },
                        tooltip: {
                            callbacks: {
                                label: function (context) {
                                    const label = context.label || '';
                                    const value = context.raw || 0;
                                    const total = context.chart._metasets[0].total;
                                    const percentage = ((value / total) * 100).toFixed(1);
                                    return `${label}: ${value} (${percentage}%)`;
                                }
                            }
                        }
                    }
                }
            });


            // === GRÁFICO: Seguimiento por Especialidad (DINÁMICO) ===
            const ctxEsp = document.getElementById('chartEspecialidad').getContext('2d');
            new Chart(ctxEsp, {
                type: 'bar',
                data: {
                    labels: <?= json_encode($labels_especialidad); ?>,
                    datasets: [
                        {
                            label: 'Completado',
                            data: <?= json_encode($data_aprobado); ?>,
                            backgroundColor: 'rgba(96, 165, 250, 0.8)'
                        },
                        {
                            label: 'Observado',
                            data: <?= json_encode($data_en_ejecucion); ?>,
                            backgroundColor: 'rgba(147, 197, 253, 0.8)'
                        },
                        {
                            label: 'Pendiente',
                            data: <?= json_encode($data_pendiente); ?>,
                            backgroundColor: 'rgba(219, 234, 254, 0.9)'
                        }
                    ]
                },
                options: {
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: { color: '#4b5563' }
                        }
                    },
                    scales: {
                        x: { stacked: true, ticks: { color: '#6b7280' } },
                        y: { stacked: true, ticks: { color: '#6b7280', precision: 0 } }
                    },
                    responsive: true,
                    maintainAspectRatio: false
                }
            });


            // === GRÁFICO: Línea de Tiempo ===
            const ctxLinea = document.getElementById('chartLineaTiempo').getContext('2d');
            new Chart(ctxLinea, {
                type: 'line',
                data: {
                    labels: ['1-Jul', '1-Ago', '1-Sep', '1-Oct', '1-Nov', '1-Dic'],
                    datasets: [{
                        label: 'Requerimientos',
                        data: [10, 25, 40, 55, 75, 100],
                        borderColor: '#60a5fa',
                        backgroundColor: 'rgba(96, 165, 250, 0.2)',
                        fill: true,
                        tension: 0.4,
                        pointRadius: 4,
                        pointBackgroundColor: '#3b82f6'
                    }]
                },
                options: {
                    plugins: { legend: { display: false } },
                    scales: {
                        x: { ticks: { color: '#6b7280' } },
                        y: { ticks: { color: '#6b7280' } }
                    }
                }
            });

            // === GRÁFICO: Avance por Requerimiento ===
            const ctxAvance = document.getElementById('chartAvance').getContext('2d');
            new Chart(ctxAvance, {
                type: 'bar',
                data: {
                    labels: ['Startvi', 'Consultas', 'Registro'],
                    datasets: [
                        { label: 'Completado', data: [5, 8, 6], backgroundColor: gradient(ctxAvance, '#93c5fd', '#bfdbfe') },
                        { label: 'Observado', data: [10, 7, 8], backgroundColor: gradient(ctxAvance, '#a5c8dd', '#dbeafe') },
                        { label: 'Pendiente', data: [3, 5, 2], backgroundColor: gradient(ctxAvance, '#d1d5db', '#e5e7eb') }
                    ]
                },
                options: {
                    plugins: { legend: { position: 'bottom', labels: { color: '#4b5563' } } },
                    scales: {
                        x: { stacked: true, ticks: { color: '#6b7280' } },
                        y: { stacked: true, ticks: { color: '#6b7280' } }
                    }
                }
            });

            // === GRÁFICO: Análisis por Funcionalidad Limpio (Jerárquico) ===
            const ctxFunc = document.getElementById('chartAnalisisFuncionalidad').getContext('2d');
            const analisisData = <?= json_encode($analisis_funcionalidad_limpio); ?>;

            // Obtener órganos únicos en orden jerárquico
            const organos = [...new Set(analisisData.map(r => r.organo_jurisdiccional))];
            const funcionalidades = [...new Set(analisisData.map(r => r.funcionalidad))];

            // Generar datasets por funcionalidad (ej. FC, FP)
            const colores = [
                '#60a5fa', '#34d399', '#fbbf24', '#f87171', '#a78bfa'
            ];
            const datasets = funcionalidades.map((func, i) => ({
                label: func,
                data: organos.map(o => {
                    const match = analisisData.find(r => r.organo_jurisdiccional === o && r.funcionalidad === func);
                    return match ? match.total_requerimientos : 0;
                }),
                backgroundColor: colores[i % colores.length],
                borderRadius: 6,
                barPercentage: 0.6
            }));

            new Chart(ctxFunc, {
                type: 'bar',
                data: {
                    labels: organos,
                    datasets: datasets
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: { color: '#374151', boxWidth: 12, padding: 10 }
                        },
                        title: {
                            display: true,
                            text: 'Requerimientos por Órgano Jurisdiccional y Funcionalidad',
                            color: '#1f2937',
                            font: { size: 14, weight: '600' }
                        },
                        tooltip: {
                            callbacks: {
                                label: function (ctx) {
                                    return `${ctx.dataset.label}: ${ctx.raw} requerimientos`;
                                }
                            }
                        }
                    },
                    scales: {
                        x: {
                            ticks: { color: '#6b7280', font: { size: 11 } },
                            grid: { display: false }
                        },
                        y: {
                            beginAtZero: true,
                            ticks: { color: '#6b7280', stepSize: 5 },
                            grid: { color: '#f3f4f6' }
                        }
                    }
                }
            });



        </script>
    </body>

    </html>
    <?php
} else {
    header("Location:" . Conectar::ruta() . "index.php");
}
?>