<?php
  require_once("../../config/conexion.php"); 
  if(isset($_SESSION["usu_id"])){ 
?>

<!DOCTYPE html>
<html lang="en" class="no-focus">
    <head>
        <?php require_once("../MainHead/MainHead.php");?> 
        
        <title>Consultar Status | Trámite Documentario</title>

    </head>
    <body>
        <div id="page-container" class="main-content-boxed">
            <!-- Ajustar ubicación del botón en el header -->
            <?php require_once("../MainHeader/MainHeader.php");?> 
            
            <!-- Contenido -->
            <main id="main-container">
                <div class="content">
                    <div class="block">
                        <div class="block-header block-header-default" style="display: flex; justify-content: space-between; align-items: center;">
                            <h3 class="block-title">Listado de Registros</h3>
                            <!-- Botón para redirigir a Nuevo Registro alineado a la derecha -->
                            <button type="button" class="btn btn-circle btn-dual-secondary" id="btn-registro">
                                <i class="fa fa-angle-left"></i> Regresar
                            </button>
                        </div>
                        <div class="block-content block-content-full">
                            <table id="doc_data" class="table table-bordered table-striped table-vcenter js-dataTable-full">
                                <thead>
                                    <tr>
                                        <th style="width: 10%;">Ticket</th>
                                        <th style="width: 15%;">Fecha</th>
                                        <th class="d-none d-sm-table-cell" style="width: 20%;">Asunto</th>
                                        <th class="d-none d-sm-table-cell" style="width: 65%;">Descripción</th>
                                        <th class="text-center" style="width: 15%;"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- Aquí va el contenido de la tabla -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </main>

            <div id="modaldetalle" class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Detalle de Documentos</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <table id="detalle_data" class="table" width="100%">
                                <thead>
                                    <tr>
                                        <th>Observación</th>
                                        <th>Archivo</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- Aquí va el contenido de la tabla de detalles -->
                                </tbody>
                            </table>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                        </div>
                    </div>
                </div>
            </div>

            <?php require_once("../MainFooter/MainFooter.php");?> 
        </div>

        <?php require_once("../MainJs/MainJs.php");?> 
        <script type="text/javascript" src="consultarstatus.js"></script>

        <!-- Script de redirección -->
        <script>
            document.getElementById('btn-registro').addEventListener('click', function() {
                window.location.href = "../NuevoRegistro/";
            });
        </script>
    </body>
</html>

<?php
  } else {
    $conectar = new Conectar();
    header("Location:" . $conectar->ruta() . "index.php");
  }
?>
