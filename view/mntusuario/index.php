<?php
    require_once("../../config/conexion.php");
    require_once("../../models/Rol.php");
    $rol = new Rol();
    $datos = $rol->validar_menu_x_rol($_SESSION["rol_id"],"mntusuario");
    if(isset($_SESSION["usu_id"]) and count($datos)>0){
?>
<!doctype html>
<html lang="es">
    <head>
        <title>  Mnt.Colaborador TEMPLATE</title>
        <?php require_once("../html/head.php")?>
    </head>

    <body>

        <div id="layout-wrapper">

            <?php require_once("../html/header.php")?>

            <?php require_once("../html/menu.php")?>

            <div class="main-content">

                <div class="page-content">
                    <div class="container-fluid">

                        <div class="row">
                            <div class="col-12">
                                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                                    <h4 class="mb-sm-0 font-size-18">Mantenimiento de Colaborador</h4>

                                    <div class="page-title-right">
                                        <ol class="breadcrumb m-0">
                                            <li class="breadcrumb-item"><a href="javascript: void(0);">Pages</a></li>
                                            <li class="breadcrumb-item active">Starter Page</li>
                                        </ol>
                                    </div>

                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="card">
                                        <div class="card-header">
                                            <h4 class="card-title">Colaborador</h4>
                                            <p class="card-title-desc">(*) Vista para Registrar, Modificar, Listar y Eliminar. </p>
                                        </div>

                                        <div class="card-body">
                                            <button type="button" id="btnnuevo" class="btn btn-primary waves-effect waves-light">Nuevo Registro</button>
                                            <br>
                                            <br>
                                            <table id="listado_table" class="table table-bordered dt-responsive  nowrap w-100">
                                                <thead>
                                                    <tr>
                                                        <th>Nombre</th>
                                                        <th>Correo</th>
                                                        <th>Rol</th>
                                                        <th>Fech.Creación</th>
                                                        <th>Acciones</th>
                                                    </tr>
                                                </thead>

                                                <tbody>

                                                </tbody>
                                            </table>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

                <?php require_once("../html/footer.php")?>

            </div>

        </div>

        <?php require_once("mnt.php")?>

        <?php require_once("mntpermiso.php")?>

        <?php require_once("../html/sidebar.php")?>

        <div class="rightbar-overlay"></div>

        <?php require_once("../html/js.php")?>

        <script type="text/javascript" src="mntusuario.js"></script>

    </body>
</html>
<?php
  }else{
    header("Location:".Conectar::ruta()."index.php");
  }
?>