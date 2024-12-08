<?php
    // Incluir la conexión a la base de datos
    require_once("../config/conexion.php");

    if (isset($_GET["op"]) && $_GET["op"] == "validate") {
        $conectar = new Conectar();
        $conexion = $conectar->conexion();

        $dep_id = isset($_POST["dep_id"]) ? trim($_POST["dep_id"]) : null;

        // Verificar si el dep_id existe en la tabla
        $sql = "SELECT dep_nom FROM departamento WHERE dep_id = ?";
        $stmt = $conexion->prepare($sql);
        $stmt->bind_param("i", $dep_id); // "i" para entero
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            echo json_encode(["status" => "success", "department" => $row["dep_nom"]]);
        } else {
            echo json_encode(["status" => "error", "message" => "El código ingresado no coincide con ningún departamento."]);
        }

        $stmt->close();
        $conexion->close();
    }
?>

<html lang="en" class="no-focus">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
        
        <title>Iniciar Como Administrador | Trámite Documentario</title>

        <meta name="description" content="Codebase - Bootstrap 4 Admin Template &amp; UI Framework created by pixelcave and published on Themeforest">
        <meta name="author" content="pixelcave">
        <meta name="robots" content="noindex, nofollow">
        <meta property="og:title" content="Codebase - Bootstrap 4 Admin Template &amp; UI Framework">
        <meta property="og:site_name" content="Codebase">
        <meta property="og:description" content="Codebase - Bootstrap 4 Admin Template &amp; UI Framework created by pixelcave and published on Themeforest">
        <meta property="og:type" content="website">
        <meta property="og:url" content="">
        <meta property="og:image" content="">
        <link rel="shortcut icon" href="../public/assets/img/favicons/favicon.png">
        <link rel="icon" type="image/png" sizes="192x192" href="../public/assets/img/favicons/favicon-192x192.png">
        <link rel="apple-touch-icon" sizes="180x180" href="../public/assets/img/favicons/apple-touch-icon-180x180.png">
        <link rel="stylesheet" id="css-main" href="../public/assets/css/codebase.min.css">
    </head>
    <body>
        <div id="page-container" class="main-content-boxed">
            <main id="main-container">
                <div class="bg-body-dark bg-pattern" style="background-image: url('../public/assets/img/various/bg-pattern-inverse.png');">
                    <div class="row mx-0 justify-content-center">
                        <div class="hero-static col-lg-6 col-xl-4">
                            <div class="content content-full overflow-hidden">
                                <div class="py-30 text-center">
                                    <h1 class="h4 font-w700 mt-30 mb-10">UNALM</h1>
                                    <h2 class="h5 font-w400 text-muted mb-0">Trámite Documentario</h2>
                                </div>
                                <form class="js-validation-signup" action="be_pages_auth_all.html" method="post">
                                    <div class="block block-themed block-rounded block-shadow">
                                        <div class="block-header bg-gd-emerald">
                                            <h3 class="block-title">Ingresar Código de Seguridad</h3>
                                            <div class="block-options">
                                                <button type="button" class="btn-block-option">
                                                    <i class="si si-wrench"></i>
                                                </button>
                                            </div>
                                        </div>
                                        <div class="block-content">
                                            <div class="form-group row">
                                                <div class="col-12">
                                                    <input type="email" class="form-control" id="dep_id" name="dep_id">
                                                </div>
                                            </div>
                                            <div class="form-group row justify-content-center mb-0">
                                                <div class="col-sm-6 text-center">
                                                    <button type="button" class="btn btn-alt-success" id="btnadministrador">
                                                        <i class="fa fa-plus mr-10"></i> Ingresar
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="block-content bg-body-light">
                                            <div class="form-group text-center">
                                                <a class="link-effect text-muted mr-10 mb-5 d-inline-block" href="../">
                                                    <i class="fa fa-user text-muted mr-5"></i> Acceso
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </form>

                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>

        <script src="../public/assets/js/core/jquery.min.js"></script>
        <script src="../public/assets/js/core/popper.min.js"></script>
        <script src="../public/assets/js/core/bootstrap.min.js"></script>
        <script src="../public/assets/js/core/jquery.slimscroll.min.js"></script>
        <script src="../public/assets/js/core/jquery.scrollLock.min.js"></script>
        <script src="../public/assets/js/core/jquery.appear.min.js"></script>
        <script src="../public/assets/js/core/jquery.countTo.min.js"></script>
        <script src="../public/assets/js/core/js.cookie.min.js"></script>
        <script src="../public/assets/js/codebase.js"></script>
        <script src="../public/assets/js/plugins/jquery-validation/jquery.validate.min.js"></script>
        <script src="../public/assets/js/pages/op_auth_signin.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>
        <script type="text/javascript" src="iniciaradministrador.js"></script>



    </body>
</html>