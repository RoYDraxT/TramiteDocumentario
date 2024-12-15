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
        
        case "listar_departamento":
            $dep_id_actual = isset($_POST["dep_id_actual"]) ? $_POST["dep_id_actual"] : null;
            $datos = $documento->list_departamento($dep_id_actual); // Método en el modelo
            $data = array();
            foreach ($datos as $row) {
                $sub_array = array();
                $sub_array["dep_id"] = $row["dep_id"];
                $sub_array["dep_nom"] = $row["dep_nom"];
                $data[] = $sub_array;
            }
            echo json_encode($data);
        break;
            
        case "derivar":
            $doc_id = isset($_POST['doc_id']) ? intval($_POST['doc_id']) : 0;
            $dep_id = isset($_POST['dep_id']) ? intval($_POST['dep_id']) : 0;
        
            if ($doc_id > 0 && $dep_id > 0) {
                $resultado = $documento->derivar_documento($doc_id, $dep_id);
                if ($resultado) {
                    echo json_encode(["status" => "success", "message" => "Documento derivado exitosamente."]);
                } else {
                    echo json_encode(["status" => "error", "message" => "No se pudo derivar el documento."]);
                }
            } else {
                echo json_encode(["status" => "error", "message" => "Parámetros inválidos."]);
            }
        break;

        case "tramitar":
            $doc_id = isset($_POST["doc_id"]) ? intval($_POST["doc_id"]) : 0;
            
            if ($doc_id > 0) {
                try {
                    // Obtener la fecha y hora actual en formato compatible
                    $fecha_actual = date("Y-m-d H:i:s");
                    
                    // Conectar a la base de datos
                    $conectar = new Conectar();
                    $conexion = $conectar->getConexion(); // Asegúrate de que esto esté correctamente definido
        
                    // Actualizar la base de datos solo si no se ha realizado el trámite antes
                    $checkSql = "SELECT * FROM documento WHERE doc_id = ? AND (fech_visto IS NULL OR seguimiento = 0)";
                    $checkStmt = $conexion->prepare($checkSql);
                    $checkStmt->execute([$doc_id]);
                    $documento = $checkStmt->fetch(PDO::FETCH_ASSOC);
        
                    if ($documento) {
                        // Si el trámite no se ha realizado, continuar con la actualización
                        $sql = "UPDATE documento SET fech_visto = ?, seguimiento = 1 WHERE doc_id = ?";
                        $stmt = $conexion->prepare($sql);
                        $stmt->execute([$fecha_actual, $doc_id]);
                        echo json_encode(["status" => "success", "message" => "El trámite ha sido registrado correctamente."]);
                    } else {
                        echo json_encode(["status" => "info", "message" => "El trámite ya ha sido registrado previamente."]);
                    }
                } catch (Exception $e) {
                    logError("Error al tramitar el documento: " . $e->getMessage());
                    echo json_encode(["status" => "error", "message" => "Ocurrió un error al registrar el trámite."]);
                }
            } else {
                echo json_encode(["status" => "error", "message" => "ID de documento inválido."]);
            }
        break;
        
    }

