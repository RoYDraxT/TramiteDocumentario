<?php
    require_once("../config/conexion.php");
    require_once("../models/Documento.php");
    $documento = new Documento();

    switch($_GET["op"]){

        case "insert":
            $datos = $documento->insert_partes($_POST["usu_id"]);
            if(is_array($datos)==true and count($datos)>0){
                foreach($datos as $row)
                {
                    $output["doc_id"] = $row["doc_id"];
                }
                echo json_encode($output);
            }
        break;

        case "update":
            $partes->update_partes($_POST["doc_id"],$_POST["doc_asun"],$_POST["doc_desc"]);
        break;

    }

