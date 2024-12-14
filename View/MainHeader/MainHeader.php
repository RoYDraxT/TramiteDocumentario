<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once("../../config/conexion.php");

// Variables predeterminadas
$user_id = isset($_SESSION["usu_id"]) ? $_SESSION["usu_id"] : 0;
$user_nombre = "Administrador"; // Predeterminado si no hay sesión de usuario
$user_apellido = "";
$dep_nombre = ""; // Nombre del departamento

// Comprobar si la sesión contiene dep_id
if (isset($_SESSION["dep_id"])) {
    try {
        // Conexión a la base de datos
        $conectar = new Conectar();
        $conexion = $conectar->getConexion();

        // Obtener el nombre del departamento
        $sql = "SELECT dep_nom FROM departamento WHERE dep_id = ?";
        $stmt = $conexion->prepare($sql);
        $stmt->bindValue(1, $_SESSION["dep_id"], PDO::PARAM_INT);
        $stmt->execute();
        $departamento = $stmt->fetch(PDO::FETCH_ASSOC);

        // Asignar el nombre del departamento si existe
        if ($departamento) {
            $dep_nombre = ucfirst($departamento['dep_nom']);
        } else {
            $dep_nombre = "Departamento"; // Valor por defecto si no existe
        }
    } catch (Exception $e) {
        // Registrar errores
        file_put_contents("../../logs/error.log", "Error al obtener el departamento: " . $e->getMessage() . PHP_EOL, FILE_APPEND);
        $dep_nombre = "Departamento";
    }
}

// Si no existe dep_id, verificar si existe usu_id para mostrar el nombre del usuario
if (empty($dep_nombre) && isset($_SESSION["usu_nom"], $_SESSION["usu_ape"])) {
    $user_nombre = ucfirst($_SESSION["usu_nom"]);
    $user_apellido = ucfirst($_SESSION["usu_ape"]);
}
?>

<header id="page-header">
    <div class="content-header">
        <div class="content-header-section">
            <!-- Botón de menú opcional -->
        </div>

        <div class="content-header-section">
            <input type="hidden" id="useridx" class="form-control" value="<?php echo $user_id; ?>">

            <div class="btn-group" role="group">
                <button type="button" class="btn btn-rounded btn-dual-secondary" id="page-header-user-dropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <?php 
                    // Mostrar nombre del departamento si existe, de lo contrario mostrar el nombre del usuario
                    echo !empty($dep_nombre) ? $dep_nombre : $user_nombre . " " . $user_apellido; 
                    ?>
                    <i class="fa fa-angle-down ml-5"></i>
                </button>
                <div class="dropdown-menu dropdown-menu-right min-width-150" aria-labelledby="page-header-user-dropdown">
                    <a class="dropdown-item" href="../Logout/logout.php">
                        <i class="si si-logout mr-5"></i> Cerrar Sesión
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div id="page-header-loader" class="overlay-header bg-primary">
        <div class="content-header content-header-fullrow text-center">
            <div class="content-header-item">
                <i class="fa fa-sun-o fa-spin text-white"></i>
            </div>
        </div>
    </div>
</header>
