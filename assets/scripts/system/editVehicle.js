/**
 * File : addUser.js
 * 
 * This file contain the validation of add user form
 * 
 * Using validation plugin : jquery.validate.js
 * 
 * @author Felix Cabrita
 */


jQuery(document).ready(function() {

    jQuery(document).on("click", "#search", function() {
        search_document();
    });

    $("#document").keypress(function(event) {
        if (event.which === 13 || event.which === 9)
            search_document();
    });

    var addForm = $("#add");

    var validator = addForm.validate({
        rules: {
            model: {
                required: true
            },
            brand: {
                required: true
            },
            year: {
                required: true,
                number: true
            },
            colour: {
                required: true
            },
            licence_plate: {
                required: true,
                remote: {
                    url: baseURL + "chequeo_vehiculos",
                    type: "post",
                    data: { id: function() { return $("#id").val(); } }
                }
            }
        },
        messages: {
            model: {
                required: "Este campo es necesario"
            },
            brand: {
                required: "Este campo es necesario"
            },
            year: {
                required: "Este campo es necesario",
                number: "Por favor soloi ingrese numeros"
            },
            colour: {
                required: "Este campo es necesario"
            },
            licence_plate: {
                required: "Este campo es necesario",
                remote: "Ya existe un vehiculo con este numero de placa"
            }
        }
    });


    function search_document() {
        var document = $("#document").val(),
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
                        $("#type").val(tenant.type == 6 ? "PROPIETARIO APTO" : "INQUILINO");
                        $("#tenant").val(tenant.id);

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