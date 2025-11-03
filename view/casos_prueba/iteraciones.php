<?php
require_once("../../config/conexion.php");
require_once("../../models/Rol.php");
require_once("../../models/Casos_prueba.php");

$rol = new Rol();
$datos = $rol->validar_menu_x_rol($_SESSION["rol_id"], "casos_prueba");

if (isset($_SESSION["usu_id"]) && count($datos) > 0) {

    $id_caso = isset($_GET["id"]) ? intval($_GET["id"]) : 0;
    if ($id_caso <= 0) {
        header("Location:" . Conectar::ruta() . "view/casos_prueba/index.php");
        exit;
    }

    // Traer datos básicos del caso (código, nombre, estado)
    $cp = new Casos_prueba();
    $info = $cp->get_caso_por_id($id_caso);
?>
<!doctype html>
<html lang="es">
<head>
    <title>Iteraciones del Caso de Prueba</title>
    <?php require_once("../html/head.php"); ?>
</head>
<body>
<div id="layout-wrapper">
    <?php require_once("../html/header.php"); ?>
    <?php require_once("../html/menu.php"); ?>

    <div class="main-content">
        <div class="page-content">
            <div class="container-fluid">

                <!-- Breadcrumb -->
                <div class="row">
                    <div class="col-12">
                        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                            <h4 class="mb-sm-0">Iteraciones del Caso de Prueba</h4>
                            <div class="page-title-right">
                                <ol class="breadcrumb m-0">
                                    <li class="breadcrumb-item"><a href="index.php">Casos de Prueba</a></li>
                                    <li class="breadcrumb-item active">Iteraciones</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Cabecera del caso -->
                <div class="card mb-3">
                    <div class="card-body d-flex flex-wrap gap-4 align-items-center">
                        <div>
                            <div class="text-muted">Código</div>
                            <div class="fs-5 fw-semibold"><?= htmlspecialchars($info["codigo"] ?? "-") ?></div>
                        </div>
                        <div class="flex-grow-1">
                            <div class="text-muted">Nombre del Caso</div>
                            <div class="fw-semibold"><?= htmlspecialchars($info["nombre"] ?? "-") ?></div>
                        </div>
                        <div>
                            <div class="text-muted">Estado del Caso</div>
                            <span id="badge_estado_caso" class="badge bg-secondary">
                                <?= htmlspecialchars($info["estado"] ?? "Pendiente") ?>
                            </span>
                        </div>
                        <div class="ms-auto">
                            <a href="index.php" class="btn btn-outline-secondary">
                                <i class="bx bx-arrow-back"></i> Volver
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Formulario de nueva iteración -->
                <div class="card mb-3">
                    <div class="card-header fw-semibold">Registrar Iteración</div>
                    <div class="card-body">
                        <form id="form_iteracion">
                            <input type="hidden" id="id_caso" value="<?= $id_caso ?>">

                            <div class="row g-3">
                                <div class="col-md-4">
                                    <label class="form-label">Ejecutor</label>
                                    <select class="form-select" id="ejecutor_nombre" name="ejecutor_nombre" required>
                                <option value="">Seleccione...</option>
                                <option value="Andres Silva" <?= (isset($_SESSION['usu_nombre']) && stripos($_SESSION['usu_nombre'], 'Andres Silva') !== false) ? 'selected' : '' ?>>Andres Silva</option>
                                <option value="Lucero Sifuentes" <?= (isset($_SESSION['usu_nombre']) && stripos($_SESSION['usu_nombre'], 'Lucero Sifuentes') !== false) ? 'selected' : '' ?>>Lucero Sifuentes</option>
                                <option value="Nancy Maza" <?= (isset($_SESSION['usu_nombre']) && stripos($_SESSION['usu_nombre'], 'Nancy Maza') !== false) ? 'selected' : '' ?>>Nancy Maza</option>
                                <option value="Christian Candiotti" <?= (isset($_SESSION['usu_nombre']) && stripos($_SESSION['usu_nombre'], 'Christian Candiotti') !== false) ? 'selected' : '' ?>>Christian Candiotti</option>
                            </select>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Fecha de ejecución</label>
                                    <input type="datetime-local" id="fecha_ejecucion" class="form-control">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Estado de la iteración</label>
                                    <select id="resultado" class="form-select">
                                        <option value="">Seleccione...</option>
                                        <option>Ejecutado</option>
                                        <option>Observado</option>
                                    </select>
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Comentario</label>
                                    <textarea id="comentario" class="form-control" rows="3"
                                        placeholder="Detalle de la ejecución u observación"></textarea>
                                </div>
                            </div>

                            <div class="mt-3">
                                <button type="submit" class="btn btn-primary">
                                    <i class="bx bx-save"></i> Guardar iteración
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Historial / tabla de iteraciones -->
                <div class="card">
                    <div class="card-header fw-semibold">Historial de Iteraciones</div>
                    <div class="card-body">
                        <table id="iteraciones_table" class="table table-striped table-bordered w-100">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Fecha</th>
                                    <th>Ejecutor</th>
                                    <th>Estado</th>
                                    <th>Comentario</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>

            </div><!-- container -->
        </div><!-- page-content -->

        <?php require_once("../html/footer.php"); ?>
    </div><!-- main-content -->
</div><!-- layout-wrapper -->

<?php require_once("../html/sidebar.php"); ?>
<div class="rightbar-overlay"></div>

<?php require_once("../html/js.php"); ?>
<script src="iteraciones.js"></script>

</body>
</html>

<?php } else {
    header("Location:" . Conectar::ruta() . "index.php");
} ?>
