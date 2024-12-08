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
                // Redirigir a la página correspondiente según el ID del departamento
                switch (dep_id) {
                    case '1463':
                        window.location.href = "../View/Administrador/academico.php";
                        break;
                    case '8479':
                        window.location.href = "../View/Administrador/altadireccion.php";
                        break;
                    case '5495':
                        window.location.href = "../View/Administrador/facultades.php";
                        break;
                    case '5189':
                        window.location.href = "../View/Administrador/orgagobierno.php";
                        break;
                    case '6447':
                        window.location.href = "../View/Administrador/postgrado.php";
                        break;
                    default:
                        Swal.fire(
                            'Trámite Documentario',
                            'Código no válido.',
                            'error'
                        );
                }
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
