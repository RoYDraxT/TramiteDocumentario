<?php
    require_once("../config/conexion.php");
    require_once("../models/Documento.php");
    $documento = new Documento();

    switch($_GET["op"]){

        case "insert":
            $datos = $documento->insert_documento($_POST["usu_id"]);
            if(is_array($datos)==true and count($datos)>0){
                foreach($datos as $row)
                {
                    $output["doc_id"] = $row["doc_id"];
                }
                echo json_encode($output);
            }
        break;

        case "update":
            $documento->update_documento($_POST["doc_id"],$_POST["doc_asun"],$_POST["doc_desc"]);
        break;
        
        case "insertdetalle":
            $documento->insert_docdetalle($_POST["doc_id"],$_POST["docd_obs"],$_POST["docd_file"]);
        break;

        case "deletedetalle":
            $documento->delete_docdetalle($_POST["docd_id"]);
        break;

        case "listardetalle":
            $datos=$documento->list_docdetalle($_POST["doc_id"]);
            $data= Array();
            foreach($datos as $row){
                $sub_array = array();
                $sub_array[] = $row["docd_obs"];
                $sub_array[] = '<a href="../../public/src/'.$row["docd_file"].'" target="_blank">'.$row["docd_file"].'</a>';
                $sub_array[] = '<button type="button" onClick="eliminar('.$row["docd_id"].');"  id="'.$row["docd_id"].'" class="btn btn-outline-danger btn-icon"><div><i class="fa fa-trash"></i></div></button>';
                $data[] = $sub_array;
            }
        
            $results = array(
                "sEcho"=>1,
                "iTotalRecords"=>count($data),
                "iTotalDisplayRecords"=>count($data),
                "aaData"=>$data);
            echo json_encode($results);
        break;

    }

