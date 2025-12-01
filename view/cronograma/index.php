<?php
require_once("../../config/conexion.php");
require_once("../../models/Rol.php");

$rol = new Rol();
$permiso = $rol->validar_menu_x_rol($_SESSION["rol_id"], "cronograma");

if (!(isset($_SESSION["usu_id"]) && count($permiso) > 0)) {
    header("Location:" . Conectar::ruta() . "index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <title>Cronograma del Proyecto</title>
    <?php require_once("../html/head.php") ?>
    <link rel="stylesheet" href="cronograma.css">
</head>

<body>

<div id="layout-wrapper">
    <?php require_once("../html/header.php") ?>
    <?php require_once("../html/menu.php") ?>

    <div class="main-content">
        <div class="page-content">
            <div class="container-fluid">

                <h4 class="mb-3 fw-bold">Cronograma del Proyecto</h4>

                <!-- Selección de cronograma -->
                <div class="row mb-3">
                    <div class="col-md-4">
                        <label><b>Seleccione Cronograma</b></label>
                        <select id="selectCronograma" class="form-select"></select>
                    </div>

                    <div class="col-md-3">
                        <label><b>Vista</b></label>
                        <select id="vista" class="form-select">
                            <option value="mes">Vista Mensual</option>
                            <option value="dia">Vista Diaria</option>
                        </select>
                    </div>
                </div>

                <!-- TABLA CRONOGRAMA -->
                <div id="contenedorTabla"></div>

            </div>
        </div>

        <?php require_once("../html/footer.php") ?>
    </div>
</div>

<?php require_once("../html/sidebar.php") ?>
<?php require_once("../html/js.php") ?>
<script src="cronograma.js"></script>

</body>
</html>
