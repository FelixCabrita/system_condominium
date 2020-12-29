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

    var addForm = $("#edit"),
        type = "",
        tenant = "";
    search_document();

    var validator = addForm.validate({
        rules: {
            name: {
                required: true
            },
            chargue: {
                required: true
            },
            edifice: {
                required: true
            },
            apartment: {
                required: true
            },
            number_parking: {
                required: true
            },
            document: {
                required: true,
                remote: {
                    url: baseURL + "chequeo_miembro",
                    type: "post",
                    data: { id: function() { return $("#id").val(); } }
                }
            }
        },
        messages: {
            name: {
                required: "Este campo es necesario"
            },
            charge: {
                required: "Este campo es necesario"
            },
            edifice: {
                required: "Este campo es necesario"
            },
            apartment: {
                required: "Este campo es necesario"
            },
            number_parking: {
                required: "Este campo es necesario"
            },
            document: {
                required: "Este campo es necesario",
                remote: "Este miembro ya existe"
            }
        }
    });

    $("#edifice").change(function() {
        select_Edifice();

    });




    jQuery(document).on("click", "#search", function() {
        search_document();
    });

    $("#document").keypress(function(event) {
        if (event.which === 13 || event.which === 9)
            search_document();
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
                        tenant = data.tenant;
                        type = tenant.type;
                        $("#name").val(tenant.name);

                        const data1 = new FormData();
                        data1.append('role', type);
                        data1.append('id', tenant.id);
                        var hitURL1 = baseURL + "obtener_asignaciones";

                        fetch(hitURL1, {
                                method: 'POST',
                                body: data1
                            })
                            .then(response => response.json())
                            .then(data1 => {
                                if (data1.status) {
                                    if (type === '7') {
                                        $('select[name="edifice"]').empty();
                                        $('select[name="apartment"]').empty();
                                        var asignaciones = data1.asig;
                                        $.each(asignaciones, function(key, value) {
                                            $('select[name="edifice"]').append('<option value="' + value.edifice + '">' + value.edifice_code + '</option>');
                                            $('select[name="apartment"]').append('<option value="' + value.apartment + '">' + value.apartment_code + '</option>');
                                        });

                                    } else {
                                        var asignaciones = data1.asig;
                                        $('select[name="edifice"]').empty();
                                        $('select[name="edifice"]').append('<option value="">SELECCIONE UN EDIFICIO</option>');
                                        $('select[name="apartment"]').empty();
                                        $('select[name="apartment"]').append('<option value="">SELECCIONE UN APARTAMENTO</option>');
                                        $.each(asignaciones, function(key, value) {
                                            $('select[name="edifice"]').append('<option value="' + value.edifice + '">' + value.edifice_code + '</option>');
                                        });
                                        console.log(edifice_select)

                                        $('#edifice option[value=' + edifice_select + ']').prop('selected', true)

                                        select_Edifice();


                                    }
                                } else {
                                    Swal.fire({
                                        position: 'top-end',
                                        icon: 'error',
                                        title: "Esta documento no tiene apartamentos asignados",
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


    function select_Edifice() {
        var stateID = $("#edifice").val();
        if (stateID) {
            if (type === '') {
                $.ajax({
                    url: '../obtener_apartemantos/' + stateID,
                    type: "GET",
                    dataType: "json",
                    success: function(data) {

                        $('select[name="apartment"]').empty();
                        $('select[name="apartment"]').append('<option value="">SELECCIONE UN APARTAMENTO</option>');
                        $.each(data, function(key, value) {
                            $('select[name="apartment"]').append('<option value="' + value.id + '">' + value.apartment + '</option>');
                        });
                    }
                });
            } else {
                const data1 = new FormData();
                data1.append('role', type);
                data1.append('id', tenant.id);
                data1.append('edifice', stateID);
                var hitURL1 = baseURL + "obtener_asignaciones";

                fetch(hitURL1, {
                        method: 'POST',
                        body: data1
                    })
                    .then(response => response.json())
                    .then(data1 => {
                        if (data1.status) {
                            var asignaciones = data1.asig;
                            $('select[name="apartment"]').empty();
                            $('select[name="apartment"]').append('<option value="">SELECCIONE UN APARTAMENTO</option>');
                            $.each(asignaciones, function(key, value) {
                                $('select[name="apartment"]').append('<option value="' + value.apartment + '">' + value.apartment_code + '</option>');
                            });


                            $('#apartment option[value=' + apartment_select + ']').prop('selected', true)
                        } else {
                            Swal.fire({
                                position: 'top-end',
                                icon: 'error',
                                title: "Esta documento no tiene apartamentos asignados",
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
        } else {
            $('select[name="apartment"]').empty();
            $('select[name="apartment"]').append('<option value="">SELECCIONE UN EDIFICIO</option>');
        }
    }
});