<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once("../../config/conexion.php");

function logError($message) {
    $logFile = "../../logs/error.log";
    $date = new DateTime();
    $formattedDate = $date->format('Y-m-d H:i:s');
    file_put_contents($logFile, "[$formattedDate] $message" . PHP_EOL, FILE_APPEND);
}

if (($_SESSION["dep_id"])) {
    // Obtener el ID del departamento desde la sesión
    $dep_id = $_SESSION['dep_id'];

    try {
        // Conectar a la base de datos
        $conectar = new Conectar();
        $conexion = $conectar->getConexion();
    
        // Consulta para obtener los documentos y la información del usuario
        $sql = "
            SELECT 
                d.doc_id, 
                d.fech_crea, 
                d.doc_asun, 
                d.doc_desc, 
                u.usu_nom, 
                u.usu_ape, 
                u.usu_dni 
            FROM documento d
            INNER JOIN usuario u ON d.usu_id = u.usu_id
            WHERE d.dep_id = ?
        ";
        $stmt = $conexion->prepare($sql);
        $stmt->bindValue(1, $dep_id, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (Exception $e) {
        logError("Error al obtener los documentos: " . $e->getMessage());
        header("Location: " . $conectar->ruta() . "index.php");
        exit();
    }    

?>

<!DOCTYPE html>
<html lang="en" class="no-focus">
    <head>
        <?php require_once("../MainHead/MainHead.php");?> 
        <title>Departamento Académico | Trámite Documentario</title>
    </head>
    <body>
        <div id="page-container" class="main-content-boxed">
            <?php require_once("../MainHeader/MainHeader.php");?> 
            
            <!-- Contenido -->
            <main id="main-container">
                <div class="content">
                    <div class="block">
                        <div class="block-header block-header-default" style="display: flex; justify-content: space-between; align-items: center;">
                            <h3 class="block-title">Listado de Trámites del Departamento</h3>
                        </div>
                        <div class="block-content block-content-full">
                            <table id="doc_data" class="table table-bordered table-striped table-vcenter js-dataTable-full">
                                <thead>
                                    <tr>
                                        <th style="width: 5%;">Ticket</th>
                                        <th style="width: 15%;">Nombre del Usuario</th>
                                        <th style="width: 10%;">DNI</th>
                                        <th style="width: 15%;">Fecha de Envío</th>
                                        <th style="width: 15%;">Asunto</th>
                                        <th style="width: 25%;">Descripción</th>
                                        <th style="width: 5%;">Acciones</th>
                                        <th style="width: 10%;">Tramitar</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($result as $row): ?>
                                        <tr>
                                            <td><?php echo $row['doc_id']; ?></td>
                                            <td><?php echo ucfirst($row['usu_nom']) . ' ' . ucfirst($row['usu_ape']); ?></td>
                                            <td><?php echo $row['usu_dni']; ?></td>
                                            <td><?php echo date("d-m-Y", strtotime($row['fech_crea'])); ?></td>
                                            <td><?php echo $row['doc_asun']; ?></td>
                                            <td><?php echo $row['doc_desc']; ?></td>
                                            <td class="text-center">
                                                <button class="btn btn-outline-info btn-icon" data-toggle="modal" data-target="#modaldetalle" data-id="<?php echo $row['doc_id']; ?>">
                                                    <i class="fa fa-database"></i>
                                                </button>
                                            </td>
                                            <td class="text-center">
                                                <button class="btn btn-secondary btn-tramitar btn-sm" data-toggle="modal" data-target="#modaltramitar" data-id="<?php echo $row['doc_id']; ?>">
                                                    Tramitar
                                                </button>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
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
            
            <div id="modaltramitar" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="modalTramitarLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Acciones de Trámite</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <!-- Pestañas -->
                            <ul class="nav nav-tabs" id="tabAcciones" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" id="responder-tab" data-toggle="tab" href="#responder" role="tab" aria-controls="responder" aria-selected="true">Responder</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="derivar-tab" data-toggle="tab" href="#derivar" role="tab" aria-controls="derivar" aria-selected="false">Derivar</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="anular-tab" data-toggle="tab" href="#anular" role="tab" aria-controls="anular" aria-selected="false">Anular</a>
                                </li>
                            </ul>

                            <!-- Contenido de las pestañas -->
                            <div class="tab-content" id="tabContentAcciones">

                                <div class="tab-pane fade show active" id="responder" role="tabpanel" aria-labelledby="responder-tab">
                                    <form id="form-responder">
                                        <div class="form-group">
                                            <textarea class="form-control" id="respuesta" rows="4" placeholder="Escribe aquí tu respuesta"></textarea>
                                        </div>
                                        <div class="form-group">
                                            <label for="archivo-respuesta">Adjuntar archivo (opcional):</label>
                                            <input type="file" class="form-control-file" id="archivo-respuesta">
                                        </div>
                                        <button type="button" id="btn-enviar-respuesta" class="btn btn-primary">Enviar</button>
                                    </form>
                                </div>

                                <div class="tab-pane fade" id="derivar" role="tabpanel" aria-labelledby="derivar-tab">
                                    <form id="form-derivar">
                                        <div class="form-group">
                                            <select id="select-departamento" class="form-control">
                                                <option value="">Seleccione un departamento</option>
                                            </select>
                                        </div>
                                        <button type="button" id="btn-enviar-derivar" class="btn btn-primary">Derivar</button>
                                    </form>
                                </div>

                                <div class="tab-pane fade" id="anular" role="tabpanel" aria-labelledby="anular-tab">
                                    <form id="form-anular" class="needs-validation">
                                        <div class="form-group">
                                            <textarea class="form-control" id="mensaje-anular" name="mensaje_anular" rows="4" placeholder="Motivo de la anulación" required></textarea>
                                            <div class="invalid-feedback">
                                                Por favor, ingresa un motivo para la anulación.
                                            </div>
                                        </div>
                                        <button type="button" id="btn-enviar-anular" class="btn btn-danger">Anular</button>
                                    </form>
                                </div>


                            </div>
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
        <script type="text/javascript">
            $(document).ready(function() {
                var dep_id_actual = '<?php echo $_SESSION['dep_id']; ?>';

                // AJAX para listar departamentos excluyendo el actual
                $.ajax({
                    url: "../../controller/documento.php?op=listar_departamento",
                    type: "POST",
                    data: { dep_id_actual: dep_id_actual },
                    success: function(response) {
                        try {
                            let departamentos = JSON.parse(response); // Intentar parsear como JSON
                            let select = $("#select-departamento"); // ID del select en tu HTML
                            select.empty(); // Limpiar opciones anteriores
                            departamentos.forEach(function(depto) {
                                select.append(`<option value="${depto.dep_id}">${depto.dep_nom}</option>`);
                            });
                        } catch (error) {
                            console.error("Error al parsear la respuesta JSON:", error);
                            console.log("Respuesta del servidor:", response);
                        }
                    },
                    error: function(error) {
                        console.error("Error al listar departamentos:", error);
                    }
                });

                $(document).on('click', '.btn-tramitar', function() {
                    var docId = $(this).data('id'); // Obtener el ID del documento
                    var tramitarButton = $(this);

                    $('#modaltramitar').data('id', docId);

                    if (tramitarButton.data('tramite-realizado')) {
                        // Si ya se realizó el trámite, abrir solo el modal sin actualizar la fecha ni seguimiento
                        $('#modaltramitar').data('id', docId);
                        return;
                    }

                    if (confirm('¿Estás seguro de tramitar este documento?')) {
                        tramitarButton.data('tramite-realizado', true); // Marcar como "realizado" para no volver a preguntar
                        // Realizar la solicitud AJAX para actualizar los datos
                        $.ajax({
                            url: '../../controller/documento.php?op=tramitar',
                            type: 'POST',
                            data: { doc_id: docId },
                            dataType: 'json', // Especificar el tipo de respuesta esperada
                            success: function(response) {
                                if (response.status === 'success') {
                                    // Cargar el modal después de tramitar
                                    $('#modaltramitar').data('id', docId);
                                } else {
                                    alert('Error: ' + response.message);
                                }
                            },
                            error: function(xhr, status, error) {
                                console.error('Error en la solicitud AJAX:', error);
                                alert('Error al tramitar el documento.');
                            }
                        });
                    }
                });

                $(document).on('click', '#btn-enviar-respuesta', function () {
                    var docId = $('#modaltramitar').data('id'); // Obtener el ID del documento desde el modal
                    var respuesta = $('#respuesta').val().trim(); // Capturar texto del textarea
                    var archivo = $('#archivo-respuesta')[0].files[0]; // Capturar archivo adjunto (si hay)

                    // Validar que haya una respuesta o un archivo
                    if (!respuesta && !archivo) {
                        alert('Por favor, escribe una respuesta o adjunta un archivo.');
                        return;
                    }

                    // Crear un objeto FormData para enviar los datos al servidor
                    var formData = new FormData();
                    formData.append('doc_id', docId);
                    formData.append('resd_obs', respuesta);
                    if (archivo) {
                        formData.append('resd_file', archivo);
                    }

                    // Enviar los datos mediante AJAX
                    $.ajax({
                        url: '../../controller/documento.php?op=responder', // Ruta al controlador
                        type: 'POST',
                        data: formData,
                        processData: false, // No procesar los datos
                        contentType: false, // No establecer Content-Type automáticamente
                        dataType: 'json', // Tipo de respuesta esperada
                        success: function (response) {
                            if (response.status === 'success') {
                                alert(response.message);
                                // Opcional: Cerrar modal o limpiar formulario
                                $('#form-responder')[0].reset();
                                $('#modaltramitar').modal('hide');
                            } else {
                                alert('Error: ' + response.message);
                            }
                        },
                        error: function (xhr, status, error) {
                            console.error('Error en la solicitud AJAX:', error);
                            alert('Ocurrió un error al procesar la respuesta.');
                        }
                    });
                });



                                
                $(document).on('click', '[data-target="#modaldetalle"]', function() {
                    var docId = $(this).data('id'); // Obtener el ID del documento

                    // Limpiar el contenido anterior del modal
                    $('#detalle_data tbody').empty();

                    // Llamada AJAX para obtener los detalles del documento
                    $.ajax({
                        url: '../../controller/documento.php?op=listardetalle_consulta',
                        type: 'POST',
                        data: { doc_id: docId },
                        success: function(response) {
                            var data = JSON.parse(response); // Convertir la respuesta a JSON

                            // Verificar si se recibieron datos
                            if (data.aaData.length > 0) {
                                $.each(data.aaData, function(index, detail) {
                                    // Agregar filas a la tabla
                                    $('#detalle_data tbody').append(
                                        '<tr>' +
                                        '<td>' + detail[0] + '</td>' + // Observación
                                        '<td><a href="../../public/src/' + detail[1] + '" target="_blank">Ver Archivo</a></td>' + // Enlace al archivo
                                        '</tr>'
                                    );
                                });
                            } else {
                                $('#detalle_data tbody').append('<tr><td colspan="2">No se encontraron detalles para este documento.</td></tr>');
                            }
                        },
                        error: function() {
                            $('#detalle_data tbody').html('<tr><td colspan="2">Error al cargar los detalles.</td></tr>');
                        }
                    });
                });

                $(document).on('click', '#btn-enviar-derivar', function () {
                    var departamento = $('#select-departamento').val(); // ID del departamento seleccionado
                    var docId = $('#modaltramitar').data('id'); // ID del documento asociado al modal

                    if (!departamento) {
                        alert('Por favor, selecciona un departamento antes de derivar.');
                        return;
                    }

                    // Llamada AJAX para actualizar la base de datos
                    $.ajax({
                        url: '../../controller/documento.php?op=derivar',
                        type: 'POST',
                        data: { 
                            doc_id: docId, 
                            dep_id: departamento 
                        },
                        success: function (response) {
                            var data = JSON.parse(response);

                            if (data.status === 'success') {                                
                                // Remover la fila del documento derivado
                                $('button[data-id="' + docId + '"]').closest('tr').remove();

                                // Cerrar el modal después de la acción
                                $('#modaltramitar').modal('hide'); // Línea que cierra el modal automáticamente
                            } else {
                                alert('Error: ' + data.message);
                            }
                        },
                        error: function () {
                            alert('Error al derivar el documento.');
                        }
                    });
                });

                $(document).on('click', '#btn-enviar-anular', function () {
                    var resd_obs = $('#mensaje-anular').val().trim(); // Obtener el motivo de anulación
                    var docId = $('#modaltramitar').data('id'); // ID del documento desde el modal

                    if (!docId) { 
                        alert('Error: No se pudo obtener el ID del documento.');
                        return;
                    }

                    if (!resd_obs) {
                        alert('Por favor, escribe un motivo antes de anular.');
                        return;
                    }

                    // Enviar los datos al servidor
                    $.ajax({
                        url: '../../controller/documento.php?op=anular',
                        type: 'POST',
                        data: {
                            doc_id: docId,
                            resd_obs: resd_obs
                        },
                        dataType: 'json',
                        success: function (response) {
                            if (response.status === 'success') {
                                alert(response.message);
                                // Opcional: Cerrar el modal y actualizar la vista
                                $('#modal-anular').modal('hide');
                                location.reload();
                            } else {
                                alert('Error: ' + response.message);
                            }
                        },
                        error: function (xhr, status, error) {
                            console.error('Error en la solicitud AJAX:', error);
                            alert('Ocurrió un error al procesar la anulación.');
                        }
                    });
                });





            });
        </script>
    </body>
</html>

<?php
} else {
    logError("Sesión no válida. Redireccionando al index principal.");
    $conectar = new Conectar();
    header("Location:" . $conectar->ruta() . "index.php");
}
?>
