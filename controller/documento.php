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
            
            echo json_encode(["status" => "success", "message" => "Documento actualizado correctamente"]);

        break;

    }

