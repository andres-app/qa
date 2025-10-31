<?php
    /* TODO: Incluye el archivo de configuración de la conexión a la base de datos y la clase Usuario */
    require_once("../config/conexion.php");
    require_once("../models/Area.php");

    /* TODO:Crea una instancia de la clase Area */
    $area = new Area();

    /* TODO: Utiliza una estructura switch para determinar la operación a realizar según el valor de $_GET["op"] */
    switch($_GET["op"]){

        /* TODO: Si la operación es "combo" */
        case "combo":
            $datos=$area->get_area();
            $html="";
            $html.="<option value=''>Seleccionar</option>";
            if(is_array($datos)==true and count($datos)>0){
                foreach($datos as $row){
                    $html.="<option value='".$row['area_id']."'>".$row['area_nom']."</option>";
                }
                echo $html;
            }
            break;

        case "listar":
            $datos=$area->get_area();
            $data = Array();
            foreach($datos as $row){
                $sub_array = array();
                $sub_array[]= $row["area_nom"];
                $sub_array[]= $row["area_correo"];
                $sub_array[]= $row["fech_crea"];
                $sub_array[]= '<button type="button" class="btn btn-soft-warning waves-effect waves-light btn-sm" onClick="editar('.$row["area_id"].')"><i class="bx bx-edit-alt font-size-16 align-middle"></i></button>
                <button type="button" class="btn btn-soft-danger waves-effect waves-light btn-sm" onClick="eliminar('.$row["area_id"].')"><i class="bx bx-trash-alt font-size-16 align-middle"></i></button>';
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
            $datos = $area->get_area_nombre($_POST["area_nom"]);
            if(is_array($datos) == true and count($datos) == 0){
                if(empty($_POST["area_id"])){
                    $area->insert_area($_POST["area_nom"],$_POST["area_correo"]);
                    echo "1";
                }else{
                    $area->update_area($_POST["area_id"],$_POST["area_nom"],$_POST["area_correo"]);
                    echo "2";
                }
            }else{
                echo "0";
            }
            break;

        case "mostrar":
            $datos = $area->get_area_x_id($_POST["area_id"]);
            if(is_array($datos) == true and count($datos) > 0){
                foreach($datos as $row){
                    $output["area_id"] = $row["area_id"];
                    $output["area_nom"] = $row["area_nom"];
                    $output["area_correo"] = $row["area_correo"];
                }
                echo json_encode($output);
            }
            break;

        case "eliminar":
            $area->eliminar_area($_POST["area_id"]);
            echo "1";
            break;

        case "permiso":
            $datos=$area->get_area_usuario_permisos($_POST["usu_id"]);
            $data = Array();
            foreach($datos as $row){
                $sub_array = array();
                $sub_array[]= $row["area_nom"];
                if($row["aread_permi"]=="Si"){
                    $sub_array[]= '<button type="button" class="btn btn-soft-success waves-effect waves-light btn-sm" onClick="deshabilitar('.$row["aread_id"].')"><i class="bx bx-check-double font-size-16 align-middle"></i> Si</button>';
                }else{
                    $sub_array[]= '<button type="button" class="btn btn-soft-danger waves-effect waves-light btn-sm" onClick="habilitar('.$row["aread_id"].')"><i class="bx bx-window-close font-size-16 align-middle"></i> No</button>';
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
            $area->habilitar_area_usuario($_POST["aread_id"]);
            echo "1";
            break;

        case "deshabilitar":
            $area->deshabilitar_area_usuario($_POST["aread_id"]);
            echo "1";
            break;
    }
?>