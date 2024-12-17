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
        $sql="insert into detalledoc values (null, ?, ?, ?, now(), 1);";
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
        $sql="select * from detalledoc where doc_id = ? and est = 1;";
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
        $sql="select * from documento where usu_id = ? and est = 1;";
        $sql=$conectar->prepare($sql);
        $sql->bindValue(1, $usu_id);
        $sql->execute();
        return $resultado=$sql->fetchAll(PDO::FETCH_ASSOC);
    }

    public function list_departamento($dep_id_actual) {
        $sql = "SELECT dep_id, dep_nom FROM departamento WHERE dep_id != ?";
        $stmt = $this->getConexion()->prepare($sql);
        $stmt->bindValue(1, $dep_id_actual, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }   
    
    public function derivar_documento($doc_id, $new_dep_id) {
        $sql = "UPDATE documento SET dep_id = ? WHERE doc_id = ?";
        $stmt = $this->getConexion()->prepare($sql);
        $stmt->bindValue(1, $new_dep_id, PDO::PARAM_INT);
        $stmt->bindValue(2, $doc_id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    // Método para anular un documento
    public function anular_documento($doc_id, $resd_obs, $dep_id) {
        $conectar = parent::conexion();
        parent::set_names();
        try {
            // Verificar sesión
            if (!isset($_SESSION['dep_id'])) {
                throw new Exception("No se ha encontrado el ID del departamento en la sesión.");
            }
    
            logError("Sesión verificada - dep_id: " . $_SESSION['dep_id']);
    
            // Verificar que el documento exista
            $sql_check_doc = "SELECT 1 FROM documento WHERE doc_id = ?";
            $stmt_check_doc = $conectar->prepare($sql_check_doc);
            $stmt_check_doc->bindValue(1, $doc_id, PDO::PARAM_INT);
            $stmt_check_doc->execute();
    
            if ($stmt_check_doc->rowCount() == 0) {
                throw new Exception("No se encontró el documento con ID: $doc_id para actualizar.");
            }
    
            logError("Documento encontrado - doc_id: $doc_id");
    
            // 1. Actualizar o insertar en la tabla `documento`
            $sql_doc = "update documento SET seguimiento = 3, fech_resp = NOW() WHERE doc_id = ?";
            $stmt_doc = $conectar->prepare($sql_doc);
            $stmt_doc->bindValue(1, $doc_id, PDO::PARAM_INT);
            $stmt_doc->execute();
    
            logError("Documento actualizado - doc_id: $doc_id");
    
            // 2. Insertar detalle en la tabla `detalleres`
            $sql_detalle = "insert INTO detalleres (doc_id, dep_id, resd_obs, resd_file, fech_crea) VALUES (?, ?, ?, NULL, NOW())";
            $stmt_detalle = $conectar->prepare($sql_detalle);
            $stmt_detalle->bindValue(1, $doc_id, PDO::PARAM_INT);
            $stmt_detalle->bindValue(2, $dep_id, PDO::PARAM_INT);
            $stmt_detalle->bindValue(3, $resd_obs, PDO::PARAM_STR);
            $stmt_detalle->execute();
    
            logError("Detalle insertado - doc_id: $doc_id, dep_id: " . $_SESSION['dep_id'] . ", mensaje: $resd_obs");
    
            // Confirmar la transacción si todo está bien
            $conectar->commit();
            return true;
    
        } catch (PDOException $e) {
            // Revertir la transacción en caso de error
            $conectar->rollBack();
            logError("Error al anular el documento: " . $e->getMessage());
            return false;
        } catch (Exception $ex) {
            $conectar->rollBack();
            logError($ex->getMessage());
            return false;
        }
    }
    
    public function actualizar_documento_respuesta($doc_id) {
        try {
            $sql = "UPDATE documento SET seguimiento = 2, fech_resp = NOW() WHERE doc_id = ?";
            $stmt = $this->getConexion()->prepare($sql);
            $stmt->bindValue(1, $doc_id, PDO::PARAM_INT);
    
            if ($stmt->execute()) {
                return true;
            } else {
                throw new Exception("No se pudo actualizar el estado de seguimiento del documento.");
            }
        } catch (PDOException $e) {
            logError("Error al responder documento: " . $e->getMessage());
            return false;
        }
    }    
     
    public function insertar_detalle_respuesta($doc_id, $dep_id, $resd_obs, $resd_file) {
        $sql = "INSERT INTO detalleres (doc_id, dep_id, resd_obs, resd_file, fech_crea) VALUES (?, ?, ?, ?, NOW())";
        $stmt = $this->getConexion()->prepare($sql);
        $stmt->bindValue(1, $doc_id, PDO::PARAM_INT);
        $stmt->bindValue(2, $dep_id, PDO::PARAM_INT);
        $stmt->bindValue(3, $resd_obs, PDO::PARAM_STR);
        $stmt->bindValue(4, $resd_file, PDO::PARAM_STR);
        return $stmt->execute();
    }    

    
}
?>
