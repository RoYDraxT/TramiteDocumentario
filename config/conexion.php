<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

class Conectar {
    protected $dbh;

    protected function Conexion() {
        try {
            $this->dbh = new PDO("mysql:host=localhost;dbname=tramitedocumentario", "root", "");
            $this->dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // Configurar el modo de error de PDO
            return $this->dbh;   
        } catch (Exception $e) {
            print "¡Error BD!: " . $e->getMessage() . "<br/>";
            die();  
        }
    }

    public function getConexion() {
        if ($this->dbh === null) {
            $this->Conexion();
        }
        return $this->dbh;
    }

    public function set_names() {   
        if ($this->dbh === null) {
            $this->Conexion();
        }
        return $this->dbh->query("SET NAMES 'utf8'");
    }

    public function ruta() {
        return "http://localhost:80/TramiteDocumentario/";
    }
}
?>
