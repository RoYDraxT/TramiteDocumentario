<?php
  require_once("../../config/conexion.php"); 
  if(isset($_SESSION["usu_id"])){ 
?>

<html lang="en" class="no-focus">
    <head>
        <?php require_once("../MainHead/MainHead.php");?> 
        
        <title>Home | Trámite Documentario</title>
    </head>
    <body>
        <div id="page-container" class="page-header-modern main-content-boxed">
            <?php require_once("../MainHeader/MainHeader.php");?> 
            
            <!-- Contenido -->
            <main id="main-container">
                <div class="content">
                    <!-- Encabezado Principal -->
                    <div class="block">
                        <div class="block-header block-header-default">
                            <h3 class="block-title">Bienvenido al Sistema de Gestión Documentaria</h3>
                        </div>
                        <div class="block-content">
                            <p>Estamos aquí para ayudarte a gestionar de manera rápida y eficiente todos tus documentos y trámites institucionales. Este sistema está diseñado para facilitar el seguimiento de tus solicitudes en cualquier momento y desde cualquier lugar.</p>
                        </div>
                    </div>

                    <!-- Botones de Acción -->
                    <div class="block">
                        <div class="block-content d-flex justify-content-center bg-white py-3">
                            <button type="button" class="btn btn-alt-primary btn-lg mx-3" onclick="location.href='../NuevoRegistro/index.php'">
                                Nuevo Registro
                            </button>
                            <button type="button" class="btn btn-alt-info btn-lg mx-3" onclick="location.href='../ConsultarStatus/index.php'">
                                Consultar Status
                            </button>
                        </div>
                    </div>

                    <!-- Sección Informativa sobre Trámite Documentario -->
                    <div class="block">
                        <div class="block-header block-header-default bg-white">
                            <h3 class="block-title">¿Qué es el Sistema de Trámite Documentario?</h3>
                        </div>
                        <div class="block-content">
                            <p>Nuestro Sistema de Trámite Documentario es una herramienta integral diseñada para que puedas gestionar tus documentos de manera sencilla y eficiente dentro de la organización. Aquí podrás:</p>
                            <ul>
                                <li>Registrar nuevos documentos en pocos pasos.</li>
                                <li>Consultar el estado de tus solicitudes en tiempo real.</li>
                                <li>Mantener un control detallado de todos tus trámites, asegurando transparencia y orden en el proceso.</li>
                            </ul>
                            <p>Con nuestro sistema, nos aseguramos de que cada documento llegue a su destino de forma segura y sin contratiempos.</p>
                            <!-- Imagen Ejemplo -->
                            <img src="..\..\public\assets\img\photos\nuevoregistro.PNG" alt="Ejemplo de Trámite Documentario" class="img-fluid">
                        </div>
                    </div>


                    <!-- Sección Informativa sobre Cómo Consultar Status -->
                    <div class="block">
                        <div class="block-header block-header-default bg-white">
                            <h3 class="block-title">¿Cómo Consultar el Estado de un Trámite?</h3>
                        </div>
                        <div class="block-content">
                            <p>Consultar el estado de un trámite en nuestro sistema es muy sencillo. Solo necesitas seguir estos pasos:</p>
                            <ol>
                                <li>Accede a la sección <b>Consultar Status</b> haciendo clic en el botón correspondiente.</li>
                                <li>Introduce el número de referencia del documento que deseas consultar.</li>
                                <li>Haz clic en <b>Buscar</b> y el sistema te mostrará el estado actual de tu trámite.</li>
                            </ol>
                            <p>Puedes ver detalles como la fecha de registro, el estado actual, y cualquier acción pendiente o completada.</p>
                            <!-- Imagen Ejemplo de Consultar Status -->
                            <img src="..\..\public\assets\img\photos\consultarstatus.PNG" alt="Consulta de Status" class="img-fluid">
                        </div>
                    </div>


                    <!-- Sección de Recursos y Ayuda -->
                    <div class="block">
                        <div class="block-header block-header-default bg-white">
                            <h3 class="block-title">Recursos y Ayuda</h3>
                        </div>
                        <div class="block-content">
                            <ul>
                                <li><a href="FAQs/index.php">Preguntas Frecuentes</a></li>
                                <li><a href="Contacto/index.php">Contacto de Soporte</a></li>
                            </ul>
                            <p>Si tienes alguna duda o necesitas ayuda adicional, no dudes en visitar nuestras Preguntas Frecuentes o ponerte en contacto con nuestro equipo de soporte.</p>
                        </div>
                    </div>
                </div>
            </main>
            <!-- Contenido -->

            <?php require_once("../MainFooter/MainFooter.php");?> 
        </div>

        <?php require_once("../MainJs/MainJs.php");?> 
    </body>
</html>

<?php
  } else {
    $conectar = new Conectar();
    header("Location:" . $conectar->ruta() . "index.php");
  }
?>
