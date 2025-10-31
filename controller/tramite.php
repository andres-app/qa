<?php
    /* TODO: Incluye el archivo de configuración de la conexión a la base de datos y la clase Usuario */
    require_once("../config/conexion.php");
    require_once("../models/Tramite.php");

    /* TODO:Crea una instancia de la clase Tramite */
    $tramite = new Tramite();

    /* TODO: Utiliza una estructura switch para determinar la operación a realizar según el valor de $_GET["op"] */
    switch($_GET["op"]){

        /* TODO: Si la operación es "combo" */
        case "combo":
            $datos=$tramite->get_tramite();
            $html="";
            $html.="<option value=''>Seleccionar</option>";
            if(is_array($datos)==true and count($datos)>0){
                foreach($datos as $row){
                    $html.="<option value='".$row['tra_id']."'>".$row['tra_nom']."</option>";
                }
                echo $html;
            }
            break;

        case "listar":
            $datos=$tramite->get_tramite();
            $data = Array();
            foreach($datos as $row){
                $sub_array = array();
                $sub_array[]= $row["tra_nom"];
                $sub_array[]= substr($row["tra_descrip"],0,20)."...";
                $sub_array[]= $row["fech_crea"];
                $sub_array[]= '<button type="button" class="btn btn-soft-warning waves-effect waves-light btn-sm" onClick="editar('.$row["tra_id"].')"><i class="bx bx-edit-alt font-size-16 align-middle"></i></button>';
                $sub_array[]= '<button type="button" class="btn btn-soft-danger waves-effect waves-light btn-sm" onClick="eliminar('.$row["tra_id"].')"><i class="bx bx-trash-alt font-size-16 align-middle"></i></button>';
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
            $datos = $tramite->get_tra_nombre($_POST["tra_nom"]);
            if(is_array($datos) == true and count($datos) == 0){
                if(empty($_POST["tra_id"])){
                    $tramite->insert_tramite($_POST["tra_nom"],$_POST["tra_descrip"]);
                    echo "1";
                }else{
                    $tramite->update_tramite($_POST["tra_id"],$_POST["tra_nom"],$_POST["tra_descrip"]);
                    echo "2";
                }
            }else{
                echo "0";
            }
            break;

        case "mostrar":
            $datos = $tramite->get_tra_x_id($_POST["tra_id"]);
            if(is_array($datos) == true and count($datos) > 0){
                foreach($datos as $row){
                    $output["tra_id"] = $row["tra_id"];
                    $output["tra_nom"] = $row["tra_nom"];
                    $output["tra_descrip"] = $row["tra_descrip"];
                }
                echo json_encode($output);
            }
            break;

        case "eliminar":
            $tramite->eliminar_tramite($_POST["tra_id"]);
            echo "1";
            break;
    }
?>