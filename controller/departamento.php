<?php
require_once("../config/conexion.php");
require_once("../models/Departamento.php");

$departamento = new Departamento();

switch($_GET["op"]) {
    case "validate":
        $dep_id = isset($_POST["dep_id"]) ? trim($_POST["dep_id"]) : null;

        $result = $departamento->validate_dep_id($dep_id);
        
        if ($result) {
            echo json_encode(["status" => "success", "department" => $result["dep_nom"]]);
        } else {
            echo json_encode(["status" => "error", "message" => "El código ingresado no coincide con ningún departamento."]);
        }
        break;
}
?>
