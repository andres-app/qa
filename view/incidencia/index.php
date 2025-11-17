<?php
require_once("../../config/conexion.php");
require_once("../../models/Rol.php");

// Validar acceso al módulo
$rol = new Rol();
$datos = $rol->validar_menu_x_rol($_SESSION["rol_id"], "incidencia");

if (isset($_SESSION["usu_id"]) && count($datos) > 0) {
    ?>

    <!doctype html>
    <html lang="es">

    <head>
        <title>Gestión de Incidencias - QA</title>
        <?php require_once("../html/head.php") ?>
    </head>

    <body>
        <div id="layout-wrapper">
            <?php require_once("../html/header.php") ?>
            <?php require_once("../html/menu.php") ?>

            <div class="main-content">
                <div class="page-content">
                    <div class="container-fluid">

                        <!-- Título y breadcrumb -->
                        <div class="row">
                            <div class="col-12">
                                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                                    <h4 class="mb-sm-0 font-size-18">Gestión de Incidencias</h4>
                                    <div class="page-title-right">
                                        <ol class="breadcrumb m-0">
                                            <li class="breadcrumb-item"><a href="javascript:void(0);">QA</a></li>
                                            <li class="breadcrumb-item active">Incidencias</li>
                                        </ol>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Contenido principal -->
                        <!-- Contenido principal -->
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="card">

                                    <!-- ENCABEZADO -->
                                    <div class="card-header d-flex justify-content-between align-items-center">
                                        <div>
                                            <h4 class="card-title">Listado de Incidencias</h4>
                                            <p class="card-title-desc">
                                                Registro y control de incidencias reportadas por QA y atendidas por el
                                                equipo de Desarrollo.
                                            </p>
                                        </div>

                                        <div class="d-flex gap-2">
                                            <!-- BOTÓN DE FILTROS -->
                                            <button class="btn btn-outline-primary btn-sm" id="btnFiltros">
                                                <i class="bx bx-filter-alt"></i> Filtros
                                            </button>

                                            <!-- NUEVA INCIDENCIA -->
                                            <button type="button" id="btnnuevo"
                                                class="btn btn-primary waves-effect waves-light">
                                                <i class="fas fa-plus-circle me-1"></i> Nueva Incidencia
                                            </button>
                                        </div>
                                    </div>

                                    <!-- CARD BODY -->
                                    <div class="card-body">

                                        <!-- PANEL DE FILTROS — DENTRO DEL CARD-BODY -->
                                        <div id="panelFiltros" class="card p-3 mb-3 shadow-sm" style="display:none;">
                                            <div class="row g-3">

                                                <div class="col-md-4">
                                                    <label class="form-label small">Documentación</label>
                                                    <select id="filtro_documentacion"
                                                        class="form-select form-select-sm"></select>
                                                </div>

                                                <div class="col-md-3">
                                                    <label class="form-label small">Módulo</label>
                                                    <select id="filtro_modulo" class="form-select form-select-sm"></select>
                                                </div>

                                                <div class="col-md-2">
                                                    <label class="form-label small">Prioridad</label>
                                                    <select id="filtro_prioridad" class="form-select form-select-sm">
                                                        <option value="">Todos</option>
                                                        <option value="Alta">Alta</option>
                                                        <option value="Media">Media</option>
                                                        <option value="Baja">Baja</option>
                                                    </select>
                                                </div>

                                                <div class="col-md-3">
                                                    <label class="form-label small">Tipo</label>
                                                    <select id="filtro_tipo" class="form-select form-select-sm">
                                                        <option value="">Todos</option>
                                                        <option value="Documentacion">Documentacion</option>
                                                        <option value="Mejora">Validación</option>
                                                        <option value="Consulta">Funcional</option>
                                                    </select>
                                                </div>

                                                <div class="col-md-3">
                                                    <label class="form-label small">Actividad</label>
                                                    <select id="filtro_actividad"
                                                        class="form-select form-select-sm"></select>
                                                </div>

                                                <div class="col-md-3">
                                                    <label class="form-label small">Estado</label>
                                                    <select id="filtro_estado" class="form-select form-select-sm">
                                                        <option value="">Todos</option>
                                                        <option value="Pendiente">Pendiente</option>
                                                        <option value="Resuelto">Resuelto</option>
                                                    </select>
                                                </div>

                                                <!-- Fecha lado a lado -->
                                                <div class="col-md-4">
                                                    <label class="form-label small">Fecha (rango)</label>
                                                    <div class="row g-1">
                                                        <div class="col-6">
                                                            <input type="date" id="filtro_fecha_desde"
                                                                class="form-control form-control-sm">
                                                        </div>
                                                        <div class="col-6">
                                                            <input type="date" id="filtro_fecha_hasta"
                                                                class="form-control form-control-sm">
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>

                                        <!-- TABLA -->
                                        <table id="incidencia_table"
                                            class="table table-striped table-bordered dt-responsive nowrap w-100">
                                            <thead class="table-light text-center">
                                                <tr>
                                                    <th>ID.</th>
                                                    <th>N° Inc.</th>
                                                    <th>Actividad</th>
                                                    <th>Documentación</th>
                                                    <th>Módulo</th>
                                                    <th>Descripción</th>
                                                    <th>Analista</th>
                                                    <th>Prioridad</th>
                                                    <th>Tipo</th>
                                                    <th>Fecha Registro</th>
                                                    <th>Estado</th>
                                                    <th class="text-center">Acciones</th>
                                                </tr>
                                            </thead>
                                            <tbody></tbody>
                                        </table>

                                    </div> <!-- END CARD BODY -->

                                </div> <!-- END CARD -->

                            </div>
                        </div>


                    </div><!-- container-fluid -->
                </div><!-- page-content -->

                <?php require_once("../html/footer.php") ?>
            </div><!-- main-content -->
        </div><!-- layout-wrapper -->

        <!-- Modal de Registro / Edición -->
        <?php require_once("modal_incidencia.php"); ?>

        <?php require_once("../html/sidebar.php") ?>
        <div class="rightbar-overlay"></div>

        <?php require_once("../html/js.php") ?>
        <script type="text/javascript" src="incidencia.js"></script>

    </body>

    </html>

    <?php
} else {
    header("Location:" . Conectar::ruta() . "index.php");
}
?>