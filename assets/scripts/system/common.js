/**
 * @author Felix Cabrita
 */
function importEdifice() {
    document.forms["importEdifice"].submit();
}

function importClients() {
    document.forms["importClients"].submit();
}

function importApartment() {
    document.forms["importApartment"].submit();
}

function importModule() {
    document.forms["importModule"].submit();
}

function importDocument() {
    document.forms["importDocument"].submit();
}

function importheading() {
    document.forms["importheading"].submit();
}

function importaccounthigher() {
    document.forms["importaccounthigher"].submit();
}

function importaccount() {
    document.forms["importaccount"].submit();
}

function importaccountdetail() {
    document.forms["importaccountdetail"].submit();
}

function load_bills_() {
    document.forms["load_bills_"].submit();
}

function report_balance_verification() {
    document.forms["report_balance_verification"].submit();
}
jQuery(document).ready(function() {

    $.ajax({
        url: baseURL + 'obtener_rubros',
        type: "GET",
        dataType: "json",
        success: function(data) {

            $('select[name="heading_2_ra"]').empty();
            $('select[name="heading_1_ra"]').empty();
            $.each(data, function(key, value) {
                $('select[name="heading_1_ra"]').append('<option value="' + value.id + '" ' + (value.code == 4 ? 'selected' : '') + '>' + value.code + " - " + value.name + '</option>');
                $('select[name="heading_2_ra"]').append('<option value="' + value.id + '" ' + (value.code == 6 ? 'selected' : '') + '>' + value.code + " - " + value.name + '</option>');
            });
        }
    });

    jQuery(document).on("click", ".activ", function() {
        var userId = $(this).data("userid"),
            isactive = $(this).data("isactive"),
            hitURL = baseURL + "activaruser";

        const data = new FormData();
        data.append('userId', userId);
        data.append('isactive', (isactive == 1 ? 0 : 1));

        Swal.queue([{
            title: "¿Desea " + (isactive == 1 ? "desactivar" : "activar") + " este usuarioaaa?",
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'SI!',
            cancelButtonText: 'No, cancelar!',
            text: 'Cambiara el estado de este usuario, podras reversar esta accion.',
            showLoaderOnConfirm: true,
            preConfirm: () => {
                return fetch(hitURL, {
                        method: 'POST',
                        body: data
                    })
                    .then(response => response.json())
                    .then(data => (data.status ?
                        Swal.fire({
                            position: 'top-end',
                            icon: 'success',
                            title: "Usuario " + (isactive == 1 ? "desactivado" : "activado") + "  con exito",
                            showConfirmButton: false,
                            timer: 2000
                        }).then((result) => {
                            /* Read more about handling dismissals below */
                            if (result.dismiss === Swal.DismissReason.timer) {
                                window.location = baseURL + "usuarios"
                            }
                        }) :
                        Swal.fire({
                            position: 'top-end',
                            icon: 'error',
                            title: "Por alguna razon no podemos " + (isactive == 1 ? "desactivar" : "activar") + " este usuario",
                            showConfirmButton: false,
                            timer: 2000
                        })))
                    .catch(() => {
                        Swal.insertQueueStep({
                            icon: 'error',
                            title: 'acceso denegado..!'
                        })
                    })
            }
        }])
    });

    jQuery(document).on("click", ".changeStatusCondominio", function() {
        var userId = $(this).data("userid"),
            isactive = $(this).data("isactive"),
            hitURL = baseURL + "cambio_estado";

        const data = new FormData();
        data.append('userId', userId);
        data.append('isactive', (isactive == 1 ? 0 : 1));
        Swal.queue([{
            title: "¿Desea " + (isactive == 1 ? "desactivar" : "activar") + " este condominio?",
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'SI!',
            cancelButtonText: 'No, cancelar!',
            text: 'Cambiara el estado de este condominio, podras reversar esta accion.',
            showLoaderOnConfirm: true,
            preConfirm: () => {
                return fetch(hitURL, {
                        method: 'POST',
                        body: data
                    })
                    .then(response => response.json())
                    .then(data => (data.status ?
                        Swal.fire({
                            position: 'top-end',
                            icon: 'success',
                            title: "Condominio " + (isactive == 1 ? "desactivado" : "activado") + "  con exito",
                            showConfirmButton: false,
                            timer: 2000
                        }).then((result) => {
                            window.location = baseURL + "condominios";
                        }) :
                        Swal.fire({
                            position: 'top-end',
                            icon: 'error',
                            title: "Por alguna razon no podemos " + (isactive == 1 ? "desactivar" : "activar") + " este condominio",
                            showConfirmButton: false,
                            timer: 2000
                        })))
                    .catch(() => {
                        Swal.insertQueueStep({
                            icon: 'error',
                            title: 'acceso denegado..!'
                        })
                    })
            }
        }])
    });

    jQuery(document).on("click", ".deleteCondominium", function() {
        var userId = $(this).data("userid"),
            name = $(this).data("name"),
            hitURL = baseURL + "eliminar_condominio",
            currentRow = $(this);

        const data = new FormData();
        data.append('id', userId);

        Swal.queue([{
            title: '¿Desea eliminar el codominio "' + name + '?',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'SI!',
            cancelButtonText: 'No, cancelar!',
            text: 'No podras reversar esta accion.',
            showLoaderOnConfirm: true,
            preConfirm: () => {
                return fetch(hitURL, {
                        method: 'POST',
                        body: data
                    })
                    .then(response => response.json())
                    .then(data => (data.status ?
                        Swal.fire({
                            position: 'top-end',
                            icon: 'success',
                            title: 'Condominio eliminado exitosamente!',
                            showConfirmButton: false,
                            timer: 2000
                        }).then((result) => {
                            /* Read more about handling dismissals below */
                            if (result.dismiss === Swal.DismissReason.timer) {
                                currentRow.parents('tr').remove();
                            }
                        }) :
                        Swal.fire({
                            position: 'top-end',
                            icon: 'error',
                            title: "Por alguna razon no podemos eliminar este condominio",
                            showConfirmButton: false,
                            timer: 2000
                        })))
                    .catch(() => {
                        Swal.insertQueueStep({
                            icon: 'error',
                            title: 'acceso denegado..!'
                        })
                    })
            }
        }])
    });

    jQuery(document).on("click", ".deleteUser", function() {
        var userId = $(this).data("userid"),
            name = $(this).data("name"),
            hitURL = baseURL + "deleteUser",
            currentRow = $(this);

        const data = new FormData();
        data.append('userId', userId);

        Swal.queue([{
            title: '¿Desea eliminar el usuario "' + name + '?',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'SI!',
            cancelButtonText: 'No, cancelar!',
            text: 'No podras reversar esta accion.',
            showLoaderOnConfirm: true,
            preConfirm: () => {
                return fetch(hitURL, {
                        method: 'POST',
                        body: data
                    })
                    .then(response => response.json())
                    .then(data => (data.status ?
                        Swal.fire({
                            position: 'top-end',
                            icon: 'success',
                            title: 'Usuario eliminado exitosamente!',
                            showConfirmButton: false,
                            timer: 2000
                        }).then((result) => {
                            /* Read more about handling dismissals below */
                            if (result.dismiss === Swal.DismissReason.timer) {
                                currentRow.parents('tr').remove();
                            }
                        }) :
                        Swal.fire({
                            position: 'top-end',
                            icon: 'error',
                            title: "Por alguna razon no podemos eliminar este usuario",
                            showConfirmButton: false,
                            timer: 2000
                        })))
                    .catch(() => {
                        Swal.insertQueueStep({
                            icon: 'error',
                            title: 'acceso denegado..!'
                        })
                    })
            }
        }])
    });

    jQuery(document).on("click", ".deleteEdifice", function() {
        var userId = $(this).data("userid"),
            name = $(this).data("name"),
            hitURL = baseURL + "eliminar_edificio",
            currentRow = $(this);

        const data = new FormData();
        data.append('id', userId);

        Swal.queue([{
            title: '¿Desea eliminar el edificio "' + name + '?',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'SI!',
            cancelButtonText: 'No, cancelar!',
            text: 'Si este edificio tiene datos enlazados no podra eliminarlo.',
            showLoaderOnConfirm: true,
            preConfirm: () => {
                return fetch(hitURL, {
                        method: 'POST',
                        body: data
                    })
                    .then(response => response.json())
                    .then(data => (data.status ?
                        Swal.fire({
                            position: 'top-end',
                            icon: 'success',
                            title: 'Edificio eliminado exitosamente!',
                            showConfirmButton: false,
                            timer: 2000
                        }).then((result) => {
                            /* Read more about handling dismissals below */
                            if (result.dismiss === Swal.DismissReason.timer) {
                                currentRow.parents('tr').remove();
                            }
                        }) :
                        Swal.fire({
                            position: 'top-end',
                            icon: 'error',
                            title: "Es posible que este edificio tenga datos enlazados, por favor verifique y vuelva a intentarlo",
                            showConfirmButton: false,
                            timer: 2000
                        })))
                    .catch(() => {
                        Swal.insertQueueStep({
                            icon: 'error',
                            title: 'acceso denegado..!'
                        })
                    })
            }
        }])
    });

    jQuery(document).on("click", ".changeStatusEdifice", function() {
        var userId = $(this).data("userid"),
            isactive = $(this).data("isactive"),
            hitURL = baseURL + "cambio_estado_edificio";

        const data = new FormData();
        data.append('id', userId);
        data.append('isactive', (isactive == 1 ? 0 : 1));

        Swal.queue([{
            title: "¿Desea " + (isactive == 1 ? "desactivar" : "activar") + " este edificio?",
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'SI!',
            cancelButtonText: 'No, cancelar!',
            text: 'Cambiara el estado de este edificio, podras reversar esta accion.',
            showLoaderOnConfirm: true,
            preConfirm: () => {
                return fetch(hitURL, {
                        method: 'POST',
                        body: data
                    })
                    .then(response => response.json())
                    .then(data => (data.status ?
                        Swal.fire({
                            position: 'top-end',
                            icon: 'success',
                            title: "Edificio " + (isactive == 1 ? "desactivado" : "activado") + "  con exito",
                            showConfirmButton: false,
                            timer: 2000
                        }).then((result) => {
                            /* Read more about handling dismissals below */
                            if (result.dismiss === Swal.DismissReason.timer) {
                                window.location = baseURL + "edificios"
                            }
                        }) :
                        Swal.fire({
                            position: 'top-end',
                            icon: 'error',
                            title: "Por alguna razon no podemos " + (isactive == 1 ? "desactivar" : "activar") + " este edificio",
                            showConfirmButton: false,
                            timer: 2000
                        })))
                    .catch(() => {
                        Swal.insertQueueStep({
                            icon: 'error',
                            title: 'acceso denegado..!'
                        })
                    })
            }
        }])
    });

    jQuery(document).on("click", ".deletePeriod", function() {
        var userId = $(this).data("userid"),
            name = $(this).data("name"),
            hitURL = baseURL + "eliminar_periodos",
            currentRow = $(this);

        const data = new FormData();
        data.append('id', userId);

        Swal.queue([{
            title: '¿Desea eliminar el periodo "' + name + '?',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'SI!',
            cancelButtonText: 'No, cancelar!',
            text: 'Si este periodo tiene datos enlazados no podra eliminarlo.',
            showLoaderOnConfirm: true,
            preConfirm: () => {
                return fetch(hitURL, {
                        method: 'POST',
                        body: data
                    })
                    .then(response => response.json())
                    .then(data => (data.status ?
                        Swal.fire({
                            position: 'top-end',
                            icon: 'success',
                            title: 'Periodo eliminado exitosamente!',
                            showConfirmButton: false,
                            timer: 2000
                        }).then((result) => {
                            /* Read more about handling dismissals below */
                            if (result.dismiss === Swal.DismissReason.timer) {
                                currentRow.parents('tr').remove();
                            }
                        }) :
                        Swal.fire({
                            position: 'top-end',
                            icon: 'error',
                            title: "Es posible que este periodo tenga datos enlazados, por favor verifique y vuelva a intentarlo",
                            showConfirmButton: false,
                            timer: 2000
                        })))
                    .catch(() => {
                        Swal.insertQueueStep({
                            icon: 'error',
                            title: 'acceso denegado..!'
                        })
                    })
            }
        }])
    });

    jQuery(document).on("click", ".changeStatusPeriod", function() {
        var userId = $(this).data("userid"),
            isactive = $(this).data("isactive"),
            hitURL = baseURL + "cambio_estado_periodo";

        const data = new FormData();
        data.append('id', userId);
        data.append('isactive', (isactive == 1 ? 0 : 1));

        Swal.queue([{
            title: "¿Desea " + (isactive == 1 ? "desactivar" : "activar") + " este periodo?",
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'SI!',
            cancelButtonText: 'No, cancelar!',
            text: 'Cambiara el estado de este periodo, podras reversar esta accion.',
            showLoaderOnConfirm: true,
            preConfirm: () => {
                return fetch(hitURL, {
                        method: 'POST',
                        body: data
                    })
                    .then(response => response.json())
                    .then(data => (data.status ?
                        Swal.fire({
                            position: 'top-end',
                            icon: 'success',
                            title: "Periodo " + (isactive == 1 ? "desactivado" : "activado") + "  con exito",
                            showConfirmButton: false,
                            timer: 2000
                        }).then((result) => {
                            /* Read more about handling dismissals below */
                            if (result.dismiss === Swal.DismissReason.timer) {
                                window.location = baseURL + "periodos"
                            }
                        }) :
                        Swal.fire({
                            position: 'top-end',
                            icon: 'error',
                            title: "Por alguna razon no podemos " + (isactive == 1 ? "desactivar" : "activar") + " este periodo",
                            showConfirmButton: false,
                            timer: 2000
                        })))
                    .catch(() => {
                        Swal.insertQueueStep({
                            icon: 'error',
                            title: 'acceso denegado..!'
                        })
                    })
            }
        }])
    });

    jQuery(document).on("click", ".deleteApartment", function() {
        var userId = $(this).data("userid"),
            name = $(this).data("name"),
            hitURL = baseURL + "eliminar_apartamento",
            currentRow = $(this);

        const data = new FormData();
        data.append('id', userId);

        Swal.queue([{
            title: '¿Desea eliminar el apartamento "' + name + '?',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'SI!',
            cancelButtonText: 'No, cancelar!',
            text: 'Si este apartamento tiene datos enlazados no podra eliminarlo.',
            showLoaderOnConfirm: true,
            preConfirm: () => {
                return fetch(hitURL, {
                        method: 'POST',
                        body: data
                    })
                    .then(response => response.json())
                    .then(data => (data.status ?
                        Swal.fire({
                            position: 'top-end',
                            icon: 'success',
                            title: 'Apartamento eliminado exitosamente!',
                            showConfirmButton: false,
                            timer: 2000
                        }).then((result) => {
                            /* Read more about handling dismissals below */
                            if (result.dismiss === Swal.DismissReason.timer) {
                                currentRow.parents('tr').remove();
                            }
                        }) :
                        Swal.fire({
                            position: 'top-end',
                            icon: 'error',
                            title: "Es posible que este apartamento tenga datos enlazados, por favor verifique y vuelva a intentarlo",
                            showConfirmButton: false,
                            timer: 2000
                        })))
                    .catch(() => {
                        Swal.insertQueueStep({
                            icon: 'error',
                            title: 'acceso denegado..!'
                        })
                    })
            }
        }])
    });

    jQuery(document).on("click", ".changeStatusApartment", function() {
        var userId = $(this).data("userid"),
            isactive = $(this).data("isactive"),
            hitURL = baseURL + "cambio_estado_apartamento";

        const data = new FormData();
        data.append('id', userId);
        data.append('isactive', (isactive == 1 ? 0 : 1));

        Swal.queue([{
            title: "¿Desea " + (isactive == 1 ? "desactivar" : "activar") + " este apartamento?",
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'SI!',
            cancelButtonText: 'No, cancelar!',
            text: 'Cambiara el estado de este apartamento, podras reversar esta accion.',
            showLoaderOnConfirm: true,
            preConfirm: () => {
                return fetch(hitURL, {
                        method: 'POST',
                        body: data
                    })
                    .then(response => response.json())
                    .then(data => (data.status ?
                        Swal.fire({
                            position: 'top-end',
                            icon: 'success',
                            title: "Apartamento " + (isactive == 1 ? "desactivado" : "activado") + "  con exito",
                            showConfirmButton: false,
                            timer: 2000
                        }).then((result) => {
                            /* Read more about handling dismissals below */
                            if (result.dismiss === Swal.DismissReason.timer) {
                                window.location = baseURL + "apartamentos"
                            }
                        }) :
                        Swal.fire({
                            position: 'top-end',
                            icon: 'error',
                            title: "Por alguna razon no podemos " + (isactive == 1 ? "desactivar" : "activar") + " este apartamento",
                            showConfirmButton: false,
                            timer: 2000
                        })))
                    .catch(() => {
                        Swal.insertQueueStep({
                            icon: 'error',
                            title: 'acceso denegado..!'
                        })
                    })
            }
        }])
    });

    jQuery(document).on("click", ".deleteDirective", function() {
        var userId = $(this).data("userid"),
            name = $(this).data("name"),
            carnet = $(this).data("carnet"),
            hitURL = baseURL + "eliminar_miembro",
            currentRow = $(this);

        const data = new FormData();
        data.append('id', userId);

        Swal.queue([{
            title: '¿Desea eliminar el miembro "' + name + '?',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'SI!',
            cancelButtonText: 'No, cancelar!',
            showLoaderOnConfirm: true,
            preConfirm: () => {
                return fetch(hitURL, {
                        method: 'POST',
                        body: data
                    })
                    .then(response => response.json())
                    .then(data => (data.status ?
                        Swal.fire({
                            position: 'top-end',
                            icon: 'success',
                            title: 'Miembro eliminado exitosamente!',
                            showConfirmButton: false,
                            timer: 2000
                        }).then((result) => {
                            /* Read more about handling dismissals below */
                            if (result.dismiss === Swal.DismissReason.timer) {
                                if (carnet === 0) { currentRow.parents('tr').remove(); } else {
                                    window.location = baseURL + "juntadirectiva"
                                }

                            }
                        }) :
                        Swal.fire({
                            position: 'top-end',
                            icon: 'error',
                            title: "Por alguna razon este miembro no se ha podido eliminar",
                            showConfirmButton: false,
                            timer: 2000
                        })))
                    .catch(() => {
                        Swal.insertQueueStep({
                            icon: 'error',
                            title: 'acceso denegado..!'
                        })
                    })
            }
        }])
    });

    jQuery(document).on("click", ".changeStatusDirective", function() {
        var userId = $(this).data("userid"),
            isactive = $(this).data("isactive"),
            hitURL = baseURL + "cambio_estado_miembro";

        const data = new FormData();
        data.append('id', userId);
        data.append('isactive', (isactive == 1 ? 0 : 1));

        Swal.queue([{
            title: "¿Desea " + (isactive == 1 ? "desactivar" : "activar") + " este miembro?",
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'SI!',
            cancelButtonText: 'No, cancelar!',
            showLoaderOnConfirm: true,
            preConfirm: () => {
                return fetch(hitURL, {
                        method: 'POST',
                        body: data
                    })
                    .then(response => response.json())
                    .then(data => (data.status ?
                        Swal.fire({
                            position: 'top-end',
                            icon: 'success',
                            title: "Miembro " + (isactive == 1 ? "desactivado" : "activado") + "  con exito",
                            showConfirmButton: false,
                            timer: 2000
                        }).then((result) => {
                            /* Read more about handling dismissals below */
                            if (result.dismiss === Swal.DismissReason.timer) {
                                window.location = baseURL + "juntadirectiva"
                            }
                        }) :
                        Swal.fire({
                            position: 'top-end',
                            icon: 'error',
                            title: "Por alguna razon no podemos " + (isactive == 1 ? "desactivar" : "activar") + " este miembro",
                            showConfirmButton: false,
                            timer: 2000
                        })))
                    .catch(() => {
                        Swal.insertQueueStep({
                            icon: 'error',
                            title: 'acceso denegado..!'
                        })
                    })
            }
        }])
    });

    jQuery(document).on("click", ".deleteService", function() {
        var userId = $(this).data("userid"),
            name = $(this).data("name"),
            hitURL = baseURL + "eliminar_servicio",
            currentRow = $(this);

        const data = new FormData();
        data.append('id', userId);

        Swal.queue([{
            title: '¿Desea eliminar el servicio "' + name + '?',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'SI!',
            cancelButtonText: 'No, cancelar!',
            text: 'Si este servicio tiene datos enlazados no podra eliminarlo.',
            showLoaderOnConfirm: true,
            preConfirm: () => {
                return fetch(hitURL, {
                        method: 'POST',
                        body: data
                    })
                    .then(response => response.json())
                    .then(data => (data.status ?
                        Swal.fire({
                            position: 'top-end',
                            icon: 'success',
                            title: 'Servicio eliminado exitosamente!',
                            showConfirmButton: false,
                            timer: 2000
                        }).then((result) => {
                            /* Read more about handling dismissals below */
                            if (result.dismiss === Swal.DismissReason.timer) {
                                currentRow.parents('tr').remove();
                            }
                        }) :
                        Swal.fire({
                            position: 'top-end',
                            icon: 'error',
                            title: "Es posible que este servicio tenga datos enlazados, por favor verifique y vuelva a intentarlo",
                            showConfirmButton: false,
                            timer: 2000
                        })))
                    .catch(() => {
                        Swal.insertQueueStep({
                            icon: 'error',
                            title: 'acceso denegado..!'
                        })
                    })
            }
        }])
    });

    jQuery(document).on("click", ".changeStatusService", function() {
        var userId = $(this).data("userid"),
            isactive = $(this).data("isactive"),
            hitURL = baseURL + "cambio_estado_servicio";

        const data = new FormData();
        data.append('id', userId);
        data.append('isactive', (isactive == 1 ? 0 : 1));

        Swal.queue([{
            title: "¿Desea " + (isactive == 1 ? "desactivar" : "activar") + " este servicio?",
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'SI!',
            cancelButtonText: 'No, cancelar!',
            text: 'Cambiara el estado de este servicio, podras reversar esta accion.',
            showLoaderOnConfirm: true,
            preConfirm: () => {
                return fetch(hitURL, {
                        method: 'POST',
                        body: data
                    })
                    .then(response => response.json())
                    .then(data => (data.status ?
                        Swal.fire({
                            position: 'top-end',
                            icon: 'success',
                            title: "Servicio " + (isactive == 1 ? "desactivado" : "activado") + "  con exito",
                            showConfirmButton: false,
                            timer: 2000
                        }).then((result) => {
                            /* Read more about handling dismissals below */
                            if (result.dismiss === Swal.DismissReason.timer) {
                                window.location = baseURL + "servicios"
                            }
                        }) :
                        Swal.fire({
                            position: 'top-end',
                            icon: 'error',
                            title: "Por alguna razon no podemos " + (isactive == 1 ? "desactivar" : "activar") + " este servicio",
                            showConfirmButton: false,
                            timer: 2000
                        })))
                    .catch(() => {
                        Swal.insertQueueStep({
                            icon: 'error',
                            title: 'acceso denegado..!'
                        })
                    })
            }
        }])
    });

    jQuery(document).on("click", ".deleteCommonarea", function() {
        var userId = $(this).data("userid"),
            name = $(this).data("name"),
            hitURL = baseURL + "eliminar_areascomunes",
            currentRow = $(this);

        const data = new FormData();
        data.append('id', userId);

        Swal.queue([{
            title: '¿Desea eliminar el area comun "' + name + '?',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'SI!',
            cancelButtonText: 'No, cancelar!',
            text: 'Si esta area comun tiene datos enlazados no podra eliminarlo.',
            showLoaderOnConfirm: true,
            preConfirm: () => {
                return fetch(hitURL, {
                        method: 'POST',
                        body: data
                    })
                    .then(response => response.json())
                    .then(data => (data.status ?
                        Swal.fire({
                            position: 'top-end',
                            icon: 'success',
                            title: 'Area comun eliminado exitosamente!',
                            showConfirmButton: false,
                            timer: 2000
                        }).then((result) => {
                            /* Read more about handling dismissals below */
                            if (result.dismiss === Swal.DismissReason.timer) {
                                currentRow.parents('tr').remove();
                            }
                        }) :
                        Swal.fire({
                            position: 'top-end',
                            icon: 'error',
                            title: "Es posible que esta area comun tenga datos enlazados, por favor verifique y vuelva a intentarlo",
                            showConfirmButton: false,
                            timer: 2000
                        })))
                    .catch(() => {
                        Swal.insertQueueStep({
                            icon: 'error',
                            title: 'acceso denegado..!'
                        })
                    })
            }
        }])
    });
    jQuery(document).on("click", ".changeStatusCommonarea", function() {
        var userId = $(this).data("userid"),
            isactive = $(this).data("isactive"),
            hitURL = baseURL + "cambio_estado_areascomunes";

        const data = new FormData();
        data.append('id', userId);
        data.append('isactive', (isactive == 1 ? 0 : 1));

        Swal.queue([{
            title: "¿Desea " + (isactive == 1 ? "desactivar" : "activar") + " esta area comun?",
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'SI!',
            cancelButtonText: 'No, cancelar!',
            text: 'Cambiara el estado de esta area comun , podras reversar esta accion.',
            showLoaderOnConfirm: true,
            preConfirm: () => {
                return fetch(hitURL, {
                        method: 'POST',
                        body: data
                    })
                    .then(response => response.json())
                    .then(data => (data.status ?
                        Swal.fire({
                            position: 'top-end',
                            icon: 'success',
                            title: "Area comun " + (isactive == 1 ? "desactivado" : "activado") + "  con exito",
                            showConfirmButton: false,
                            timer: 2000
                        }).then((result) => {
                            /* Read more about handling dismissals below */
                            if (result.dismiss === Swal.DismissReason.timer) {
                                window.location = baseURL + "areascomunes"
                            }
                        }) :
                        Swal.fire({
                            position: 'top-end',
                            icon: 'error',
                            title: "Por alguna razon no podemos " + (isactive == 1 ? "desactivar" : "activar") + " est area comun",
                            showConfirmButton: false,
                            timer: 2000
                        })))
                    .catch(() => {
                        Swal.insertQueueStep({
                            icon: 'error',
                            title: 'acceso denegado..!'
                        })
                    })
            }
        }])
    });

    jQuery(document).on("click", ".deleteTenant", function() {
        var userId = $(this).data("userid"),
            name = $(this).data("name"),
            hitURL = baseURL + "eliminar_clientes",
            currentRow = $(this);

        const data = new FormData();
        data.append('id', userId);

        Swal.queue([{
            title: '¿Desea eliminar el clientes "' + name + '?',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'SI!',
            cancelButtonText: 'No, cancelar!',
            text: 'Si este clientes tiene datos enlazados no podra eliminarlo.',
            showLoaderOnConfirm: true,
            preConfirm: () => {
                return fetch(hitURL, {
                        method: 'POST',
                        body: data
                    })
                    .then(response => response.json())
                    .then(data => (data.status ?
                        Swal.fire({
                            position: 'top-end',
                            icon: 'success',
                            title: 'Cliente eliminado exitosamente!',
                            showConfirmButton: false,
                            timer: 2000
                        }).then((result) => {
                            /* Read more about handling dismissals below */
                            if (result.dismiss === Swal.DismissReason.timer) {
                                currentRow.parents('tr').remove();
                            }
                        }) :
                        Swal.fire({
                            position: 'top-end',
                            icon: 'error',
                            title: "Es posible que este Cliente tenga datos enlazados, por favor verifique y vuelva a intentarlo",
                            showConfirmButton: false,
                            timer: 2000
                        })))
                    .catch(() => {
                        Swal.insertQueueStep({
                            icon: 'error',
                            title: 'acceso denegado..!'
                        })
                    })
            }
        }])
    });

    jQuery(document).on("click", ".changeStatusTenant", function() {
        var userId = $(this).data("userid"),
            isactive = $(this).data("isactive"),
            hitURL = baseURL + "cambio_estado_clientes";

        const data = new FormData();
        data.append('id', userId);
        data.append('isactive', (isactive == 1 ? 0 : 1));

        Swal.queue([{
            title: '¿Desea deshabilitar el usuario de este cliente?',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'SI!',
            cancelButtonText: 'No, cancelar!',
            text: 'Podras reversar esta accion editando del registro.',
            showLoaderOnConfirm: true,
            preConfirm: () => {
                return fetch(hitURL, {
                        method: 'POST',
                        body: data
                    })
                    .then(response => response.json())
                    .then(data => (data.status ?
                        Swal.fire({
                            position: 'top-end',
                            icon: 'success',
                            title: "Cliente deshabilitado con exito",
                            showConfirmButton: false,
                            timer: 2000
                        }).then((result) => {
                            /* Read more about handling dismissals below */
                            if (result.dismiss === Swal.DismissReason.timer) {
                                window.location = baseURL + "clientes"
                            }
                        }) :
                        Swal.fire({
                            position: 'top-end',
                            icon: 'error',
                            title: "Por alguna razon no podemos deshabilitar este cliente",
                            showConfirmButton: false,
                            timer: 2000
                        })))
                    .catch(() => {
                        Swal.insertQueueStep({
                            icon: 'error',
                            title: 'acceso denegado..!'
                        })
                    })
            }
        }])
    });

    jQuery(document).on("click", ".set_asignation", function() {
        var userId = $(this).data("userid"),
            name = $(this).data("name"),
            edifice = $("#edifice").val(),
            date_start = $("#date_start").val(),
            apartment = $("#apartment").val(),
            apartment_name = $("#apartment  option:selected").html(),
            hitURL = baseURL + "asignar_apartamento";

        if (edifice === '' || apartment === '' || date_start === '') {
            Swal.fire({
                position: 'top-end',
                icon: 'error',
                title: "Debe asignar un edificio, un apartamento y la fecha desde que empezo a vivir en el .",
                showConfirmButton: false,
                timer: 3000
            })
        } else {
            const data = new FormData();
            data.append('id', userId);
            data.append('edifice', edifice);
            data.append('date_start', date_start);
            data.append('apartment', apartment);
            Swal.queue([{
                title: "¿Desea asignar el apartamento (" + apartment_name + ") a este cliente?",
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'SI!',
                cancelButtonText: 'No, cancelar!',
                text: 'Esta accion solo se puede reversar desvinculando el apartamento con una observacion.',
                showLoaderOnConfirm: true,
                preConfirm: () => {
                    return fetch(hitURL, {
                            method: 'POST',
                            body: data
                        })
                        .then(response => response.json())
                        .then(data => (data.status ?
                            Swal.fire({
                                position: 'top-end',
                                icon: 'success',
                                title: 'Apartamento asignado exitosamente!',
                                showConfirmButton: false,
                                timer: 2000
                            }).then((result) => {
                                if (result.dismiss === Swal.DismissReason.timer) {
                                    window.location = baseURL + "asignaciones/" + userId;
                                }
                            }) :
                            Swal.fire({
                                position: 'top-end',
                                icon: 'error',
                                title: data.error === '' ? "Por alguna razon no podemos asignar el apartamento (" + apartment_name + ") a este cliente" : data.error,
                                showConfirmButton: false,
                                timer: 3000
                            })))
                        .catch(() => {
                            Swal.insertQueueStep({
                                icon: 'error',
                                title: 'acceso denegado..!'
                            })
                        })
                }
            }])
        }
    });

    jQuery(document).on("click", ".delete_asignation", function() {
        var userId = $(this).data("userid"),
            apartment = $(this).data("name"),
            tenant = $(this).data("tenant"),
            hitURL = baseURL + "desvincular_apartamento";

        const data = new FormData();
        data.append('id', userId);
        data.append('type', type);
        data.append('document', documentTenant);
        if (type === '6') {
            Swal.queue([{
                title: "¿Desea desvincular el apartamento (" + apartment + ") de este propietario?",
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'SI!',
                cancelButtonText: 'No, cancelar!',
                input: 'text',
                inputPlaceholder: 'Documento de identidad del nuevo propietario',
                text: 'Para desvincular este apartamento debes asignarle otro propietario.',
                showLoaderOnConfirm: true,
                inputValidator: (value) => {
                    if (!value) {
                        return 'Debes colocar un documento de identidad!'
                    }
                    if (documentTenant === value) { return 'No puede asignar el apartamento al mismo propietario!' }
                },
                preConfirm: () => {

                    data.append('observation', $('.swal2-input').val());

                    return fetch(hitURL, {
                            method: 'POST',
                            body: data
                        })
                        .then(response => response.json())
                        .then(data => (data.status ?
                            Swal.fire({
                                position: 'top-end',
                                icon: 'success',
                                title: "Apartamento reasignado con exito",
                                showConfirmButton: false,
                                timer: 2000
                            }).then((result) => {
                                /* Read more about handling dismissals below */
                                if (result.dismiss === Swal.DismissReason.timer) {
                                    window.location = baseURL + "asignaciones/" + tenant;
                                }
                            }) :
                            Swal.fire({
                                position: 'top-end',
                                icon: 'error',
                                title: data.error,
                                showConfirmButton: false,
                                timer: 4000
                            })))
                        .catch(() => {
                            Swal.insertQueueStep({
                                icon: 'error',
                                title: 'acceso denegado..!'
                            })
                        })
                }
            }])
        } else {
            Swal.queue([{
                title: "¿Desea desvincular el apartamento (" + apartment + ") de este cliente?",
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'SI!',
                cancelButtonText: 'No, cancelar!',
                input: 'text',
                inputPlaceholder: 'Ingresa una observacion',
                text: 'No podras reversar esta accion y tendras que asignarlo de nuevo.',
                showLoaderOnConfirm: true,
                inputValidator: (value) => {
                    if (!value) {
                        return 'Debes colocar una observacion!'
                    }
                },
                preConfirm: () => {

                    data.append('observation', $('.swal2-input').val());

                    return fetch(hitURL, {
                            method: 'POST',
                            body: data
                        })
                        .then(response => response.json())
                        .then(data => (data.status ?
                            Swal.fire({
                                position: 'top-end',
                                icon: 'success',
                                title: "Apartamento desvinculado con exito",
                                showConfirmButton: false,
                                timer: 2000
                            }).then((result) => {
                                /* Read more about handling dismissals below */
                                if (result.dismiss === Swal.DismissReason.timer) {
                                    window.location = baseURL + "asignaciones/" + tenant;
                                }
                            }) :
                            Swal.fire({
                                position: 'top-end',
                                icon: 'error',
                                title: "Por alguna razon no podemos desvincular el apartamento (" + apartment + ") a este cliente",
                                showConfirmButton: false,
                                timer: 2000
                            })))
                        .catch(() => {
                            Swal.insertQueueStep({
                                icon: 'error',
                                title: 'acceso denegado..!'
                            })
                        })
                }
            }])
        }

    });

    jQuery(document).on("click", ".view_asignation", function() {
        var name = $(this).data("name"),
            date = $(this).data("date"),
            observations = "Fecha de desvinculacion: " + $(this).data("date") + "<br>" +
            "Observacion: " + $(this).data("observation");
        Swal.fire({
            position: 'top-end',
            icon: 'info',
            title: "Observaciones {" + name + "}",
            html: observations
        })
    });

    jQuery(document).on("click", ".deleteTag", function() {
        var userId = $(this).data("userid"),
            name = $(this).data("name"),
            hitURL = baseURL + "eliminar_etiquetas",
            currentRow = $(this);

        const data = new FormData();
        data.append('id', userId);

        Swal.queue([{
            title: '¿Desea eliminar la etiqueta "' + name + '?',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'SI!',
            cancelButtonText: 'No, cancelar!',
            text: 'Si esta etiqueta tiene datos enlazados no podra eliminarla.',
            showLoaderOnConfirm: true,
            preConfirm: () => {
                return fetch(hitURL, {
                        method: 'POST',
                        body: data
                    })
                    .then(response => response.json())
                    .then(data => (data.status ?
                        Swal.fire({
                            position: 'top-end',
                            icon: 'success',
                            title: 'Etiqueta eliminada exitosamente!',
                            showConfirmButton: false,
                            timer: 2000
                        }).then((result) => {
                            /* Read more about handling dismissals below */
                            if (result.dismiss === Swal.DismissReason.timer) {
                                currentRow.parents('tr').remove();
                            }
                        }) :
                        Swal.fire({
                            position: 'top-end',
                            icon: 'error',
                            title: "Es posible que esta etiqueta tenga datos enlazados, por favor verifique y vuelva a intentarlo",
                            showConfirmButton: false,
                            timer: 2000
                        })))
                    .catch(() => {
                        Swal.insertQueueStep({
                            icon: 'error',
                            title: 'acceso denegado..!'
                        })
                    })
            }
        }])
    });

    jQuery(document).on("click", ".changeStatusTag", function() {
        var userId = $(this).data("userid"),
            isactive = $(this).data("isactive"),
            hitURL = baseURL + "cambio_estado_etiquetas";

        const data = new FormData();
        data.append('id', userId);
        data.append('isactive', (isactive == 1 ? 0 : 1));

        Swal.queue([{
            title: "¿Desea " + (isactive == 1 ? "desactivar" : "activar") + " esta etiqueta?",
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'SI!',
            cancelButtonText: 'No, cancelar!',
            input: 'text',
            inputPlaceholder: 'Ingresa una observacion',
            text: 'Cambiara el estado de esta etiqueta , podras reversar esta accion.',
            showLoaderOnConfirm: true,
            inputValidator: (value) => {
                if (!value) {
                    return 'Debes colocar una observacion!'
                }
            },
            preConfirm: () => {

                data.append('observation', (isactive == 1 ? "desactivado" : "activado") + ": " + $('.swal2-input').val());

                return fetch(hitURL, {
                        method: 'POST',
                        body: data
                    })
                    .then(response => response.json())
                    .then(data => (data.status ?
                        Swal.fire({
                            position: 'top-end',
                            icon: 'success',
                            title: "etiqueta " + (isactive == 1 ? "desactivado" : "activado") + "  con exito",
                            showConfirmButton: false,
                            timer: 2000
                        }).then((result) => {
                            /* Read more about handling dismissals below */
                            if (result.dismiss === Swal.DismissReason.timer) {
                                window.location = baseURL + "etiquetas"
                            }
                        }) :
                        Swal.fire({
                            position: 'top-end',
                            icon: 'error',
                            title: "Por alguna razon no podemos " + (isactive == 1 ? "desactivar" : "activar") + " esta etiqueta",
                            showConfirmButton: false,
                            timer: 2000
                        })))
                    .catch(() => {
                        Swal.insertQueueStep({
                            icon: 'error',
                            title: 'acceso denegado..!'
                        })
                    })
            }
        }])
    });

    jQuery(document).on("click", ".deleteVehicleTag", function() {
        var userId = $(this).data("userid"),
            name = $(this).data("name"),
            hitURL = baseURL + "desvinculacion_vehiculo_etiqueta";

        const data = new FormData();
        data.append('tag_id', userId);

        Swal.queue([{
            title: "¿Desea desvincular la placa {" + name + "} esta etiqueta?",
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'SI!',
            cancelButtonText: 'No, cancelar!',
            input: 'text',
            inputPlaceholder: 'Ingresa una observacion',
            text: 'Cambiara el estado de esta etiqueta , podras reversar esta accion.',
            showLoaderOnConfirm: true,
            inputValidator: (value) => {
                if (!value) {
                    return 'Debes colocar una observacion!'
                }
            },
            preConfirm: () => {

                data.append('observation', "Vehiculo {" + name + "} desvinculado: " + $('.swal2-input').val());

                return fetch(hitURL, {
                        method: 'POST',
                        body: data
                    })
                    .then(response => response.json())
                    .then(data => (data.status ?
                        Swal.fire({
                            position: 'top-end',
                            icon: 'success',
                            title: "Vehiculo  desvinculado  con exito",
                            showConfirmButton: false,
                            timer: 2000
                        }).then((result) => {
                            /* Read more about handling dismissals below */
                            if (result.dismiss === Swal.DismissReason.timer) {
                                window.location = baseURL + "etiquetas"
                            }
                        }) :
                        Swal.fire({
                            position: 'top-end',
                            icon: 'error',
                            title: "Por alguna razon no podemos desvincular este vehiculo",
                            showConfirmButton: false,
                            timer: 2000
                        })))
                    .catch(() => {
                        Swal.insertQueueStep({
                            icon: 'error',
                            title: 'acceso denegado..!'
                        })
                    })
            }
        }])
    });

    jQuery(document).on("click", ".observationTag", function() {
        var name = $(this).data("name"),
            observations = $(this).data("observations").replace("|", "<br>");

        Swal.fire({
            position: 'top-end',
            icon: 'info',
            title: "Observaciones {" + name + "}",
            html: observations
        })
    });

    jQuery(document).on("click", ".observationIncidents", function() {
        var name = $(this).data("name"),
            observations = $(this).data("observations");

        Swal.fire({
            position: 'top-end',
            icon: 'info',
            title: "Observaciones {" + name + "}",
            html: observations
        })
    });

    jQuery(document).on("click", ".changeStatusReservation", function() {
        var userId = $(this).data("userid"),
            isactive = $(this).data("isactive"),
            hitURL = baseURL + "cambio_estado_reservaciones";

        const data = new FormData();
        data.append('id', userId);
        data.append('isactive', isactive);

        Swal.queue([{
            title: "¿Desea " + (isactive === -1 ? "declinar" : "aprobar") + " esta reservacion?",
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'SI!',
            cancelButtonText: 'No, cancelar!',
            input: 'text',
            inputPlaceholder: 'Ingresa una observacion',
            text: 'Esta accion no se podra reversar.',
            showLoaderOnConfirm: true,
            inputValidator: (value) => {
                if (isactive === -1) {
                    if (!value) {
                        return 'Debes colocar una observacion!'
                    }
                }

            },
            preConfirm: () => {
                var observation = $('.swal2-input').val();
                data.append('observation', (isactive === -1 ? "Declinado" : "Aprobado") + " el setfecha() : " + (observation === '' ? "SIN OBSERVACION" : observation));

                return fetch(hitURL, {
                        method: 'POST',
                        body: data
                    })
                    .then(response => response.json())
                    .then(data => (data.status ?
                        Swal.fire({
                            position: 'top-end',
                            icon: 'success',
                            title: "Reservacion " + (isactive == -1 ? "declinada" : "aprobada") + "  con exito",
                            showConfirmButton: false,
                            timer: 2000
                        }).then((result) => {
                            /* Read more about handling dismissals below */
                            if (result.dismiss === Swal.DismissReason.timer) {
                                window.location = baseURL + "reservaciones"
                            }
                        }) :
                        Swal.fire({
                            position: 'top-end',
                            icon: 'error',
                            title: "Por alguna razon no podemos " + (isactive === -1 ? "declinar" : "aprobar") + " esta etiqueta",
                            showConfirmButton: false,
                            timer: 2000
                        })))
                    .catch(() => {
                        Swal.insertQueueStep({
                            icon: 'error',
                            title: 'acceso denegado..!'
                        })
                    })
            }
        }])
    });

    jQuery(document).on("click", ".changeStatusEmployees", function() {
        var userId = $(this).data("userid"),
            isactive = $(this).data("isactive"),
            hitURL = baseURL + "cambio_estado_etiquetas";

        const data = new FormData();
        data.append('id', userId);
        data.append('isactive', (isactive == 1 ? 0 : 1));

        Swal.queue([{
            title: "¿Desea " + (isactive == 1 ? "desactivar" : "activar") + " este empleado?",
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'SI!',
            cancelButtonText: 'No, cancelar!',
            input: 'text',
            inputPlaceholder: 'Ingresa una observacion',
            text: 'Por favo agregue una observacion, esto ayudara a resolver posibles inconvenientes.',
            showLoaderOnConfirm: true,
            inputValidator: (value) => {
                if (!value) {
                    return 'Debes colocar una observacion!'
                }
            },
            preConfirm: () => {
                data.append('observation', (isactive == 1 ? "desactivado" : "activado") + ": " + $('.swal2-input').val());

                return fetch(hitURL, {
                        method: 'POST',
                        body: data
                    })
                    .then(response => response.json())
                    .then(data => (data.status ?
                        Swal.fire({
                            position: 'top-end',
                            icon: 'success',
                            title: "Empleado " + (isactive == 1 ? "desactivado" : "activado") + "  con exito",
                            showConfirmButton: false,
                            timer: 2000
                        }).then((result) => {
                            /* Read more about handling dismissals below */
                            if (result.dismiss === Swal.DismissReason.timer) {
                                window.location = baseURL + "empleados"
                            }
                        }) :
                        Swal.fire({
                            position: 'top-end',
                            icon: 'error',
                            title: "Por alguna razon no podemos " + (isactive == 1 ? "desactivar" : "activar") + " este empleado",
                            showConfirmButton: false,
                            timer: 2000
                        })))
                    .catch(() => {
                        Swal.insertQueueStep({
                            icon: 'error',
                            title: 'acceso denegado..!'
                        })
                    })
            }
        }])
    });

    jQuery(document).on("click", ".changeStatusVehicles", function() {
        var userId = $(this).data("userid"),
            isactive = $(this).data("isactive"),
            hitURL = baseURL + "cambio_estado_vehiculos";

        const data = new FormData();
        data.append('id', userId);
        data.append('isactive', (isactive == 1 ? 0 : 1));

        Swal.queue([{
            title: "¿Desea " + (isactive == 1 ? "desactivar" : "activar") + " este vehiculo?",
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'SI!',
            cancelButtonText: 'No, cancelar!',
            text: 'Cambiara el estado de este vehiculo , podras reversar esta accion.',
            showLoaderOnConfirm: true,
            preConfirm: () => {
                return fetch(hitURL, {
                        method: 'POST',
                        body: data
                    })
                    .then(response => response.json())
                    .then(data => (data.status ?
                        Swal.fire({
                            position: 'top-end',
                            icon: 'success',
                            title: "Vehiculo " + (isactive == 1 ? "desactivado" : "activado") + "  con exito",
                            showConfirmButton: false,
                            timer: 2000
                        }).then((result) => {
                            /* Read more about handling dismissals below */
                            if (result.dismiss === Swal.DismissReason.timer) {
                                window.location = baseURL + "vehiculos"
                            }
                        }) :
                        Swal.fire({
                            position: 'top-end',
                            icon: 'error',
                            title: "Por alguna razon no podemos " + (isactive == 1 ? "desactivar" : "activar") + " este vehiculo",
                            showConfirmButton: false,
                            timer: 2000
                        })))
                    .catch(() => {
                        Swal.insertQueueStep({
                            icon: 'error',
                            title: 'acceso denegado..!'
                        })
                    })
            }
        }])
    });

    jQuery(document).on("click", ".setVehicleToTag", function() {
        var propietario = $(this).data("propietario"),
            serial = $(this).data("serial"),
            tag_id = $(this).data("tag_id"),
            vehicle = $(this).data("vehicle"),
            licence_plate = $(this).data("licence_plate"),
            hitURL = baseURL + "asignacion_vehiculo_etiqueta";

        const data = new FormData();
        data.append('tag_id', tag_id);
        data.append('vehicle', vehicle);

        Swal.queue([{
            title: "¿Desea asignar este vehiculo {PLACA: " + licence_plate + "} al serial {" + serial + "}?",
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'SI!',
            cancelButtonText: 'No, cancelar!',
            html: 'Para reversar esta accion debera suspender la etiqueta.',
            showLoaderOnConfirm: true,
            preConfirm: () => {
                return fetch(hitURL, {
                        method: 'POST',
                        body: data
                    })
                    .then(response => response.json())
                    .then(data => (data.status ?
                        Swal.fire({
                            position: 'top-end',
                            icon: 'success',
                            title: "Vehiculo asignado con exito",
                            showConfirmButton: false,
                            timer: 2000
                        }).then((result) => {
                            /* Read more about handling dismissals below */
                            if (result.dismiss === Swal.DismissReason.timer) {
                                window.location = baseURL + "etiquetas"
                            }
                        }) :
                        Swal.fire({
                            position: 'top-end',
                            icon: 'error',
                            title: "Por alguna razon no podemos asignar este vehiculo",
                            showConfirmButton: false,
                            timer: 2000
                        })))
                    .catch(() => {
                        Swal.insertQueueStep({
                            icon: 'error',
                            title: 'acceso denegado..!'
                        })
                    })
            }
        }])
    });

    jQuery(document).on("click", ".observationVehicles", function() {
        var name = $(this).data("name"),
            status = $(this).data("status"),
            observation = $(this).data("observation");

        Swal.fire({
            position: 'top-end',
            icon: 'info',
            title: "CONDICION DEL VEHICULO {" + name + "}",
            html: observation
        })
    });

    jQuery(document).on("click", ".getDatatenant", function() {
        var name = $(this).data("name"),
            status = $(this).data("status"),
            observation = $(this).data("observation");

        Swal.fire({
            position: 'top-end',
            icon: 'info',
            title: "CONDICION DEL VEHICULO {" + name + "}",
            html: observation
        })
    });

    jQuery(document).on("click", ".generate_report", function() {
        $("#generateReport").submit();
    });

    jQuery(document).on("click", ".observationVigilant", function() {
        var name = $(this).data("name"),
            observations = $(this).data("observation");

        Swal.fire({
            position: 'top-end',
            icon: 'info',
            title: "Observaciones {" + name + "}",
            html: observations
        })
    });

    jQuery(document).on("click", ".changeStatusVisitor", function() {
        var userId = $(this).data("userid"),
            isactive = $(this).data("isactive"),
            hitURL = baseURL + "cambio_estado_visitantes";

        const data = new FormData();
        data.append('id', userId);
        data.append('isactive', (isactive == 1 ? 0 : 1));

        Swal.queue([{
            title: "¿Desea " + (isactive == 1 ? "suspender el acceso a" : "activar el acceso a") + " este visitante?",
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'SI!',
            cancelButtonText: 'No, cancelar!',
            input: 'text',
            inputPlaceholder: 'Ingresa una observacion',
            text: 'Por favo agregue una observacion, esto ayudara a resolver posibles inconvenientes.',
            showLoaderOnConfirm: true,
            inputValidator: (value) => {
                if (!value) {
                    return 'Debes colocar una observacion!'
                }
            },
            preConfirm: () => {
                data.append('observation', (isactive == 1 ? "Suspendido por" : "Activado por") + ": " + $('.swal2-input').val());

                return fetch(hitURL, {
                        method: 'POST',
                        body: data
                    })
                    .then(response => response.json())
                    .then(data => (data.status ?
                        Swal.fire({
                            position: 'top-end',
                            icon: 'success',
                            title: "Visitante " + (isactive == 1 ? "suspendido" : "activado") + "  con exito",
                            showConfirmButton: false,
                            timer: 2000
                        }).then((result) => {
                            /* Read more about handling dismissals below */
                            if (result.dismiss === Swal.DismissReason.timer) {
                                window.location = baseURL + "visitantes"
                            }
                        }) :
                        Swal.fire({
                            position: 'top-end',
                            icon: 'error',
                            title: "Por alguna razon no podemos " + (isactive == 1 ? "suspender el acceso a" : "activar el acceso a") + " este visitante",
                            showConfirmButton: false,
                            timer: 2000
                        })))
                    .catch(() => {
                        Swal.insertQueueStep({
                            icon: 'error',
                            title: 'acceso denegado..!'
                        })
                    })
            }
        }])
    });

    jQuery(document).on("click", ".observationVisitor", function() {
        var name = $(this).data("name"),
            observations = $(this).data("observations").replace("|", "<br>");

        Swal.fire({
            position: 'top-end',
            icon: 'info',
            title: "Observaciones {" + name + "}",
            html: observations
        })
    });



    //CONTABILITY

    jQuery(document).on("click", ".changeStatusModule", function() {
        var userId = $(this).data("userid"),
            isactive = $(this).data("isactive"),
            hitURL = baseURL + "cambio_estado_modulos";

        const data = new FormData();
        data.append('id', userId);
        data.append('isactive', (isactive == 1 ? 0 : 1));

        Swal.queue([{
            title: "¿Desea " + (isactive == 1 ? "desactivar" : "activar") + " este modulo?",
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'SI!',
            cancelButtonText: 'No, cancelar!',
            text: 'Cambiara el estado de este modulo , podras reversar esta accion.',
            showLoaderOnConfirm: true,
            preConfirm: () => {
                return fetch(hitURL, {
                        method: 'POST',
                        body: data
                    })
                    .then(response => response.json())
                    .then(data => (data.status ?
                        Swal.fire({
                            position: 'top-end',
                            icon: 'success',
                            title: "Modulo " + (isactive == 1 ? "desactivado" : "activado") + "  con exito",
                            showConfirmButton: false,
                            timer: 2000
                        }).then((result) => {
                            /* Read more about handling dismissals below */
                            if (result.dismiss === Swal.DismissReason.timer) {
                                window.location = baseURL + "modulos"
                            }
                        }) :
                        Swal.fire({
                            position: 'top-end',
                            icon: 'error',
                            title: "Por alguna razon no podemos " + (isactive == 1 ? "desactivar" : "activar") + " este modulo",
                            showConfirmButton: false,
                            timer: 2000
                        })))
                    .catch(() => {
                        Swal.insertQueueStep({
                            icon: 'error',
                            title: 'acceso denegado..!'
                        })
                    })
            }
        }])
    });

    jQuery(document).on("click", ".changeStatusDocumentType", function() {
        var userId = $(this).data("userid"),
            isactive = $(this).data("isactive"),
            hitURL = baseURL + "cambio_estado_tipodocumento";

        const data = new FormData();
        data.append('id', userId);
        data.append('isactive', (isactive == 1 ? 0 : 1));

        Swal.queue([{
            title: "¿Desea " + (isactive == 1 ? "desactivar" : "activar") + " este tipo de documento?",
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'SI!',
            cancelButtonText: 'No, cancelar!',
            text: 'Cambiara el estado de este tipo de documento , podras reversar esta accion.',
            showLoaderOnConfirm: true,
            preConfirm: () => {
                return fetch(hitURL, {
                        method: 'POST',
                        body: data
                    })
                    .then(response => response.json())
                    .then(data => (data.status ?
                        Swal.fire({
                            position: 'top-end',
                            icon: 'success',
                            title: "Tipo de documento " + (isactive == 1 ? "desactivado" : "activado") + "  con exito",
                            showConfirmButton: false,
                            timer: 2000
                        }).then((result) => {
                            /* Read more about handling dismissals below */
                            if (result.dismiss === Swal.DismissReason.timer) {
                                window.location = baseURL + "tipodocumento"
                            }
                        }) :
                        Swal.fire({
                            position: 'top-end',
                            icon: 'error',
                            title: "Por alguna razon no podemos " + (isactive == 1 ? "desactivar" : "activar") + " este tipo de documento",
                            showConfirmButton: false,
                            timer: 2000
                        })))
                    .catch(() => {
                        Swal.insertQueueStep({
                            icon: 'error',
                            title: 'acceso denegado..!'
                        })
                    })
            }
        }])
    });

    jQuery(document).on("click", ".changeDataHeading", function() {
        var userId = $(this).data("userid"),
            name = $(this).data("name")
        hitURL = baseURL + "editando_rubro";

        const data = new FormData();
        data.append('id', userId);

        Swal.queue([{
            title: "¿Desea cambiar el nombre del rubro '" + name + "'?",
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'SI!',
            cancelButtonText: 'No, cancelar!',
            input: 'text',
            inputPlaceholder: 'Ingresa el nuevo nombre del rubro',
            showLoaderOnConfirm: true,
            inputValidator: (value) => {
                if (!value) {
                    return 'Debes colocar un nombre!'
                }
            },
            preConfirm: () => {
                data.append('name', $('.swal2-input').val());

                return fetch(hitURL, {
                        method: 'POST',
                        body: data
                    })
                    .then(response => response.json())
                    .then(data => (data.status ?
                        Swal.fire({
                            position: 'top-end',
                            icon: 'success',
                            title: "Se ha Realizado la actualziacion del rubro",
                            showConfirmButton: false,
                            timer: 2000
                        }).then((result) => {
                            /* Read more about handling dismissals below */
                            if (result.dismiss === Swal.DismissReason.timer) {
                                window.location = baseURL + "rubros"
                            }
                        }) :
                        Swal.fire({
                            position: 'top-end',
                            icon: 'error',
                            title: data.error,
                            showConfirmButton: false,
                            timer: 2000
                        }).then((result) => {
                            /* Read more about handling dismissals below */
                            if (result.dismiss === Swal.DismissReason.timer) {
                                window.location = baseURL + "rubros"
                            }
                        })
                    ))

            }
        }])
    });

    jQuery(document).on("click", ".changeStatusheading", function() {
        var userId = $(this).data("userid"),
            isactive = $(this).data("isactive"),
            hitURL = baseURL + "cambio_estado_rubro";

        const data = new FormData();
        data.append('id', userId);
        data.append('isactive', (isactive == 1 ? 0 : 1));

        Swal.queue([{
            title: "¿Desea " + (isactive == 1 ? "desactivar" : "activar") + " este rubro?",
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'SI!',
            cancelButtonText: 'No, cancelar!',
            text: 'Cambiara el estado de este rubro, podras reversar esta accion.',
            showLoaderOnConfirm: true,
            preConfirm: () => {
                return fetch(hitURL, {
                        method: 'POST',
                        body: data
                    })
                    .then(response => response.json())
                    .then(data => (data.status ?
                        Swal.fire({
                            position: 'top-end',
                            icon: 'success',
                            title: "Rubro " + (isactive == 1 ? "desactivado" : "activado") + "  con exito",
                            showConfirmButton: false,
                            timer: 2000
                        }).then((result) => {
                            /* Read more about handling dismissals below */
                            if (result.dismiss === Swal.DismissReason.timer) {
                                window.location = baseURL + "rubros"
                            }
                        }) :
                        Swal.fire({
                            position: 'top-end',
                            icon: 'error',
                            title: "Por alguna razon no podemos " + (isactive == 1 ? "desactivar" : "activar") + " este rubro",
                            showConfirmButton: false,
                            timer: 2000
                        })))
                    .catch(() => {
                        Swal.insertQueueStep({
                            icon: 'error',
                            title: 'acceso denegado..!'
                        })
                    })
            }
        }])
    });

    jQuery(document).on("click", ".changeStatusAccountControl", function() {
        var userId = $(this).data("userid"),
            isactive = $(this).data("isactive"),
            hitURL = baseURL + "cambio_estado_cuenta_control";

        const data = new FormData();
        data.append('id', userId);
        data.append('isactive', (isactive == 1 ? 0 : 1));

        Swal.queue([{
            title: "¿Desea " + (isactive == 1 ? "desactivar" : "activar") + " esta cuenta control?",
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'SI!',
            cancelButtonText: 'No, cancelar!',
            text: 'Cambiara el estado de esta cuenta control, podras reversar esta accion.',
            showLoaderOnConfirm: true,
            preConfirm: () => {
                return fetch(hitURL, {
                        method: 'POST',
                        body: data
                    })
                    .then(response => response.json())
                    .then(data => (data.status ?
                        Swal.fire({
                            position: 'top-end',
                            icon: 'success',
                            title: "Cuenta control " + (isactive == 1 ? "desactivado" : "activado") + "  con exito",
                            showConfirmButton: false,
                            timer: 2000
                        }).then((result) => {
                            /* Read more about handling dismissals below */
                            if (result.dismiss === Swal.DismissReason.timer) {
                                window.location = baseURL + "cuenta_control"
                            }
                        }) :
                        Swal.fire({
                            position: 'top-end',
                            icon: 'error',
                            title: "Por alguna razon no podemos " + (isactive == 1 ? "desactivar" : "activar") + " esta cuenta control",
                            showConfirmButton: false,
                            timer: 2000
                        })))
                    .catch(() => {
                        Swal.insertQueueStep({
                            icon: 'error',
                            title: 'acceso denegado..!'
                        })
                    })
            }
        }])
    });

    jQuery(document).on("click", ".changeStatusAccountHigher", function() {
        var userId = $(this).data("userid"),
            isactive = $(this).data("isactive"),
            hitURL = baseURL + "cambio_estado_cuenta_mayor";

        const data = new FormData();
        data.append('id', userId);
        data.append('isactive', (isactive == 1 ? 0 : 1));

        Swal.queue([{
            title: "¿Desea " + (isactive == 1 ? "desactivar" : "activar") + " esta cuenta mayor?",
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'SI!',
            cancelButtonText: 'No, cancelar!',
            text: 'Cambiara el estado de esta cuenta mayor, podras reversar esta accion.',
            showLoaderOnConfirm: true,
            preConfirm: () => {
                return fetch(hitURL, {
                        method: 'POST',
                        body: data
                    })
                    .then(response => response.json())
                    .then(data => (data.status ?
                        Swal.fire({
                            position: 'top-end',
                            icon: 'success',
                            title: "Cuenta mayor " + (isactive == 1 ? "desactivado" : "activado") + "  con exito",
                            showConfirmButton: false,
                            timer: 2000
                        }).then((result) => {
                            /* Read more about handling dismissals below */
                            if (result.dismiss === Swal.DismissReason.timer) {
                                window.location = baseURL + "cuenta_mayor"
                            }
                        }) :
                        Swal.fire({
                            position: 'top-end',
                            icon: 'error',
                            title: "Por alguna razon no podemos " + (isactive == 1 ? "desactivar" : "activar") + " esta cuenta mayor",
                            showConfirmButton: false,
                            timer: 2000
                        })))
                    .catch(() => {
                        Swal.insertQueueStep({
                            icon: 'error',
                            title: 'acceso denegado..!'
                        })
                    })
            }
        }])
    });

    jQuery(document).on("click", ".changeStatusAccountDetail", function() {
        var userId = $(this).data("userid"),
            isactive = $(this).data("isactive"),
            hitURL = baseURL + "cambio_estado_cuenta_detalle";

        const data = new FormData();
        data.append('id', userId);
        data.append('isactive', (isactive == 1 ? 0 : 1));

        Swal.queue([{
            title: "¿Desea " + (isactive == 1 ? "desactivar" : "activar") + " esta cuenta detalle?",
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'SI!',
            cancelButtonText: 'No, cancelar!',
            text: 'Cambiara el estado de esta cuenta detalle, podras reversar esta accion.',
            showLoaderOnConfirm: true,
            preConfirm: () => {
                return fetch(hitURL, {
                        method: 'POST',
                        body: data
                    })
                    .then(response => response.json())
                    .then(data => (data.status ?
                        Swal.fire({
                            position: 'top-end',
                            icon: 'success',
                            title: "Cuenta detalle " + (isactive == 1 ? "desactivado" : "activado") + "  con exito",
                            showConfirmButton: false,
                            timer: 2000
                        }).then((result) => {
                            /* Read more about handling dismissals below */
                            if (result.dismiss === Swal.DismissReason.timer) {
                                window.location = baseURL + "cuenta_detalle"
                            }
                        }) :
                        Swal.fire({
                            position: 'top-end',
                            icon: 'error',
                            title: "Por alguna razon no podemos " + (isactive == 1 ? "desactivar" : "activar") + " esta cuenta detalle",
                            showConfirmButton: false,
                            timer: 2000
                        })))
                    .catch(() => {
                        Swal.insertQueueStep({
                            icon: 'error',
                            title: 'acceso denegado..!'
                        })
                    })
            }
        }])
    });

    jQuery(document).on("click", ".deleteReportPay", function() {
        var userId = $(this).data("userid"),
            name = $(this).data("name"),
            photo = $(this).data("photo"),
            hitURL = baseURL + "eliminar_reporte_pago";

        const data = new FormData();
        data.append('id', userId);
        data.append('photo', photo);

        Swal.queue([{
            title: "¿Desea a eliminar este reporte de pago #" + name + "?",
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'SI!',
            cancelButtonText: 'No, cancelar!',
            showLoaderOnConfirm: true,
            preConfirm: () => {
                return fetch(hitURL, {
                        method: 'POST',
                        body: data
                    })
                    .then(response => response.json())
                    .then(data => (data.status ?
                        Swal.fire({
                            position: 'top-end',
                            icon: 'success',
                            title: "Reporte eliminado con exito",
                            showConfirmButton: false,
                            timer: 2000
                        }).then((result) => {
                            /* Read more about handling dismissals below */
                            if (result.dismiss === Swal.DismissReason.timer) {
                                window.location = baseURL + "reportesdepago"
                            }
                        }) :
                        Swal.fire({
                            position: 'top-end',
                            icon: 'error',
                            title: "Por alguna razon no podemos eliminar este reporte de pago",
                            showConfirmButton: false,
                            timer: 2000
                        })))
                    .catch(() => {
                        Swal.insertQueueStep({
                            icon: 'error',
                            title: 'acceso denegado..!'
                        })
                    })
            }
        }])
    });

    jQuery(document).on("click", ".changeStatusReportPay", function() {
        var userId = $(this).data("userid"),
            isactive = $(this).data("isactive"),
            hitURL = baseURL + "cambio_estatus_reporte_pago";

        const data = new FormData();
        data.append('id', userId);
        data.append('isactive', isactive);

        Swal.queue([{
            title: "¿Desea " + (isactive == 1 ? "aprobar" : "rechazar") + " este reporte de pago?",
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'SI!',
            cancelButtonText: 'No, cancelar!',
            showLoaderOnConfirm: true,
            preConfirm: () => {
                return fetch(hitURL, {
                        method: 'POST',
                        body: data
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.status) {
                            Swal.queue([{
                                title: 'Factura procesada con exito! ¿Desea imprimir esta factura?',
                                showCancelButton: true,
                                confirmButtonColor: '#3085d6',
                                cancelButtonColor: '#d33',
                                confirmButtonText: 'SI!',
                                cancelButtonText: 'No, cancelar!',
                                showLoaderOnConfirm: true,
                                preConfirm: () => {
                                    window.location = baseURL + "reportesdepago"
                                    window.open(baseURL + "reporte_factura/" + data.daily, '_blank');
                                },

                            }]).then((result) => {
                                if (result.dismiss) {
                                    window.location = baseURL + "reportesdepago"
                                }
                            })
                        } else {
                            Swal.fire({
                                position: 'top-end',
                                icon: 'error',
                                title: "No es posible procesar el pago en estos momentos",
                                showConfirmButton: false,
                                timer: 2000
                            })
                        }

                    })
                    .catch(() => {
                        Swal.insertQueueStep({
                            icon: 'error',
                            title: 'acceso denegado..!'
                        })
                    })
            }
        }])
    });

    jQuery(document).on("click", ".changeCashierClosing", function() {
        var userId = $(this).data("userid"),
            hitURL = baseURL + "cambio_estatus_cierre";

        const data = new FormData();
        data.append('id', userId);

        Swal.queue([{
            title: "¿Desea anular este cierre de caja?",
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'SI!',
            cancelButtonText: 'No, cancelar!',
            showLoaderOnConfirm: true,
            input: 'text',
            inputPlaceholder: 'Ingresa una observacion',
            showLoaderOnConfirm: true,
            inputValidator: (value) => {
                if (!value) {
                    return 'Debes colocar una observacion!'
                }
            },
            preConfirm: () => {
                data.append('observation', $('.swal2-input').val());
                return fetch(hitURL, {
                        method: 'POST',
                        body: data
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.status) {
                            Swal.fire({
                                position: 'top-end',
                                icon: 'success',
                                title: "Cierre de caja anulado",
                                showConfirmButton: false,
                                timer: 2000
                            }).then((result) => {
                                if (result.dismiss) {
                                    window.location = baseURL + "cierresdecaja"
                                }
                            })
                        } else {
                            Swal.fire({
                                position: 'top-end',
                                icon: 'error',
                                title: "No es posible procesar el pago en estos momentos",
                                showConfirmButton: false,
                                timer: 2000
                            })
                        }

                    })
                    .catch(() => {
                        Swal.insertQueueStep({
                            icon: 'error',
                            title: 'acceso denegado..!'
                        })
                    })
            }
        }])
    });

    jQuery(document).on("click", ".sendParameter", function() {
        console.log("afadfa");
        Swal.queue([{
            title: "¿Desea actualizar estos parametros?",
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'SI!',
            cancelButtonText: 'No, cancelar!',
            showLoaderOnConfirm: true,
            preConfirm: () => {
                document.forms["sndParameter"].submit();
            }
        }])
    });

    jQuery(document).on("click", ".searchList", function() {

    });

    jQuery(document).on("change", "#selectCondominium", function() {
        var id = $("#selectCondominium").val(),
            name = $("#selectCondominium option:selected").html();
        hitURL = baseURL + "cambio_condominio";

        const data = new FormData();
        data.append('id', id);
        Swal.queue([{
            title: "¿Desea cambiar su condominio actual por: " + name + "?",
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'SI!',
            cancelButtonText: 'No, cancelar!',
            text: 'Si esta en proceso de creacion de datos, debe darle guardar antes de hacer este proceso.',
            showLoaderOnConfirm: true,
            preConfirm: () => {
                return fetch(hitURL, {
                        method: 'POST',
                        body: data
                    })
                    .then(response => response.json())
                    .then(data => (data.status ?
                        Swal.fire({
                            position: 'top-end',
                            icon: 'success',
                            title: "Se ha realizado con exito el cambio, la proxima vez que entres estaras con este condominio",
                            showConfirmButton: false,
                            timer: 2000
                        }).then((result) => {
                            /* Read more about handling dismissals below */
                            if (result.dismiss === Swal.DismissReason.timer) {
                                window.location = baseURL
                            }
                        }) :
                        Swal.fire({
                            position: 'top-end',
                            icon: 'error',
                            title: "Por alguna razon no podemos cambiar al condominio: " + name,
                            showConfirmButton: false,
                            timer: 1000
                        })))
                    .catch(() => {
                        Swal.insertQueueStep({
                            icon: 'error',
                            title: 'acceso denegado..!'
                        });
                    })
            },
            allowOutsideClick: () => $('#selectCondominium option[value=' + c + ']').prop('selected', true),
            allowEscapeKey: () => $('#selectCondominium option[value=' + c + ']').prop('selected', true)
        }]).then(function() {
            $('#selectCondominium option[value=' + c + ']').prop('selected', true);
        }).catch(swal.noop);
    });


    /* REPORT */
    jQuery(document).on("click", ".report_balance_verification", function() {
        var date_start = $("#start_date").val() == null ? "" : $("#start_date").val(),
            date_ending = $("#ending_date").val() == null ? "" : $("#ending_date").val(),
            hitURL = baseURL + "reporte_balance_comprobacion?date_start=" + date_start + "&date_ending=" + date_ending;
        window.open(hitURL, '_blank');
    });

    jQuery(document).on("click", ".report_result_accumulated", function() {

        var date_start = $("#start_date_ra").val() == null ? "" : $("#start_date_ra").val(),
            date_ending = $("#ending_date_ra").val() == null ? "" : $("#ending_date_ra").val(),
            heading_1 = $("#heading_1_ra").val() == null ? "" : $("#heading_1_ra").val(),
            heading_2 = $("#heading_2_ra").val() == null ? "" : $("#heading_2_ra").val(),
            hitURL = baseURL + "reporte_resultado_acumulado?date_start=" + date_start + "&date_ending=" + date_ending + "&heading_1=" + heading_1 + "&heading_2=" + heading_2;
        window.open(hitURL, '_blank');
    });
});