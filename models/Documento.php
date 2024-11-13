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
            return $sql1->fetchall(pdo::FETCH_ASSOC);
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

    }