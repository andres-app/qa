<?php
require_once("../../config/conexion.php");
require_once("../../models/Rol.php");

// Validar acceso al módulo
$rol = new Rol();
$datos = $rol->validar_menu_x_rol($_SESSION["rol_id"], "actividad");

if (isset($_SESSION["usu_id"]) && count($datos) > 0) {
?>

<!doctype html>
<html lang="es">

<head>
    <title>Gestión de Actividades - QA</title>
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
                                <h4 class="mb-sm-0 font-size-18">Gestión de Actividades</h4>

                                <div class="page-title-right">
                                    <ol class="breadcrumb m-0">
                                        <li class="breadcrumb-item"><a href="javascript:void(0);">QA</a></li>
                                        <li class="breadcrumb-item active">Actividades</li>
                                    </ol>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Contenido principal -->
                    <div class="row">
                        <div class="col-lg-12">

                            <div class="card">
                                <div class="card-header d-flex justify-content-between align-items-center">
                                    <div>
                                        <h4 class="card-title">Listado de Actividades</h4>
                                        <p class="card-title-desc">
                                            Registro y seguimiento de actividades asignadas al equipo QA.
                                        </p>
                                    </div>

                                    <button type="button" id="btnnuevo"
                                        class="btn btn-primary waves-effect waves-light">
                                        <i class="fas fa-plus-circle me-1"></i> Nueva Actividad
                                    </button>
                                </div>

                                <div class="card-body">
                                    <table id="actividad_table"
                                        class="table table-striped table-bordered dt-responsive nowrap w-100">
                                        <thead class="table-light text-center">
                                            <tr>
                                                <th>ID</th>
                                                <th>Colaborador</th>
                                                <th>Actividad</th>
                                                <th>Descripción</th>
                                                <th>Recepción</th>
                                                <th>Inicio</th>
                                                <th>Respuesta</th>
                                                <th>Estado</th>
                                                <th>Avance</th>
                                                <th>Prioridad</th>
                                                <th class="text-center">Acciones</th>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                    </table>
                                </div>

                            </div>

                        </div>
                    </div>

                </div> <!-- container-fluid -->
            </div> <!-- page-content -->

            <?php require_once("../html/footer.php") ?>

        </div> <!-- main-content -->

    </div> <!-- layout-wrapper -->

    <!-- Modal de Registro / Edición -->
    <?php require_once("modal_actividad.php"); ?>

    <?php require_once("../html/sidebar.php") ?>
    <div class="rightbar-overlay"></div>

    <?php require_once("../html/js.php") ?>
    <script type="text/javascript" src="actividad.js"></script>

</body>
</html>

<?php
} else {
    header("Location:" . Conectar::ruta() . "index.php");
}
?>
