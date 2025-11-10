<?php
require_once("../../config/conexion.php");
require_once("../../models/Rol.php");
require_once("../../models/Reporte.php");

$rol = new Rol();
$datos = $rol->validar_menu_x_rol($_SESSION["rol_id"], "seg_org_juri");

$reporte = new Reporte();
$organos = $reporte->get_organos_activos();

if (isset($_SESSION["usu_id"]) && count($datos) > 0) {
?>
<!doctype html>
<html lang="es">
<head>
    <title>Seguimiento Órgano Jurisdiccional</title>
    <?php require_once("../html/head.php"); ?>
</head>
<body>
<div id="layout-wrapper">
    <?php require_once("../html/header.php"); ?>
    <?php require_once("../html/menu.php"); ?>

    <div class="main-content">
        <div class="page-content">
            <div class="container-fluid">
                <div class="page-title-box d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">Seguimiento Órgano Jurisdiccional</h4>
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript:void(0);">QA</a></li>
                        <li class="breadcrumb-item active">Órgano Jurisdiccional</li>
                    </ol>
                </div>

                <div class="card shadow-sm">
                    <div class="card-header bg-light fw-semibold">
                        Reporte Detallado de Casos de Prueba por Órgano
                    </div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label for="filtro_organo" class="form-label">Órgano Jurisdiccional</label>
                                <select id="filtro_organo" class="form-select">
                                    <option value="">Todos</option>
                                    <?php foreach ($organos as $org): ?>
                                        <option value="<?= htmlspecialchars($org['id_organo']); ?>">
                                            <?= htmlspecialchars($org['nombre']); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="col-md-4">
                                <label for="filtro_estado" class="form-label">Estado de Ejecución</label>
                                <select id="filtro_estado" class="form-select">
                                    <option value="Todos">Todos</option>
                                    <option value="Completado">Completado</option>
                                    <option value="Observado">Observado</option>
                                    <option value="Pendiente">Pendiente</option>
                                </select>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table id="requisito_table" class="table table-striped table-bordered nowrap w-100">
                                <thead class="table-light text-center">
                                    <tr>
                                        <th>Órgano Jurisdiccional</th>
                                        <th>Código Req.</th>
                                        <th>Requerimiento</th>
                                        <th>Código Caso</th>
                                        <th>Nombre Caso</th>
                                        <th>Versión</th>
                                        <th>Estado Ejecución</th>
                                        <th>Responsable</th>
                                        <th>Fecha Registro</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
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
<script src="seg_org_juri.js"></script>
</body>
</html>
<?php
} else {
    header("Location:" . Conectar::ruta() . "index.php");
}
?>
