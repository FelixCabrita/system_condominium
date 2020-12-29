/**
 * File : addUser.js
 * 
 * This file contain the validation of add user form
 * 
 * Using validation plugin : jquery.validate.js
 * 
 * @author Felix Cabrita
 */

$(document).ready(function() {
    search_document();
    jQuery(document).on("click", "#search", function() {
        search_document();
    });

    $("#manager").keypress(function(event) {
        if (event.which === 13 || event.which === 9)
            search_document();
    });

    function search_document() {
        var document = $("#manager").val(),
            hitURL = baseURL + "obtener_informacion_cliente";

        const data = new FormData();
        data.append('document', document);
        if (document !== '') {
            fetch(hitURL, {
                    method: 'POST',
                    body: data
                })
                .then(response => response.json())
                .then(data => {
                    if (data.status) {
                        var tenant = data.tenant;
                        $("#name").val(tenant.name);
                    } else {
                        Swal.fire({
                            position: 'top-end',
                            icon: 'error',
                            title: "No hemos encontrado alguna coincidencia con el documento: " + document,
                            showConfirmButton: false,
                            timer: 2000
                        });
                    }
                })
                .catch(() => {
                    Swal.insertQueueStep({
                        icon: 'error',
                        title: 'acceso denegado..!'
                    })
                })
        }
    }
});