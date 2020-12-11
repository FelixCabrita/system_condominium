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
                            timer: 1500
                        }).then((result) => {
                            /* Read more about handling dismissals below */
                            if (result.dismiss === Swal.DismissReason.timer) {
                                window.location = baseURL + "usuarios"
                            }
                        }) :
                        Swal.fire({
                            position: 'top-end',
                            icon: 'error',
                            title: "Por alguna razon no podemos eliminar este usuario",
                            showConfirmButton: false,
                            timer: 1500
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
                            timer: 1500
                        }).then((result) => {
                            /* Read more about handling dismissals below */
                            if (result.dismiss === Swal.DismissReason.timer) {
                                window.location = baseURL + "condominios"
                            }
                        }) :
                        Swal.fire({
                            position: 'top-end',
                            icon: 'error',
                            title: "Por alguna razon no podemos eliminar este condominio",
                            showConfirmButton: false,
                            timer: 1500
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
                            timer: 1500
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
                            timer: 1500
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
                            timer: 1500
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
                            timer: 1500
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

});