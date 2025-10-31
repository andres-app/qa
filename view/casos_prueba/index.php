<?php
require_once("../../config/conexion.php");
require_once("../../models/Rol.php");

// Validar acceso al módulo
$rol = new Rol();
$datos = $rol->validar_menu_x_rol($_SESSION["rol_id"], "casos_prueba");

if (isset($_SESSION["usu_id"]) && count($datos) > 0) {
?>

<!doctype html>
<html lang="es">
<head>
    <title>Gestión de Casos de Prueba - QA</title>
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
                            <h4 class="mb-sm-0 font-size-18">Gestión de Casos de Prueba</h4>
                            <div class="page-title-right">
                                <ol class="breadcrumb m-0">
                                    <li class="breadcrumb-item"><a href="javascript:void(0);">QA</a></li>
                                    <li class="breadcrumb-item active">Casos de Prueba</li>
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
                                    <h4 class="card-title">Listado de Casos de Prueba</h4>
                                    <p class="card-title-desc">
                                        Vista para registrar, modificar, listar y eliminar casos de prueba asociados a los requerimientos del sistema.
                                    </p>
                                </div>
                                <button type="button" id="btnnuevo" class="btn btn-primary waves-effect waves-light">
                                    <i class="fas fa-plus-circle me-1"></i> Nuevo Caso de Prueba
                                </button>
                            </div>

                            <div class="card-body">
                                <table id="casos_prueba_table" class="table table-striped table-bordered dt-responsive nowrap w-100">
                                    <thead class="table-light">
                                        <tr>
                                            <th>ID</th>
                                            <th>Código</th>
                                            <th>Nombre del Caso</th>
                                            <th>Requerimiento Asociado</th>
                                            <th>Tipo de Prueba</th>
                                            <th>Resultado Esperado</th>
                                            <th>Estado</th>
                                            <th>Versión</th>
                                            <th>Fecha Creación</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

            </div><!-- container-fluid -->
        </div><!-- page-content -->

        <?php require_once("../html/footer.php") ?>
    </div><!-- main-content -->
</div><!-- layout-wrapper -->

<!-- Modal de Registro / Edición -->
<?php require_once("modal_casos.php"); ?>

<?php require_once("../html/sidebar.php") ?>
<div class="rightbar-overlay"></div>

<?php require_once("../html/js.php") ?>
<script type="text/javascript" src="casos_prueba.js"></script>

</body>
</html>

<?php
} else {
    header("Location:" . Conectar::ruta() . "index.php");
}
?>
