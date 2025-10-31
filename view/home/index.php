<?php
    require_once("../../config/conexion.php");
    require_once("../../models/Rol.php");
    $rol = new Rol();
    $datos = $rol->validar_menu_x_rol($_SESSION["rol_id"],"home");
    if(isset($_SESSION["usu_id"]) and count($datos)>0){
?>
<!doctype html>
<html lang="es">
    <head>
        <title>  Inicio TEMPLATE</title>
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
                                    <h4 class="mb-sm-0 font-size-18">Inicio</h4>
                                </div>
                            </div>

                            <div class="card border border-primary">
                                <div class="card-header bg-transparent border-primary">
                                    <h5 class="my-0 text-primary"><i class="mdi mdi-bullseye-arrow me-3"></i>Intrucciones para el registro de la TEMPLATE</h5>
                                </div>
                                <div class="card-body">
                                    <h5 class="card-title">Leer Atentamente : </h5>
                                    <p class="card-text">Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</p>
                                    <h6 class="card-title">Paso 1 : </h6>
                                    <p class="card-text">Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</p>
                                    <h6 class="card-title">Paso 2 : </h6>
                                    <p class="card-text">Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</p>
                                    <h6 class="card-title">Paso 3 : </h6>
                                    <p class="card-text">Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</p>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

                <?php require_once("../html/footer.php")?>

            </div>

        </div>

        <?php require_once("../html/sidebar.php")?>

        <div class="rightbar-overlay"></div>

        <?php require_once("../html/js.php")?>

    </body>
</html>
<?php
  }else{
    header("Location:".Conectar::ruta()."index.php");
  }
?>