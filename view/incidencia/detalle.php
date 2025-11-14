<?php
require_once("../../config/conexion.php");
require_once("../../models/Rol.php");
require_once("../../models/Incidencia.php");

$rol = new Rol();
$datos = $rol->validar_menu_x_rol($_SESSION["rol_id"], "incidencia");

if (isset($_SESSION["usu_id"]) && count($datos) > 0) {

    $id_incidencia = isset($_GET["id"]) ? intval($_GET["id"]) : 0;
    if ($id_incidencia <= 0) {
        header("Location:" . Conectar::ruta() . "view/incidencia/index.php");
        exit;
    }

    $inc = new Incidencia();
    $info = $inc->mostrar($id_incidencia);
    if (!$info) {
        die("<h3 style='text-align:center;margin-top:50px;'>Incidencia no encontrada</h3>");
    }
    ?>
    <!doctype html>
    <html lang="es">

    <head>
        <title>Detalle de Incidencia</title>
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
                                    <h4 class="mb-sm-0">Detalle de Incidencia</h4>
                                    <div class="page-title-right">
                                        <ol class="breadcrumb m-0">
                                            <li class="breadcrumb-item"><a href="index.php">Incidencias</a></li>
                                            <li class="breadcrumb-item active">Editar</li>
                                        </ol>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Cabecera resumen -->
                        <div class="card mb-3 border-0 shadow-sm" style="background: #f8f9fc;">
                            <div
                                class="card-body d-flex justify-content-between align-items-center flex-wrap gap-3 border-start border-4 border-primary rounded-3">

                                <div class="d-flex align-items-center flex-wrap gap-4">

                                    <!-- N° Incidencia -->
                                    <div>
                                        <div class="text-muted small fw-semibold">ID</div>
                                        <div class="fs-5 fw-semibold text-primary mb-0">
                                            <?= htmlspecialchars($info["id_incidencia"]); ?>
                                        </div>
                                    </div>

                                    <div class="text-muted fs-4">|</div>

                                    <!-- Correlativo -->
                                    <div>
                                        <div class="text-muted small fw-semibold">N° Inc</div>
                                        <div class="fw-semibold text-dark mb-0">
                                            <?= htmlspecialchars($info["correlativo_doc"] ?? "-"); ?>
                                        </div>
                                    </div>

                                    <div class="text-muted fs-4">|</div>

                                    <!-- Documentación asociada -->
                                    <div>
                                        <div class="text-muted small fw-semibold">Documentación Asociada</div>
                                        <div class="fw-semibold mb-0">
                                            <span class="badge bg-light text-primary border px-2 py-1">
                                                <?= htmlspecialchars($info["documentacion_nombre"] ?? "-"); ?>
                                            </span>
                                        </div>
                                    </div>

                                    <div class="text-muted fs-4">|</div>

                                    <!-- Actividad -->
                                    <div>
                                        <div class="text-muted small fw-semibold">Actividad</div>
                                        <div class="fw-semibold text-dark mb-0">
                                            <?= htmlspecialchars($info["actividad"] ?? "-"); ?>
                                        </div>
                                    </div>

                                    <div class="text-muted fs-4">|</div>

                                    <!-- Módulo -->
                                    <div>
                                        <div class="text-muted small fw-semibold">Módulo del Sistema</div>
                                        <div class="fw-semibold mb-0">
                                            <span class="badge bg-light text-primary border px-2 py-1">
                                                <?= htmlspecialchars($info["modulo"] ?? "-"); ?>
                                            </span>
                                        </div>
                                    </div>

                                </div>

                                <!-- Estado + Volver -->
                                <div class="d-flex align-items-center gap-3 ms-auto">
                                    <div class="d-flex align-items-center gap-2">
                                        <span class="text-muted small fw-semibold">Estado:</span>

                                        <span class="badge px-3 py-2 fw-semibold 
                                            <?php
                                            echo ($info['estado_incidencia'] == 'Pendiente') ? 'border border-warning text-warning bg-white' :
                                                (($info['estado_incidencia'] == 'Resuelto') ? 'border border-success text-success bg-white' :
                                                    'border border-secondary text-muted bg-white');
                                            ?>">
                                            <?= htmlspecialchars($info["estado_incidencia"]); ?>
                                        </span>
                                    </div>


                                    <a href="index.php" class="btn btn-outline-secondary btn-sm">
                                        <i class="bx bx-arrow-back"></i> Volver
                                    </a>
                                </div>

                            </div>
                        </div>


                        <!-- Formulario de edición -->
                        <form id="form_editar_incidencia">
                            <input type="hidden" id="id_incidencia" name="id_incidencia" value="<?= $id_incidencia; ?>">

                            <div class="row">
                                <!-- Columna principal -->
                                <div class="col-lg-8">
                                    <div class="card mb-3">
                                        <div class="card-header fw-semibold bg-light">
                                            Información General
                                        </div>
                                        <div class="card-body">

                                            <!-- Descripción -->
                                            <div class="mb-3">
                                                <label class="form-label fw-semibold">Descripción de la incidencia</label>
                                                <textarea name="descripcion" id="descripcion" class="form-control" rows="3"
                                                    placeholder="Describe claramente la incidencia detectada durante las pruebas funcionales."><?= htmlspecialchars($info["descripcion"]); ?></textarea>
                                            </div>

                                            <!-- Acción recomendada -->
                                            <div class="mb-3">
                                                <label class="form-label fw-semibold">Acción recomendada /
                                                    correctiva</label>
                                                <textarea name="accion_recomendada" id="accion_recomendada"
                                                    class="form-control" rows="3"
                                                    placeholder="Especifica la acción sugerida o el paso a seguir para resolver la incidencia."><?= htmlspecialchars($info["accion_recomendada"]); ?></textarea>
                                            </div>

                                            <!-- Fila: tipo y prioridad -->
                                            <div class="row">
                                                <div class="col-md-6 mb-3">
                                                    <label class="form-label fw-semibold">Tipo de incidencia</label>
                                                    <select name="tipo_incidencia" id="tipo_incidencia" class="form-select">
                                                        <option value="">Seleccione...</option>
                                                        <option value="Funcional" <?= $info["tipo_incidencia"] == "Funcional" ? "selected" : ""; ?>>Funcional</option>
                                                        <option value="Interfaz" <?= $info["tipo_incidencia"] == "Interfaz" ? "selected" : ""; ?>>Interfaz</option>
                                                        <option value="Validación" <?= $info["tipo_incidencia"] == "Validación" ? "selected" : ""; ?>>Validación</option>
                                                        <option value="Integración"
                                                            <?= $info["tipo_incidencia"] == "Integración" ? "selected" : ""; ?>>Integración</option>
                                                        <option value="Base de Datos" <?= $info["tipo_incidencia"] == "Base de Datos" ? "selected" : ""; ?>>Base de Datos</option>
                                                        <option value="Otro" <?= $info["tipo_incidencia"] == "Otro" ? "selected" : ""; ?>>Otro</option>
                                                    </select>
                                                </div>

                                                <div class="col-md-6 mb-3">
                                                    <label class="form-label fw-semibold">Prioridad</label>
                                                    <select name="prioridad" id="prioridad" class="form-select">
                                                        <option value="Alta" <?= $info["prioridad"] == "Alta" ? "selected" : ""; ?>>Alta</option>
                                                        <option value="Media" <?= $info["prioridad"] == "Media" ? "selected" : ""; ?>>Media</option>
                                                        <option value="Baja" <?= $info["prioridad"] == "Baja" ? "selected" : ""; ?>>Baja</option>
                                                    </select>
                                                </div>
                                            </div>

                                            <!-- Módulo -->
                                            <div class="mb-3">
                                                <label class="form-label fw-semibold">Módulo del Sistema</label>
                                                <select name="modulo" id="modulo" class="form-select" required>
                                                    <option value="">Seleccione módulo...</option>
                                                    <option value="EJENP" <?= $info["modulo"] == "EJENP" ? "selected" : ""; ?>>
                                                        EJENP</option>
                                                    <option value="SAJ - PJ" <?= $info["modulo"] == "SAJ - PJ" ? "selected" : ""; ?>>SAJ - PJ</option>
                                                    <option value="Casos de Prueba" <?= $info["modulo"] == "Casos de Prueba" ? "selected" : ""; ?>>Casos de Prueba</option>
                                                    <option value="Grabaciones Judiciales" <?= $info["modulo"] == "Grabaciones Judiciales" ? "selected" : ""; ?>>Grabaciones Judiciales</option>
                                                    <option value="Programación de Audiencias"
                                                        <?= $info["modulo"] == "Programación de Audiencias" ? "selected" : ""; ?>>Programación de Audiencias</option>
                                                    <option value="Actuaciones Judiciales" <?= $info["modulo"] == "Actuaciones Judiciales" ? "selected" : ""; ?>>Actuaciones Judiciales</option>
                                                    <option value="Otro" <?= $info["modulo"] == "Otro" ? "selected" : ""; ?>>
                                                        Otro</option>
                                                </select>
                                                <small class="text-muted">Seleccione el módulo funcional donde se identificó
                                                    la incidencia.</small>
                                            </div>



                                            <!-- Base de datos -->
                                            <div class="mb-3">
                                                <label class="form-label fw-semibold">Base de datos</label>
                                                <input type="text" name="base_datos" id="base_datos" class="form-control"
                                                    value="<?= htmlspecialchars($info["base_datos"]); ?>"
                                                    placeholder="Nombre o instancia de la base de datos relacionada">
                                            </div>

                                            <!-- Versión -->
                                            <div class="mb-3">
                                                <label class="form-label fw-semibold">Versión del sistema</label>
                                                <input type="text" name="version_origen" id="version_origen"
                                                    class="form-control"
                                                    value="<?= htmlspecialchars($info["version_origen"]); ?>"
                                                    placeholder="Ejemplo: v1.2.5 o build 2025.03">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Columna lateral -->
                                <div class="col-lg-4">
                                    <div class="card mb-3">
                                        <div class="card-header fw-semibold bg-light">
                                            Información de Registro
                                        </div>
                                        <div class="card-body">
                                            <p><strong>Analista QA:</strong><br><?= htmlspecialchars($info["analista"]); ?>
                                            </p>
                                            <p><strong>Fecha de
                                                    Registro:</strong><br><?= htmlspecialchars($info["fecha_registro"]); ?>
                                            </p>
                                            <p><strong>Fecha de
                                                    Recepción:</strong><br><?= htmlspecialchars($info["fecha_recepcion"]); ?>
                                            </p>

                                            <div class="mt-3">
                                                <label class="form-label fw-semibold">Estado actual</label>
                                                <select id="estado_incidencia" name="estado_incidencia" class="form-select">
                                                    <option value="Pendiente" <?= $info["estado_incidencia"] == "Pendiente" ? "selected" : ""; ?>>Pendiente</option>
                                                    <option value="En Proceso" <?= $info["estado_incidencia"] == "En Proceso" ? "selected" : ""; ?>>En Proceso</option>
                                                    <option value="Resuelto" <?= $info["estado_incidencia"] == "Resuelto" ? "selected" : ""; ?>>Resuelto</option>
                                                    <option value="Cerrado" <?= $info["estado_incidencia"] == "Cerrado" ? "selected" : ""; ?>>Cerrado</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="text-end mt-5 mb-4">
                                        <button type="submit" class="btn btn-primary px-4 shadow-sm rounded-pill">
                                            <i class="bx bx-save"></i> Guardar cambios
                                        </button>
                                    </div>
                                </div>
                            </div>



                        </form>


                    </div>
                </div>

                <?php require_once("../html/footer.php"); ?>
            </div>
        </div>

        <?php require_once("../html/sidebar.php"); ?>
        <div class="rightbar-overlay"></div>
        <?php require_once("../html/js.php"); ?>

        <script>
            $("#form_editar_incidencia").on("submit", function (e) {
                e.preventDefault();
                let formData = new FormData(this);

                $.ajax({
                    url: "../../controller/incidencia.php?op=editar",
                    type: "POST",
                    data: formData,
                    contentType: false,
                    processData: false,
                    dataType: "json",
                    success: function (data) {
                        if (data.status === "ok") {
                            Swal.fire("Guardado", "Cambios actualizados correctamente", "success");
                        } else {
                            Swal.fire("Error", data.msg || "No se pudo guardar", "error");
                        }
                    },
                    error: function (xhr, status, error) {
                        Swal.fire("Error", "Error en la solicitud AJAX", "error");
                        console.error("AJAX error:", error);
                    }
                });
            });
        </script>

    </body>

    </html>

<?php } else {
    header("Location:" . Conectar::ruta() . "index.php");
} ?>