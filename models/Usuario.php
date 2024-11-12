<?php
    class Usuario extends Conectar {

        public function login(){
			$conectar=parent::Conexion();
			parent::set_names();
			if(isset($_POST["enviar"])){
				
				$password = $_POST["password"];
				$correo = $_POST["correo"];

				if(empty($correo) and empty($password)){
					header("Location:".Conectar::ruta()."index.php?m=2");
					exit();
				}
			else {
				$sql= "select * from usuario where usu_correo=? and usu_pass=? and est=1";
				$sql=$conectar->prepare($sql);
				$sql->bindValue(1, $correo);
				$sql->bindValue(2, $password);
				$sql->execute();
				$resultado = $sql->fetch();
					if(is_array($resultado) and count($resultado)>0){
						$_SESSION["usu_id"] = $resultado["usu_id"];
                        $_SESSION["usu_dni"] = $resultado["usu_dni"];
                        $_SESSION["usu_nom"] = $resultado["usu_nom"];
                        $_SESSION["usu_ape"] = $resultado["usu_ape"];
						$_SESSION["usu_correo"] = $resultado["usu_correo"];
						header("Location:".Conectar::ruta()."view/Home/");
						exit(); 
					} else {
						header("Location:".Conectar::ruta()."index.php?m=1");
						exit();
					} 
				}
			}
		}

		public function insert_usuario($usu_id,$usu_dni,$usu_nom,$usu_ape,$usu_correo,$usu_pass){
            $conectar= parent::conexion();
            parent::set_names();
            $sql="INSERT INTO usuario values (?,?,?,?,?,?,NULL, NULL, NULL, '1');";
            $sql=$conectar->prepare($sql);
			$sql->bindValue(1,$usu_id);
			$sql->bindValue(2,$usu_dni);
            $sql->bindValue(3,$usu_nom);
            $sql->bindValue(4,$usu_ape);
			$sql->bindValue(5,$usu_correo);
			$sql->bindValue(6,$usu_pass);
            $sql->execute();
		}

		public function get_correo_usuario($usu_correo){
            $conectar= parent::conexion();
            parent::set_names();
            $sql="SELECT * FROM usuario WHERE usu_correo=? AND est=1;";
            $sql=$conectar->prepare($sql);
			$sql->bindValue(1,$usu_correo);
			$sql->execute();
			return $resultado=$sql->fetchAll();
        }

    }