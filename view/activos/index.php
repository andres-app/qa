<?php
// Requerimos los archivos necesarios para la conexión a la base de datos y el modelo Rol
require_once("../../config/conexion.php");
require_once("../../models/Rol.php");

// Crear una instancia del modelo Rol para validar permisos de acceso
$rol = new Rol();

// Validar si el rol del usuario tiene acceso al módulo "vehículos"
$datos = $rol->validar_menu_x_rol($_SESSION["rol_id"], "requerimientos");

// Verificar si el usuario está autenticado y tiene los permisos necesarios
if (isset($_SESSION["usu_id"]) && count($datos) > 0) {
?>

<!doctype html>
<html lang="es">
    <head>
        <!-- Título de la página -->
        <title>Mantenimiento de Vehículos - DIGESE</title>
        
        <!-- Incluir el archivo de configuración del <head> -->
        <?php require_once("../html/head.php") ?>
    </head>

    <body>

        <div id="layout-wrapper">
            <!-- Incluir el header de la página -->
            <?php require_once("../html/header.php") ?>

            <!-- Incluir el menú de navegación lateral -->
            <?php require_once("../html/menu.php") ?>

            <!-- Contenido principal de la página -->
            <div class="main-content">

                <div class="page-content">
                    <div class="container-fluid">

                        <!-- Título y breadcrumb de la página -->
                        <div class="row">
                            <div class="col-12">
                                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                                    <h4 class="mb-sm-0 font-size-18">Lista de Vehículos</h4>
                                    <div class="page-title-right">
                                        <ol class="breadcrumb m-0">
                                            <li class="breadcrumb-item"><a href="javascript: void(0);">Pages</a></li>
                                            <li class="breadcrumb-item active">Lista de Vehículos</li>
                                        </ol>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Sección principal de la página: tabla de vehículos -->
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h4 class="card-title">Vehículos</h4>
                                        <p class="card-title-desc">(*) Vista para registrar, modificar, listar y eliminar vehículos.</p>
                                    </div>

                                    <div class="card-body">
                                        <!-- Botón para registrar un nuevo vehículo -->
                                        <button type="button" id="btnnuevo" class="btn btn-primary waves-effect waves-light">
                                            Nuevo Registro
                                        </button>
                                        <br><br>

                                        <!-- Tabla para mostrar la lista de vehículos registrados -->
                                        <table id="listado_table" class="table table-bordered dt-responsive nowrap w-100">
                                            <thead>
                                                <tr>
                                                    <th>ID</th>
                                                    <th>Placa</th>
                                                    <th>Marca</th>
                                                    <th>Modelo</th>
                                                    <th>Año</th>
                                                    <th>Último Mantenimiento</th>
                                                    <th>Próximo Mantenimiento</th>
                                                    <th>Acciones</th>
                                                </tr>
                                            </thead>

                                            <tbody>
                                                <!-- Aquí se llenará la tabla dinámicamente -->
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                    </div> <!-- Fin del contenedor-fluid -->
                </div> <!-- Fin del contenido de la página -->

                <!-- Incluir el footer -->
                <?php require_once("../html/footer.php") ?>

            </div> <!-- Fin del contenido principal -->

        </div> <!-- Fin del layout-wrapper -->

        <!-- Incluir el modal para el registro o edición de vehículos -->
        <?php require_once("modal_vehiculo.php") ?>

        <!-- Incluir la barra lateral -->
        <?php require_once("../html/sidebar.php") ?>

        <div class="rightbar-overlay"></div>

        <!-- Incluir los archivos JavaScript necesarios para la página -->
        <?php require_once("../html/js.php") ?>

        <!-- Incluir el script para gestionar los vehículos (vehiculo.js) -->
        <script type="text/javascript" src="activo.js"></script>

    </body>
</html>

<?php
    } else {
        // Si el usuario no está autenticado o no tiene permisos, redirigir al inicio de sesión
        header("Location:" . Conectar::ruta() . "index.php");
    }
?>
