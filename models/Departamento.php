<?php
class Departamento extends Conectar {

    public function validate_dep_id($dep_id) {
        $conectar = parent::conexion();
        parent::set_names();
        
        $sql = "SELECT dep_nom FROM departamento WHERE dep_id = ?";
        $sql = $conectar->prepare($sql);
        $sql->bindValue(1, $dep_id);
        $sql->execute();
        
        $result = $sql->fetch(PDO::FETCH_ASSOC);

        return $result;
    }
}
?>
