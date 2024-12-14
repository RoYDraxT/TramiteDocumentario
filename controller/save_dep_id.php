<?php
session_start();
require_once("../config/conexion.php");

if (isset($_POST["dep_id"])) {
    $_SESSION["dep_id"] = $_POST["dep_id"];
    echo json_encode(["status" => "success"]);
} else {
    echo json_encode(["status" => "error", "message" => "No se recibió el dep_id."]);
}
?>
