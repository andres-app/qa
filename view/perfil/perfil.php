<?php
require_once("../../config/conexion.php");
require_once("../../models/Usuario.php");
$usuario = new Usuario();

// Obtener los datos del usuario en sesión
$datos_usuario = $usuario->get_usuario_id($_SESSION["usu_id"]);

if (isset($_SESSION["usu_id"])) {
?>
<!doctype html>
<html lang="es">

<head>
    <title>Perfil de Usuario - TEMPLATE</title>
    <?php require_once("../html/head.php"); ?>
</head>

<body>

    <div id="layout-wrapper">

        <?php require_once("../html/header.php"); ?>
        <?php require_once("../html/menu.php"); ?>

        <div class="main-content">
            <div class="page-content">
                <div class="container-fluid">

                    <!-- Título de la página -->
                    <div class="row">
                        <div class="col-12">
                            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                                <h4 class="mb-sm-0 font-size-18">Perfil del Usuario</h4>
                            </div>
                        </div>
                    </div>

                    <!-- Mostrar mensaje de confirmación si existe -->
                    <?php if (isset($_SESSION['mensaje'])): ?>
                        <div class="alert alert-success" role="alert">
                            <?php echo $_SESSION['mensaje']; ?>
                        </div>
                        <?php unset($_SESSION['mensaje']); // Elimina el mensaje después de mostrarlo ?>
                    <?php endif; ?>

                    <!-- Contenido del perfil -->
                    <div class="row">
                        <!-- Columna para la foto y datos del usuario -->
                        <div class="col-lg-4">
                            <div class="card shadow-sm">
                                <div class="card-body text-center">
                                    <img src="<?php echo $_SESSION['usu_img']; ?>"
                                        class="rounded-circle avatar-xl img-thumbnail" alt="Perfil Usuario">
                                    <h5 class="mt-3 mb-0"><?php echo $_SESSION["usu_nomape"]; ?></h5>
                                    <p class="text-muted"><?php echo $_SESSION["usu_correo"]; ?></p>
                                    <div class="d-grid">
                                        <button class="btn btn-success btn-sm mt-2" disabled>Usuario Activo</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Columna para los detalles del perfil y formulario de actualización -->
                        <div class="col-lg-8">
                            <div class="card shadow-sm">
                                <div class="card-header">
                                    <h5 class="card-title mb-0">Detalles del Perfil</h5>
                                </div>
                                <div class="card-body">
                                    <form action="../../controller/usuario.php?op=actualizar_perfil" method="post"
                                        enctype="multipart/form-data">
                                        
                                        <!-- Nombre Completo -->
                                        <div class="mb-3">
                                            <label for="usu_nomape" class="form-label">Nombre Completo</label>
                                            <input type="text" class="form-control" id="usu_nomape" name="usu_nomape"
                                                value="<?php echo isset($_SESSION['usu_nomape']) ? $_SESSION['usu_nomape'] : ''; ?>" required>
                                        </div>

                                        <!-- Correo Electrónico -->
                                        <div class="mb-3">
                                            <label for="usu_correo" class="form-label">Correo Electrónico</label>
                                            <input type="email" class="form-control" id="usu_correo" name="usu_correo"
                                                value="<?php echo isset($_SESSION['usu_correo']) ? $_SESSION['usu_correo'] : ''; ?>" readonly>
                                        </div>

                                        <!-- Cambiar Contraseña -->
                                        <div class="mb-3">
                                            <label for="usu_pass" class="form-label">Cambiar Contraseña</label>
                                            <input type="password" class="form-control" id="usu_pass" name="usu_pass"
                                                placeholder="Dejar vacío si no se desea cambiar">
                                        </div>

                                        <!-- Botón Actualizar -->
                                        <div class="d-grid">
                                            <button type="submit" class="btn btn-primary">Actualizar Perfil</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div> <!-- Fin del row -->
                </div> <!-- Fin del container-fluid -->
            </div> <!-- Fin del page-content -->

            <?php require_once("../html/footer.php"); ?>
        </div> <!-- Fin del layout-wrapper -->
    </div>

    <?php require_once("../html/sidebar.php") ?>

    <div class="rightbar-overlay"></div>

    <?php require_once("../html/js.php") ?>

</body>

</html>

<?php
} else {
    header("Location: ../../index.php");
}
?>
