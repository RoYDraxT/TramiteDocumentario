$(document).on("click", "#btnadministrador", function () {
    var dep_id = $("#dep_id").val();

    if (dep_id === '') {
        Swal.fire(
            'Trámite Documentario',
            'Por favor, ingrese el código de seguridad.',
            'error'
        );
    } else {
        $.post("../controller/departamento.php?op=validate", { dep_id: dep_id }, function (response) {
            const data = JSON.parse(response);

            if (data.status === "success") {
                // Guardar el dep_id en la sesión y redirigir a la página general del administrador
                $.post("../controller/save_dep_id.php", { dep_id: dep_id }, function () {
                    window.location.href = "../View/Administrador/index.php";
                });
            } else {
                Swal.fire(
                    'Trámite Documentario',
                    data.message || 'Código no válido.',
                    'error'
                );
            }
        }).fail(function () {
            Swal.fire(
                'Trámite Documentario',
                'Error en el servidor. Intente nuevamente más tarde.',
                'error'
            );
        });
    }
});
