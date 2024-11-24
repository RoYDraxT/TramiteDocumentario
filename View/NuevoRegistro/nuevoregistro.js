var usu_id = $("#useridx").val();

function init(){
    $("#detalle_form").on("submit",function(e){
        guardaryeditar(e);	
    });
}

$(document).ready(function(){
    $.post("../../controller/documento.php?op=insert",{usu_id:usu_id},function(data){
        data = JSON.parse(data);
        $('#doc_id').val(data.doc_id);

        llenartabla(data.doc_id);
    });
});

function guardaryeditar(e){
    e.preventDefault();
    var formData = new FormData($("#detalle_form")[0]);
    formData.append("doc_id", $("#doc_id").val());
    $.ajax({
        url: "../../controller/documento.php?op=insertdetalle",
        type: "POST",
        data: formData,
        contentType: false,
        processData: false,
        success: function(datos){ 
            Swal.fire(
                'Mesa De Partes',
                'Se registro Correctamente',
                'success'
            );
            $("#modalarchivo").modal('hide');
            var doc_id =  $('#doc_id').val();
            llenartabla(doc_id);
        }
    });        
}



$(document).on("click","#btnguardar", function(){
    var doc_id = $("#doc_id").val();
    var doc_asun = $("#doc_asun").val();
    var doc_desc = $("#doc_desc").val();

    if(doc_asun=='' || doc_desc==''){
        Swal.fire(
            'Mesa De Partes',
            'Campos Vacios',
            'error'
        );
    }else{
        $.post("../../controller/documento.php?op=update",{doc_id:doc_id,doc_asun:doc_asun,doc_desc:doc_desc},function(data){
            let timerInterval;
            Swal.fire({
            title: 'Trámite Documentario',
            html: 'Guardado Registro...Espere..<b></b>.',
            timer: 2000,
            timerProgressBar: true,
            onBeforeOpen: () => {
                Swal.showLoading();
                timerInterval = setInterval(() => {
                const content = Swal.getContent();
                if (content) {
                    const b = content.querySelector('b');
                    if (b) {
                    b.textContent = Swal.getTimerLeft();
                    }
                }
                }, 100);

            },
            onClose: () => {
                clearInterval(timerInterval);
            }
            }).then((result) => {
                if (result.dismiss === Swal.DismissReason.timer) {
                    location.reload();
                }
            });
        });
    }
});

$(document).on("click","#btnadd", function(){
    $("#modalarchivo").modal('show');
});

function llenartabla(doc_id){
    tabla= $('#detalle_data').DataTable({
        "aProcessing": true,//Activamos el procesamiento del datatables
        "aServerSide": true,//Paginación y filtrado realizados por el servidor
        dom: 'Bfrtip',//Definimos los elementos del control de tabla
        "ajax":{
        url:"../../controller/documento.php?op=listardetalle",
        type : "post",
        data:{doc_id:doc_id},						
            error: function(e){
                console.log(e.responseText);
            },
        },
        "bDestroy": true,
        "responsive": true,
        "bInfo":true,
        "iDisplayLength": 10,
        "order": [[ 0, "desc" ]],
        "language": {
            "sProcessing":     "Procesando...",
            "sLengthMenu":     "Mostrar _MENU_ registros",
            "sZeroRecords":    "No se encontraron resultados",
            "sEmptyTable":     "Ningún dato disponible en esta tabla",
            "sInfo":           "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
            "sInfoEmpty":      "Mostrando registros del 0 al 0 de un total de 0 registros",
            "sInfoFiltered":   "(filtrado de un total de _MAX_ registros)",
            "sInfoPostFix":    "",
            "sSearch":         "Buscar:",
            "sUrl":            "",
            "sInfoThousands":  ",",
            "sLoadingRecords": "Cargando...",
            "oPaginate": {          
                "sFirst":    "Primero",
                "sLast":     "Último",
                "sNext":     "Siguiente",
                "sPrevious": "Anterior"
            },
            "oAria": {
                "sSortAscending":  ": Activar para ordenar la columna de manera ascendente",
                "sSortDescending": ": Activar para ordenar la columna de manera descendente"
            }
        },
    });
}

function eliminar(docd_id){
    $.post("../../controller/documento.php?op=deletedetalle",{docd_id:docd_id},function(data){
        Swal.fire(
            'Mesa De Partes',
            'Se elimino correctamente',
            'info'
        );
    });

    var doc_id =  $('#doc_id').val();
    llenartabla(doc_id);
}

init();