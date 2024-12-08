<?php
    require_once("../config/conexion.php");
    require_once("../models/Documento.php");
    $documento = new Documento();

    switch($_GET["op"]){

        case "insert":
            $datos = $documento->insert_documento($_POST["usu_id"], $_POST["dep_id"]);
            if(is_array($datos)==true and count($datos)>0){
                foreach($datos as $row){
                    $output["doc_id"] = $row["doc_id"];
                }
                echo json_encode($output);
            }
        break;
        
        case "update":
            $documento->update_documento($_POST["doc_id"], $_POST["doc_asun"], $_POST["doc_desc"], $_POST["dep_id"]);
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

        case "listardetalle_consulta":
            $datos=$documento->list_docdetalle($_POST["doc_id"]);
            $data= Array();
            foreach($datos as $row){
                $sub_array = array();
                $sub_array[] = $row["docd_obs"];
                $sub_array[] = '<a href="../../public/src/'.$row["docd_file"].'" target="_blank">'.$row["docd_file"].'</a>';
                $data[] = $sub_array;
            }
        
            $results = array(
                "sEcho"=>1,
                "iTotalRecords"=>count($data),
                "iTotalDisplayRecords"=>count($data),
                "aaData"=>$data);
            echo json_encode($results);
        break;

        case "listar":
            $datos=$documento->list_doc($_POST["usu_id"]);
            $data= Array();
            foreach($datos as $row){
                $sub_array = array();
                $sub_array[] = "DOC-".$row["doc_id"];
                $sub_array[] = date("d-m-Y", strtotime($row["fech_crea"]));
                $sub_array[] = $row["doc_asun"];
                $sub_array[] = $row["doc_desc"];
                $sub_array[] = '<button type="button" onClick="ver('.$row["doc_id"].');"  id="'.$row["doc_id"].'" class="btn btn-outline-info btn-icon"><div><i class="fa fa-database"></i></div></button>';
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