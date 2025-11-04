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
                        <div class="card mb-3 border-0 shadow-sm" style="background: #f8f9fc;">
                            <div
                                class="card-body d-flex justify-content-between align-items-center flex-wrap gap-3 border-start border-4 border-primary rounded-3">

                                <!-- Sección izquierda -->
                                <div class="d-flex align-items-center flex-wrap gap-4">

                                    <!-- Caso de prueba -->
                                    <div>
                                        <div class="text-muted small fw-semibold">Caso de prueba</div>
                                        <div class="fs-5 fw-semibold text-primary mb-0">
                                            <?= htmlspecialchars($info["codigo"] ?? "-") ?>
                                        </div>
                                    </div>

                                    <div class="text-muted fs-4">|</div>

                                    <!-- Nombre del caso -->
                                    <div>
                                        <div class="text-muted small fw-semibold">Nombre del Caso</div>
                                        <div class="fw-semibold text-dark mb-0">
                                            <?= htmlspecialchars($info["nombre"] ?? "-") ?>
                                        </div>
                                    </div>

                                    <div class="text-muted fs-4">|</div>

                                    <!-- Código del requerimiento -->
                                    <div>
                                        <div class="text-muted small fw-semibold">Cod. Requerimiento</div>
                                        <div class="fw-semibold mb-0">
                                            <?php if (!empty($info["requerimiento_codigo"])): ?>
                                                <span class="badge bg-light text-primary border px-2 py-1">
                                                    <?= htmlspecialchars($info["requerimiento_codigo"]) ?>
                                                </span>
                                            <?php else: ?>
                                                <span class="text-muted">-</span>
                                            <?php endif; ?>
                                        </div>
                                    </div>

                                </div>

                                <!-- Sección derecha -->
                                <div class="d-flex align-items-center gap-3 ms-auto">
                                    <div class="d-flex align-items-center gap-2">
                                        <span class="text-muted small fw-semibold">Estado del Caso:</span>
                                        <span id="badge_estado_caso"
                                            class="badge rounded-pill px-3 py-2 fw-semibold 
                        <?= strtolower($info["estado"] ?? '') === 'completado' ? 'bg-success' :
                            (strtolower($info["estado"] ?? '') === 'observado' ? 'bg-warning text-dark' : 'bg-secondary'); ?>">
                                            <?= htmlspecialchars($info["estado"] ?? "Pendiente") ?>
                                        </span>
                                    </div>

                                    <a href="index.php" class="btn btn-outline-secondary btn-sm">
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
                                        <div class="col-md-3">
                                            <label class="form-label">Fecha de ejecución<span class="text-danger">*</span></label>
                                            <input type="datetime-local" id="fecha_ejecucion" class="form-control" required>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label">Ejecutor<span class="text-danger">*</span></label>
                                            <select class="form-select" id="ejecutor_nombre" name="ejecutor_nombre"
                                                required>
                                                <option value="">Seleccione...</option>
                                                <option value="Andres Silva" <?= (isset($_SESSION['usu_nombre']) && stripos($_SESSION['usu_nombre'], 'Andres Silva') !== false) ? 'selected' : '' ?>>Andres Silva</option>
                                                <option value="Lucero Sifuentes" <?= (isset($_SESSION['usu_nombre']) && stripos($_SESSION['usu_nombre'], 'Lucero Sifuentes') !== false) ? 'selected' : '' ?>>Lucero Sifuentes</option>
                                                <option value="Nancy Maza" <?= (isset($_SESSION['usu_nombre']) && stripos($_SESSION['usu_nombre'], 'Nancy Maza') !== false) ? 'selected' : '' ?>>Nancy Maza</option>
                                                <option value="Christian Candiotti" <?= (isset($_SESSION['usu_nombre']) && stripos($_SESSION['usu_nombre'], 'Christian Candiotti') !== false) ? 'selected' : '' ?>>Christian Candiotti</option>
                                            </select>
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label">Estado de la iteración <span class="text-danger">*</span></label>
                                            <select id="resultado" class="form-select" required>
                                                <option value="">Seleccione...</option>
                                                <option>Ejecutado</option>
                                                <option>Observado</option>
                                            </select>
                                        </div>
                                        <div class="col-12">
                                            <label class="form-label">Comentario <span class="text-danger">*</span></label>
                                            <textarea id="comentario" class="form-control" rows="3" required
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