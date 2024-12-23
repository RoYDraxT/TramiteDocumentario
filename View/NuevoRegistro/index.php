<?php
  require_once("../../config/conexion.php"); 
  if(isset($_SESSION["usu_id"])){ 
?>

<html lang="en" class="no-focus">
    <head>
        <?php require_once("../MainHead/MainHead.php");?> 
        
        <title>Nuevo Registro | Trámite Documentario</title>
    </head>
    <body>
        <div id="page-container" class="main-content-boxed"> <!-- Barra superior -->
            <?php require_once("../MainHeader/MainHeader.php");?> 
            
            <!-- Contenido -->
            <div id="page-container" class="main-content-boxed"> <!-- Para centrar luego de eliminar la barra lateral -->
                <main id="main-container">
                    <div class="content">
                        <div class="block">
                            <div class="block-header block-header-default" style="display: flex; justify-content: space-between; align-items: center;">
                                <h3 class="block-title">Nuevo Registro</h3>
                                <input type="hidden" id="doc_id" class="form-control"><!-- ID del Usuario useridx-->
                            </div>
                            <div class="block-content block-content-full">
                                <div class="form-group row">
                                    <label class="col-12" for="doc_asun">Asunto</label>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control" id="doc_asun" name="doc_asun">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-12" for="doc_desc">Descripción</label>
                                    <div class="col-12">
                                        <textarea class="form-control" id="doc_desc" name="doc_desc" rows="6"></textarea>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-12" for="dep_id">Departamento</label>
                                    <div class="col-md-9">
                                        <select class="form-control" id="dep_id" name="dep_id">
                                            <option value="1463">Departamentos Académico</option>
                                            <option value="8479">Alta Dirección</option>
                                            <option value="5495">Facultades</option>
                                            <option value="5189">Órganos de Gobierno</option>
                                            <option value="6447">Escuela de Post Grado</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="block-content block-content-sm block-content-full bg-body-light">
                                    <div class="row">
                                        <div class="col-6">
                                            <button type="button" class="btn btn-alt-info" id="btnadd">
                                                <i class="fa fa-share-alt mr-5"></i> Adjuntar Documentos
                                            </button>
                                        </div>
                                        <div class="col-6 text-right">
                                            <button type="button" class="btn btn-alt-primary" id="btnguardar" onclick="location.href='../Home/index.php'">
                                                Enviar <i class="fa fa-save ml-5"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="block">
                            <div class="block-header block-header-default">
                                <h3 class="block-title">Listado de Documentos</h3>
                            </div>
                            <div class="block-content block-content-full">
                                <table id="detalle_data" class="table table-bordered table-striped table-vcenter js-dataTable-full">
                                    <thead>
                                        <tr>
                                            <th class="text-center">Observación</th>
                                            <th>Archivo</th>
                                            <th class="text-center" style="width: 15%;">Acción</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </main>
            </div>
            <!-- Contenido -->

        <?php require_once("modalarchivo.php");?> 

        <?php require_once("../MainFooter/MainFooter.php");?> 

        </div>

        <?php require_once("../MainJs/MainJs.php");?> 
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>
        <script type="text/javascript" src="nuevoregistro.js"></script>

    </body>
</html>

<?php
  } else {
    $conectar = new Conectar();
    header("Location:" . $conectar->ruta() . "index.php");
  }
?>
