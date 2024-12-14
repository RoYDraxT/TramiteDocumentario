<?php
class Documento extends Conectar {

    // Método para insertar un documento
    public function insert_documento($usu_id, $dep_id){
        $conectar=parent::conexion();
        parent::set_names();
        $sql="insert into documento values (null, ?, ?, null, null, now(), null, null, 0, 2);";
        $sql=$conectar->prepare($sql);
        $sql->bindValue(1, $usu_id);
        $sql->bindValue(2, $dep_id);
        $sql->execute();
        
        // Recuperar el último ID insertado
        $sql1="select last_insert_id() as 'doc_id';";
        $sql1=$conectar->prepare($sql1);
        $sql1->execute();
        return $resultado=$sql1->fetchAll(PDO::FETCH_ASSOC);
    }

    // Método para actualizar un documento
    public function update_documento($doc_id, $doc_asun, $doc_desc, $dep_id){
        $conectar=parent::conexion();
        parent::set_names();
        $sql="update documento set
                doc_asun = ?,
                doc_desc = ?,
                dep_id = ?,
                seguimiento = 0,
                est = 1
              where doc_id = ?;";
        $sql=$conectar->prepare($sql);
        $sql->bindValue(1, $doc_asun);
        $sql->bindValue(2, $doc_desc);
        $sql->bindValue(3, $dep_id);
        $sql->bindValue(4, $doc_id);
        $sql->execute();
    }

    // Método para insertar un detalle del documento
    public function insert_docdetalle($doc_id, $docd_obs, $docd_file){
        $conectar=parent::conexion();
        parent::set_names();

        // Verificar si hay archivo cargado
        $docd_file = '';
        if(isset($_FILES["docd_file"]["name"]) && $_FILES["docd_file"]["name"] != ''){
            $docd_file = $this->upload_file();
        }else if(isset($_POST["hidden_file_imagen"])){
            $docd_file = $_POST["hidden_file_imagen"];
        }

        // Insertar el detalle del documento
        $sql="insert into detalledoc values (null, ?, ?, ?, now(), 0, 1);";
        $sql=$conectar->prepare($sql);
        $sql->bindValue(1, $doc_id);
        $sql->bindValue(2, $docd_obs);
        $sql->bindValue(3, $docd_file);
        $sql->execute();
    }

    // Método para subir un archivo
    public function upload_file(){
        if(isset($_FILES["docd_file"])){
            $extension = pathinfo($_FILES['docd_file']['name'], PATHINFO_EXTENSION);
            $new_name = uniqid() . '.' . $extension;
            $destination = '../public/src/' . $new_name;
            move_uploaded_file($_FILES['docd_file']['tmp_name'], $destination);
            return $new_name;
        }
        return '';
    }

    // Método para listar detalles de documento
    public function list_docdetalle($doc_id){
        $conectar=parent::conexion();
        parent::set_names();
        $sql="select * from detalledoc where doc_id = ? and seguimiento = 0 and est = 1;";
        $sql=$conectar->prepare($sql);
        $sql->bindValue(1, $doc_id);
        $sql->execute();
        return $resultado=$sql->fetchAll(PDO::FETCH_ASSOC);
    }

    // Método para eliminar detalle
    public function delete_docdetalle($docd_id){
        $conectar=parent::conexion();
        parent::set_names();
        $sql="update detalledoc set est = 0 where docd_id = ?;";
        $sql=$conectar->prepare($sql);
        $sql->bindValue(1, $docd_id);
        $sql->execute();
    }

    // Método para listar documentos
    public function list_doc($usu_id){
        $conectar=parent::conexion();
        parent::set_names();
        $sql="select * from documento where usu_id = ? and seguimiento = 0 and est = 1;";
        $sql=$conectar->prepare($sql);
        $sql->bindValue(1, $usu_id);
        $sql->execute();
        return $resultado=$sql->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
