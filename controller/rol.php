<?php
    /* TODO: Incluye el archivo de configuración de la conexión a la base de datos y la clase Usuario */
    require_once("../config/conexion.php");
    require_once("../models/Rol.php");

    /* TODO:Crea una instancia de la clase Area */
    $rol = new Rol();

    /* TODO: Utiliza una estructura switch para determinar la operación a realizar según el valor de $_GET["op"] */
    switch($_GET["op"]){

        /* TODO: Si la operación es "combo" */
        case "combo":
            $datos=$rol->get_rol();
            $html="";
            $html.="<option value=''>Seleccionar</option>";
            if(is_array($datos)==true and count($datos)>0){
                foreach($datos as $row){
                    $html.="<option value='".$row['rol_id']."'>".$row['rol_nom']."</option>";
                }
                echo $html;
            }
            break;
        
        case "listar":
            $datos=$rol->get_rol();
            $data = Array();
            foreach($datos as $row){
                $sub_array = array();
                $sub_array[]= $row["rol_nom"];
                $sub_array[]= $row["fech_crea"];
                $sub_array[]= '<button type="button" class="btn btn-soft-info waves-effect waves-light btn-sm" onClick="permiso('.$row["rol_id"].')"><i class="bx bx-shield-quarter font-size-16 align-middle"></i></button>
                <button type="button" class="btn btn-soft-warning waves-effect waves-light btn-sm" onClick="editar('.$row["rol_id"].')"><i class="bx bx-edit-alt font-size-16 align-middle"></i></button>
                <button type="button" class="btn btn-soft-danger waves-effect waves-light btn-sm" onClick="eliminar('.$row["rol_id"].')"><i class="bx bx-trash-alt font-size-16 align-middle"></i></button>';
                $data[]=$sub_array;
            }

            $results = array(
                "sEcho"=>1,
                "iTotalRecords"=>count($data),
                "iTotalDisplayRecords"=>count($data),
                "aaData"=>$data);

            echo json_encode($results);
            break;

        case "guardaryeditar":
            $datos = $rol->get_rol_nombre($_POST["rol_nom"]);
            if(is_array($datos) == true and count($datos) == 0){
                if(empty($_POST["rol_id"])){
                    $rol->insert_rol($_POST["rol_nom"]);
                    echo "1";
                }else{
                    $rol->update_rol($_POST["rol_id"],$_POST["rol_nom"]);
                    echo "2";
                }
            }else{
                echo "0";
            }
            break;

        case "mostrar":
            $datos = $rol->get_rol_x_id($_POST["rol_id"]);
            if(is_array($datos) == true and count($datos) > 0){
                foreach($datos as $row){
                    $output["rol_id"] = $row["rol_id"];
                    $output["rol_nom"] = $row["rol_nom"];
                }
                echo json_encode($output);
            }
            break;

        case "eliminar":
            $rol->eliminar_rol($_POST["rol_id"]);
            echo "1";
            break;

        case "permiso":
            $datos=$rol->get_rol_menu_permisos($_POST["rol_id"]);
            $data = Array();
            foreach($datos as $row){
                $sub_array = array();
                $sub_array[]= $row["men_nom_vista"];
                if($row["mend_permi"]=="Si"){
                    $sub_array[]= '<button type="button" class="btn btn-soft-success waves-effect waves-light btn-sm" onClick="deshabilitar('.$row["mend_id"].')"><i class="bx bx-check-double font-size-16 align-middle"></i> Si</button>';
                }else{
                    $sub_array[]= '<button type="button" class="btn btn-soft-danger waves-effect waves-light btn-sm" onClick="habilitar('.$row["mend_id"].')"><i class="bx bx-window-close font-size-16 align-middle"></i> No</button>';
                }
                $data[]=$sub_array;
            }

            $results = array(
                "sEcho"=>1,
                "iTotalRecords"=>count($data),
                "iTotalDisplayRecords"=>count($data),
                "aaData"=>$data);

            echo json_encode($results);
            break;

        case "habilitar":
            $rol->habilitar_rol_menu($_POST["mend_id"]);
            echo "1";
            break;

        case "deshabilitar":
            $rol->deshabilitar_rol_menu($_POST["mend_id"]);
            echo "1";
            break;

    }
?>
