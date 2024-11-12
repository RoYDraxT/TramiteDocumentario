function init(){
    $("#usuario_form").on("submit",function(e){
        guardaryeditar(e);	
    });
}

function guardaryeditar(e){
    e.preventDefault();
    var formData = new FormData($("#usuario_form")[0]);

    $.ajax({
        url: "../controller/usuario.php?op=guardar",
        type: "POST",
        data: formData,
        contentType: false,
        processData: false,
        success: function(datos){

            if (datos=="pass"){
                Swal.fire(
                    'Mesa De Partes',
                    'No Coinciden las Contraseñas',
                    'error'
                );
            }else if (datos=="correo"){
                Swal.fire(
                    'Mesa De Partes',
                    'Usted ya se Encuentra Registrado',
                    'error'
                );
            }else{
                Swal.fire(
                    'Mesa De Partes',
                    'Se registro Correctamente',
                    'success'
                );
            }

            $('#usuario_form')[0].reset();
        }
    }); 
    
}

init();