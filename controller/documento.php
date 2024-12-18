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

        case "anular":
            // Extraer y validar los datos enviados por POST
            $doc_id = isset($_POST['doc_id']) ? intval($_POST['doc_id']) : 0;
            $resd_obs = isset($_POST['resd_obs']) ? trim($_POST['resd_obs']) : '';
            $dep_id = isset($_SESSION['dep_id']) ? intval($_SESSION['dep_id']) : 0;
        
            // Validar parámetros obligatorios
            if ($doc_id <= 0 || empty($resd_obs)) {
                echo json_encode(["status" => "error", "message" => "Debe proporcionar un motivo para anular el documento y un ID válido."]);
                exit;
            }
        
            // Asegurarse de que no se envíe archivo
            if (isset($_FILES['resd_file']) && $_FILES['resd_file']['error'] == UPLOAD_ERR_OK) {
                echo json_encode(["status" => "error", "message" => "No se permite adjuntar un archivo al anular un documento."]);
                exit;
            }
        
            try {
                // Actualizar el estado y la fecha del documento en la tabla `documento`
                $result = $documento->actualizar_documento_anulacion($doc_id);
        
                if (!$result) {
                    throw new Exception("Error al actualizar el estado del documento con ID: $doc_id.");
                }
        
                // Insertar el detalle de la anulación en la tabla `detalleres`
                $detalle_result = $documento->insertar_detalle_anulacion($doc_id, $dep_id, $resd_obs);
        
                if (!$detalle_result) {
                    throw new Exception("Error al registrar el motivo de anulación en el detalle.");
                }
        
                // Respuesta exitosa
                echo json_encode(["status" => "success", "message" => "El documento ha sido anulado correctamente."]);
        
            } catch (Exception $e) {
                // Loguear el error y enviar mensaje al cliente
                logError("Error al anular el documento: " . $e->getMessage());
                echo json_encode(["status" => "error", "message" => $e->getMessage()]);
            }
            break;             
        
        case "responder":
            // Extraer y validar los datos enviados por POST
            $doc_id = isset($_POST['doc_id']) ? intval($_POST['doc_id']) : 0;
            $resd_obs = isset($_POST['resd_obs']) ? trim($_POST['resd_obs']) : '';
            $resd_file_name = '';

            // Validar que el ID del documento es válido y que hay contenido en la respuesta o un archivo
            if ($doc_id <= 0 || (empty($resd_obs) && empty($_FILES['resd_file']['name']))) {
                echo json_encode(["status" => "error", "message" => "Debe proporcionar una respuesta o adjuntar un archivo."]);
                exit;
            }

            try {
                // Procesar el archivo si se subió uno
                if (!empty($_FILES['resd_file']['name'])) {
                    $resd_file_name = $documento->upload_file('resd_file');
                }

                // Actualizar el documento: Seguimiento = 2 y fecha de respuesta
                $result = $documento->actualizar_documento_respuesta($doc_id);

                if (!$result) {
                    throw new Exception("Error al actualizar el documento con ID: $doc_id.");
                }

                // Insertar en detalleres
                $detalle_result = $documento->insertar_detalle_respuesta($doc_id, $_SESSION['dep_id'], $resd_obs, $resd_file_name);

                if (!$detalle_result) {
                    throw new Exception("Error al insertar el detalle de la respuesta.");
                }

                // Enviar respuesta exitosa
                echo json_encode(["status" => "success", "message" => "La respuesta ha sido registrada correctamente."]);

            } catch (Exception $e) {
                // Loguear error y responder
                logError("Error en el proceso de responder: " . $e->getMessage());
                echo json_encode(["status" => "error", "message" => $e->getMessage()]);
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

        case "verificar_tramite":
            // Extraer y validar los datos enviados por POST
            $doc_id = isset($_POST['doc_id']) ? intval($_POST['doc_id']) : 0;

            if ($doc_id <= 0) {
                echo json_encode(["status" => "error", "message" => "ID de documento inválido."]);
                exit;
            }

            try {
                // Obtener el estado de seguimiento del documento
                $seguimiento = $documento->obtener_estado_tramite($doc_id);

                if ($seguimiento !== false) {
                    if ($seguimiento === 2 || $seguimiento === 3) {
                        echo json_encode(["status" => "tramitado", "message" => "Este documento ya ha sido tramitado."]);
                    } else {
                        echo json_encode(["status" => "success", "seguimiento" => $seguimiento]);
                    }
                } else {
                    echo json_encode(["status" => "error", "message" => "Error al obtener el estado del documento."]);
                }
            } catch (Exception $e) {
                logError("Error en el proceso de obtener estado del documento: " . $e->getMessage());
                echo json_encode(["status" => "error", "message" => $e->getMessage()]);
            }
            break;
            
        case "obtener_informacion_tramite":
            header('Content-Type: application/json; charset=utf-8');
            try {
                $doc_id = isset($_POST["doc_id"]) ? intval($_POST["doc_id"]) : 0;
                
                // Validar doc_id
                if ($doc_id > 0) {
                    // Llamar al método y obtener datos
                    $datos = $documento->obtenerInformacionTramite($doc_id);
                    
                    // Validar si los datos son un array o ya están codificados como JSON
                    if (is_array($datos)) {
                        $response = json_encode($datos, JSON_UNESCAPED_UNICODE);
                    } else {
                        $response = $datos; // Suponiendo que ya es JSON
                    }
                    
                    echo $response; // Enviar respuesta
                } else {
                    $error = ["status" => "error", "message" => "ID de documento inválido."];
                    echo json_encode($error);
                }
            } catch (Exception $e) {
                $error = ["status" => "error", "message" => $e->getMessage()];
                echo json_encode($error);
            }
        break;

        
        
        
    }

