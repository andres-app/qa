<?php
require_once("../../config/conexion.php");
require_once("../../models/Rol.php");

$rol = new Rol();
$datos = $rol->validar_menu_x_rol($_SESSION["rol_id"], "rp_pruxrq");

if (isset($_SESSION["usu_id"]) && count($datos) > 0) {
?>

<!doctype html>
<html lang="es">
<head>
    <title>Análisis de Casos por Requerimiento</title>
    <?php require_once("../html/head.php"); ?>
</head>

<body>
<div id="layout-wrapper">
    <?php require_once("../html/header.php"); ?>
    <?php require_once("../html/menu.php"); ?>

    <div class="main-content">
        <div class="page-content">
            <div class="container-fluid">

                <!-- Encabezado -->
                <div class="row">
                    <div class="col-12">
                        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                            <h4 class="mb-sm-0">Análisis de Casos por Requerimiento</h4>
                            <div class="page-title-right">
                                <ol class="breadcrumb m-0">
                                    <li class="breadcrumb-item"><a href="#">Reportes</a></li>
                                    <li class="breadcrumb-item active">Casos por Requerimiento</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tabla -->
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h4 class="card-title">Relación de Casos de Prueba por Requerimiento</h4>
                                <button id="btnRefrescar" class="btn btn-outline-primary btn-sm">
                                    <i class="fas fa-sync-alt me-1"></i> Refrescar
                                </button>
                            </div>
                            <div class="card-body">
                                <table id="reporte_casos_req_table" class="table table-striped table-bordered nowrap w-100">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Requerimiento</th>
                                            <th>Cantidad de Casos de Prueba (CP)</th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <?php require_once("../html/footer.php"); ?>
    </div>
</div>

<?php require_once("../html/sidebar.php"); ?>
<div class="rightbar-overlay"></div>
<?php require_once("../html/js.php"); ?>
<script type="text/javascript" src="reporte_casos_por_requerimiento.js"></script>

</body>
</html>

<?php
} else {
    header("Location:" . Conectar::ruta() . "index.php");
}
?>
