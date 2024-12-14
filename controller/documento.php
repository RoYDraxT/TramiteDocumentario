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
            $datos = $documento->list_docdetalle($_POST["doc_id"]);
            $data = array();
            foreach($datos as $row){
                $sub_array = array();
                $sub_array[] = $row["docd_obs"];
                $sub_array[] = '<a href="../../public/src/'.$row["docd_file"].'" target="_blank">'.$row["docd_file"].'</a>';
                $sub_array[] = $row["seguimiento"] == 0 ? 'Pendiente' : 'Completado';
                $sub_array[] = '<button type="button" onClick="eliminar('.$row["docd_id"].');" id="'.$row["docd_id"].'" class="btn btn-outline-danger btn-icon"><div><i class="fa fa-trash"></i></div></button>';
                $data[] = $sub_array;
            }
        
            $results = array(
                "sEcho" => 1,
                "iTotalRecords" => count($data),
                "iTotalDisplayRecords" => count($data),
                "aaData" => $data
            );
            echo json_encode($results);
        break;        

        case "listardetalle_consulta":
            $datos = $documento->list_docdetalle($_POST["doc_id"]);
            $data = array();
        
            foreach($datos as $row) {
                $sub_array = array();
                $sub_array[] = $row["docd_obs"]; // Observación
                $sub_array[] = $row["docd_file"]; // Nombre del archivo
        
                $data[] = $sub_array;
            }
        
            $results = array(
                "sEcho" => 1,
                "iTotalRecords" => count($data),
                "iTotalDisplayRecords" => count($data),
                "aaData" => $data
            );
        
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
                $sub_array[] = '<button type="button" class="btn btn-secondary" onClick="consultar('.$row["doc_id"].');">Consultar</button>'; // Botón gris
                $data[] = $sub_array;
            }
        
            $results = array(
                "sEcho"=>1,
                "iTotalRecords"=>count($data),
                "iTotalDisplayRecords"=>count($data),
                "aaData"=>$data);
            echo json_encode($results);
        break;
        
        case 'derivar':
            $doc_id = isset($_POST['doc_id']) ? intval($_POST['doc_id']) : 0;
            $dep_id = isset($_POST['dep_id']) ? intval($_POST['dep_id']) : 0;
        
            if ($doc_id > 0 && $dep_id > 0) {
                try {
                    $sql = "UPDATE documento SET dep_id = ? WHERE doc_id = ?";
                    $stmt = $conexion->prepare($sql);
                    $stmt->execute([$dep_id, $doc_id]);
        
                    echo json_encode(['status' => 'success', 'message' => 'Documento derivado correctamente']);
                } catch (Exception $e) {
                    logError("Error al derivar el documento: " . $e->getMessage());
                    echo json_encode(['status' => 'error', 'message' => 'No se pudo derivar el documento']);
                }
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Parámetros inválidos']);
            }
            break;
        
        
    }

