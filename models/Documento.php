<?php
    class Documento extends Conectar {

        public function insert_documento($usu_id){
            $conectar=parent::conexion();
            parent::set_names();
            $sql="insert into documento values (null, ?, null,null,now(),null,null,2);";
            $sql=$conectar->prepare($sql);
            $sql->bindvalue(1, $usu_id);
            $sql->execute();

            $sql1="select last_insert_id() as 'doc_id';";
            $sql1=$conectar->prepare($sql1);
            $sql1->execute();
            return $resultado=$sql1->fetchall(pdo::FETCH_ASSOC);
        }

        public function update_documento($doc_id,$doc_asun,$doc_desc){
            $conectar=parent::conexion();
            parent::set_names();
            $sql="update documento set
                    doc_asun=?,
                    doc_desc=?,
                    est=1
                where
                    doc_id=?;";
            $sql=$conectar->prepare($sql);
            $sql->bindvalue(1, $doc_asun);
            $sql->bindvalue(2, $doc_desc);
            $sql->bindvalue(3, $doc_id);
            $sql->execute();
        }

        public function insert_docdetalle($doc_id,$docd_obs,$docd_file){
            $conectar=parent::conexion();
            parent::set_names();

            require_once("Documento.php");
            $docx = new Documento();
            $docd_file = '';
            if($_FILES["docd_file"]["name"] != '')
            {
                $docd_file = $docx->upload_file();
            }else{
                $docd_file = $_POST["hidden_file_imagen"];
            }

            $sql="insert into detalledoc values (null, ?, ?,?,now(),1);";
            $sql=$conectar->prepare($sql);
            $sql->bindvalue(1, $doc_id);
            $sql->bindvalue(2, $docd_obs);
            $sql->bindvalue(3, $docd_file);
            $sql->execute();
        }

        public function upload_file(){
            if(isset($_FILES["docd_file"]))
            {
              $extension = explode('.', $_FILES['docd_file']['name']);
              $new_name = rand() . '.' . $extension[1];
              $destination = '../public/src/' . $new_name;
              move_uploaded_file($_FILES['docd_file']['tmp_name'], $destination);
              return $new_name;
            }
        }

        public function list_docdetalle($doc_id){
            $conectar=parent::conexion();
            parent::set_names();
            $sql="select * from detalledoc where doc_id=? and est=1;";
            $sql=$conectar->prepare($sql);
            $sql->bindvalue(1, $doc_id);
            $sql->execute();
            return $resultado=$sql->fetchall(pdo::FETCH_ASSOC);
        }

        public function delete_docdetalle($docd_id){
            $conectar=parent::conexion();
            parent::set_names();
            $sql="update detalledoc set est=0 where docd_id=?;";
            $sql=$conectar->prepare($sql);
            $sql->bindvalue(1, $docd_id);
            $sql->execute();
        }

        public function list_doc($usu_id){
            $conectar=parent::conexion();
            parent::set_names();
            $sql="select * from documento where usu_id=? and est=1;";
            $sql=$conectar->prepare($sql);
            $sql->bindvalue(1, $usu_id);
            $sql->execute();
            return $resultado=$sql->fetchall(pdo::FETCH_ASSOC);
        }
    }