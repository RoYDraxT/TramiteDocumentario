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

    public function actualizar_documento_anulacion($doc_id) {
        $sql = "UPDATE documento SET seguimiento = 3, fech_resp = NOW() WHERE doc_id = ?";
        $stmt = $this->getConexion()->prepare($sql);
        $stmt->bindValue(1, $doc_id, PDO::PARAM_INT);
        return $stmt->execute(); // Devuelve true si la ejecución es exitosa
    }

    public function insertar_detalle_anulacion($doc_id, $dep_id, $resd_obs) {
        $sql = "INSERT INTO detalleres (doc_id, dep_id, resd_obs, resd_file, fech_crea) VALUES (?, ?, ?, NULL, NOW())";
        $stmt = $this->getConexion()->prepare($sql);
        $stmt->bindValue(1, $doc_id, PDO::PARAM_INT);
        $stmt->bindValue(2, $dep_id, PDO::PARAM_INT);
        $stmt->bindValue(3, $resd_obs, PDO::PARAM_STR);
        return $stmt->execute(); // Devuelve true si la ejecución es exitosa
    }    
    

    public function actualizar_documento_respuesta($doc_id) {
        $this->set_names(); // Establecer el conjunto de caracteres

        $query = "UPDATE documento SET seguimiento = 2, fech_resp = NOW() WHERE doc_id = ?";
        $stmt = $this->getConexion()->prepare($query);
        $stmt->bindValue(1, $doc_id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function insertar_detalle_respuesta($doc_id, $dep_id, $resd_obs, $resd_file) {
        $this->set_names(); // Establecer el conjunto de caracteres

        $query = "INSERT INTO detalleres (doc_id, dep_id, resd_obs, resd_file, fech_crea) VALUES (?, ?, ?, ?, NOW())";
        $stmt = $this->getConexion()->prepare($query);
        $stmt->bindValue(1, $doc_id, PDO::PARAM_INT);
        $stmt->bindValue(2, $dep_id, PDO::PARAM_INT);
        $stmt->bindValue(3, $resd_obs, PDO::PARAM_STR);
        $stmt->bindValue(4, $resd_file, PDO::PARAM_STR);
        return $stmt->execute();
    }

    public function obtener_estado_tramite($doc_id) {
        $this->set_names(); // Establecer el conjunto de caracteres

        $query = "SELECT seguimiento FROM documento WHERE doc_id = ?";
        $stmt = $this->getConexion()->prepare($query);
        $stmt->bindValue(1, $doc_id, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($result) {
            return intval($result['seguimiento']);
        } else {
            return false;
        }
    }

    public function obtenerInformacionTramite($doc_id) {
        try {
            $this->set_names(); // Establecer el conjunto de caracteres

            // Consulta a la tabla `documento`
            $query = "SELECT d.doc_id, d.dep_id, d.fech_visto, d.fech_resp, d.seguimiento, dep.dep_nom 
                      FROM documento d 
                      INNER JOIN departamento dep ON d.dep_id = dep.dep_id 
                      WHERE d.doc_id = ?";
            $stmt = $this->getConexion()->prepare($query);
            $stmt->bindValue(1, $doc_id, PDO::PARAM_INT);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            // Validar si se encontró el documento
            if (!$result) {
                error_log("Documento no encontrado para doc_id: {$doc_id}");
                return json_encode(["status" => "error", "message" => "Documento no encontrado."]);
            }

            // Obtener detalles de `detalleres` si el seguimiento es `Respondido` o `Anulado`
            $respuesta = null;
            if ($result['seguimiento'] == 2 || $result['seguimiento'] == 3) {
                $query_respuesta = "SELECT resd_obs, resd_file FROM detalleres WHERE doc_id = ?";
                $stmt_respuesta = $this->getConexion()->prepare($query_respuesta);
                $stmt_respuesta->bindValue(1, $doc_id, PDO::PARAM_INT);
                $stmt_respuesta->execute();
                $respuesta = $stmt_respuesta->fetch(PDO::FETCH_ASSOC);
            }
            
            return json_encode([
                "status" => "success", 
                "dep_nom" => $result['dep_nom'], 
                "fech_visto" => $result['fech_visto'], 
                "fech_resp" => $result['fech_resp'], 
                "seguimiento" => $result['seguimiento'],
                "respuesta" => $respuesta
            ]);
        } catch (Exception $e) {
            error_log("Error en obtenerInformacionTramite: " . $e->getMessage());
            return json_encode(["status" => "error", "message" => "Ocurrió un error al procesar los datos."]);
        }
    }
    
}
?>
