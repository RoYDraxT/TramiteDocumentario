var usu_id = $("#useridx").val();

function init(){

}

$(document).ready(function(){
    $.post("../../controller/documento.php?op=insert",{usu_id:usu_id},function(data){
        data = JSON.parse(data);
        $('#doc_id').val(data.doc_id);
    });
});

$(document).on("click","#btnguardar", function(){
    var doc_id = $("#doc_id").val();
    var doc_asun = $("#doc_asun").val();
    var doc_desc = $("#doc_desc").val();

    $.post("../../controller/documento.php?op=update",{doc_id:doc_id,doc_asun:doc_asun,doc_desc:doc_desc},function(data){
        Swal.fire(
            'Mesa De Partes',
            'Se registro Correctamente',
            'success'
        );
    });
});

init();