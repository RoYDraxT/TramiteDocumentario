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

if (isset($_SESSION["usu_id"])) {
    // Obtener el ID del usuario desde la sesión
    $usu_id = $_SESSION['usu_id'];

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
            WHERE d.usu_id = ?
        ";
        $stmt = $conexion->prepare($sql);
        $stmt->bindValue(1, $usu_id, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Obtener nombre y apellido del usuario autenticado
        $sqlUser = "SELECT usu_nom, usu_ape FROM usuario WHERE usu_id = ?";
        $stmtUser = $conexion->prepare($sqlUser);
        $stmtUser->bindValue(1, $usu_id, PDO::PARAM_INT);
        $stmtUser->execute();
        $user = $stmtUser->fetch(PDO::FETCH_ASSOC);

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
                            <h3 class="block-title">Lista de Trámites</h3>
                            <button onclick="location.href='../Home/index.php';" class="btn btn-primary">Regresar</button>
                        </div>
                        <div class="block-content block-content-full">
                            <table id="doc_data" class="table table-bordered table-striped table-vcenter js-dataTable-full">
                                <thead>
                                    <tr>
                                        <th style="width: 5%;">Ticket</th>
                                        <th style="width: 15%;">Fecha de Envío</th>
                                        <th style="width: 15%;">Asunto</th>
                                        <th style="width: 25%;">Descripción</th>
                                        <th style="width: 5%;">Archivos</th>
                                        <th style="width: 10%;">Tramitar</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($result as $row): ?>
                                        <tr>
                                            <td><?php echo $row['doc_id']; ?></td>
                                            <td><?php echo date("d-m-Y", strtotime($row['fech_crea'])); ?></td>
                                            <td><?php echo $row['doc_asun']; ?></td>
                                            <td><?php echo $row['doc_desc']; ?></td>
                                            <td class="text-center">
                                                <button class="btn btn-outline-info btn-icon" data-toggle="modal" data-target="#modaldetalle" data-id="<?php echo $row['doc_id']; ?>">
                                                    <i class="fa fa-database"></i>
                                                </button>
                                            </td>
                                            <td class="text-center">
                                                <button class="btn btn-secondary btn-tramitar btn-sm" data-toggle="modal" data-target="#modalconsulta" data-id="<?php echo $row['doc_id']; ?>">
                                                    Consultar
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
            
            <div id="modalconsulta" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Información del Trámite</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <!-- Aquí se mostrará la información del trámite -->
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

            $(document).on('click', '[data-target="#modalconsulta"]', function() {
                var docId = $(this).data('id'); // Obtener el ID del documento

                // Llamada AJAX para obtener información del trámite
                $.ajax({
                    url: '../../controller/documento.php?op=obtener_informacion_tramite',
                    type: 'POST',
                    data: { doc_id: docId },
                    dataType: 'json',
                    success: function(response) {
                        console.log("Respuesta recibida del servidor:", response);

                        // Verificar si se recibieron datos correctamente
                        if (response.status === 'success') {
                            // Determinar el estado de seguimiento
                            var estadoSeguimiento;
                            switch (response.seguimiento) {
                                case '0':
                                    estadoSeguimiento = 'No visto';
                                    break;
                                case '1':
                                    estadoSeguimiento = 'Visto';
                                    break;
                                case '2':
                                    estadoSeguimiento = 'Respondido';
                                    break;
                                case '3':
                                    estadoSeguimiento = 'Anulado';
                                    break;
                                default:
                                    estadoSeguimiento = 'Desconocido';
                            }

                            // Construir el contenido del modal
                            var contenidoModal = 
                                '<div><strong>Departamento:</strong> ' + response.dep_nom + '</div>' +
                                '<div><strong>Fecha de Visto:</strong> ' + (response.fech_visto ? response.fech_visto : 'No realizado') + '</div>' +
                                '<div><strong>Fecha de Respuesta:</strong> ' + (response.fech_resp ? response.fech_resp : 'No respondido') + '</div>' +
                                '<div><strong>Estado:</strong> ' + estadoSeguimiento + '</div>';
                            
                            // Añadir mensaje si el estado es Respondido o Anulado
                            if (response.respuesta) {
                                contenidoModal += 
                                    '<div><strong>Mensaje:</strong> ' + response.respuesta.resd_obs + '</div>';
                                
                                // Añadir archivo adjunto si está disponible
                                if (response.respuesta.resd_file) {
                                    contenidoModal += 
                                        '<div><strong>Archivo:</strong> <a href="../../public/src/' + response.respuesta.resd_file + '" target="_blank">Ver Archivo</a></div>';
                                }
                            }

                            // Mostrar el contenido en el modal
                            $('#modalconsulta .modal-body').html(contenidoModal);

                            // Abrir el modal
                            $("#modalconsulta").modal("show");
                        } else {
                            alert("Error: " + response.message);
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('Error en la solicitud AJAX:', error);
                        console.log('Estado de la solicitud:', status);
                        console.log('Respuesta del servidor:', xhr.responseText);
                        alert('Error al consultar el trámite.');
                    }
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
