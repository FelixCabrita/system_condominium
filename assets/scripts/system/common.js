/**
 * @author Felix Cabrita
 */

jQuery(document).ready(function() {


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
                            /* Read more about handling dismissals below */
                            if (result.dismiss === Swal.DismissReason.timer) {
                                window.location = baseURL + "condominios"
                            }
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
                            title: "Por alguna razon no podemos " + (isactive == 1 ? "desactivar" : "activar") + " este Periodo",
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
                            timer: 2000
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

});