<?php
/*     require('class.phpmailer.php');
    include("class.smtp.php");

    require_once("../config/conexion.php");
    require_once("../Models/Usuario.php");

    class Email extends PHPMailer{
        private $admin_email;     // Correo de administración
        private $admin_password;  // Contraseña de administración

        public function __construct() {
            parent::__construct();

            // Configuración del correo y contraseña del administrador desde variables de entorno
            $this->admin_email = getenv('rodrigo25rtd@gmail.com');   // Usando una variable de entorno o archivo de configuración
            $this->admin_password = getenv('RoY2AL52005');
        
            $this->IsSMTP();
            $this->Host = 'smtp.gmail.com';
            $this->Port = 587;
            $this->SMTPAuth = true;
            $this->Username = $this->admin_email;
            $this->Password = $this->admin_password;
            $this->SMTPSecure = 'tls';
            $this->From = $this->admin_email;
            $this->CharSet = 'UTF-8';
            $this->IsHTML(true);
        }
        public function recuperar($usu_correo){
            $usuario = new Usuario();
            $datos = $usuario->get_correo_usuario($usu_correo);
            foreach ($datos as $row) {
                $nom = $row["usu_nom"].' '.$row["usu_ape"];
                $pass = $row["usu_pass"];
            }

            $this->IsSMTP();
            $this->Host = 'smtp.gmail.com';
            $this->Port = 587;                         // Cambiar puerto a 587 para TLS
            $this->SMTPAuth = true;
            $this->Username = $this->tu_email = "a";
            $this->Password = $this->tu_password = "a";        // Tu contraseña de Gmail o contraseña de app
            $this->SMTPSecure = 'tls';                 // Cambiar a 'tls' para Gmail
            $this->From = $this->tu_email;      // El correo que envía
            $this->CharSet = 'UTF-8';
            $this->addAddress($usu_correo);            // Dirección del destinatario
            $this->WordWrap = 50;
            $this->IsHTML(true);
            $this->Subject = "Recuperar Contraseña";
                $cuerpo = file_get_contents('../public/recuperar.html');
                $cuerpo = str_replace('lblnomx',$nom,$cuerpo);
                $cuerpo = str_replace('lblpassx',$pass,$cuerpo);
            $this->Body = $cuerpo;
            $this->IsHTML(true);
            return $this->Send();
        }

        public function nuevo($usu_correo){
            $usuario = new Usuario();
            $datos = $usuario->get_correo_usuario($usu_correo);
            foreach ($datos as $row) {
                $nom = $row["usu_nom"].' '.$row["usu_ape"];
            }

            $this->IsSMTP();
            $this->Host = 'smtp.gmail.com';
            $this->Port = 587;                         // Cambiar puerto a 587 para TLS
            $this->SMTPAuth = true;
            $this->Username = $this->tu_email = "rodrigo25rtd@gmail.com";
            $this->Password = $this->tu_password = "123";        // Tu contraseña de Gmail o contraseña de app
            $this->SMTPSecure = 'tls';                 // Cambiar a 'tls' para Gmail
            $this->From = $this->tu_email;      // El correo que envía
            $this->CharSet = 'UTF-8';
            $this->addAddress($usu_correo);            // Dirección del destinatario
            $this->WordWrap = 50;
            $this->IsHTML(true);
            $this->Subject = "Registro Correcto";
                $cuerpo = file_get_contents('../public/nuevo.html');
                $cuerpo = str_replace('lblnomx',$nom,$cuerpo);
            $this->Body = $cuerpo;
            $this->IsHTML(true);
            $this->AltBody = strip_tags("Registro Correcto");
            return $this->Send();
        }

        public function solicitud($doc_id,$usu_nom,$usu_ape){
            $this->IsSMTP();
            $this->Host = 'smtp.gmail.com';
            $this->Port = 587;                         // Cambiar puerto a 587 para TLS
            $this->SMTPAuth = true;
            $this->Username = $this->tu_email = "rodrigo25rtd@gmail.com";
            $this->Password = $this->tu_password = "123";        // Tu contraseña de Gmail o contraseña de app
            $this->SMTPSecure = 'tls';                 // Cambiar a 'tls' para Gmail
            $this->From = $this->tu_email;      // El correo que envía
            $this->CharSet = 'UTF-8';
            $this->addAddress("tu_correo@gmail.com");            // Dirección del destinatario
            $this->WordWrap = 50;
            $this->IsHTML(true);
            $this->Subject = "Nueva Solicitud";
                $cuerpo = file_get_contents('../public/solicitud.html');
                $cuerpo = str_replace('lblnomx',$usu_nom.' '.$usu_ape,$cuerpo);
                $cuerpo = str_replace('lblnumx',$doc_id,$cuerpo);
            $this->Body = $cuerpo;
            $this->IsHTML(true);
            $this->AltBody = strip_tags("Nueva Solicitud");
            return $this->Send();
        }

    }
 */