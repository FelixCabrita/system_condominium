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
    $("#document").focus();

});

jQuery(document).ready(function() {
    $(".alertNotAccess").hide();
    $("#document").focus();
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
            document: {
                required: true
            },
            name: {
                required: true
            },
            edifice: {
                required: true
            },
            apartment: {
                required: true
            }
        },
        messages: {
            document: {
                required: "Este campo es necesario"
            },
            name: {
                required: "Este campo es necesario"
            },
            edifice: {
                required: "Este campo es necesario"
            },
            apartment: {
                required: "Este campo es necesario"
            }
        }
    });

    $("#edifice").change(function() {
        var stateID = $(this).val();
        if (stateID) {
            $.ajax({
                url: 'obtener_apartemantos/' + stateID,
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
            $('select[name="apartment"]').empty();
            $('select[name="apartment"]').append('<option value="">SELECCIONE UN EDIFICIO</option>');
        }
    });


    function search_document() {
        var document = $("#document").val(),
            hitURL = baseURL + "chequeo_visitante";

        const data = new FormData();
        data.append('document', document);
        if (document !== '') {
            fetch(hitURL, {
                    method: 'POST',
                    body: data
                })
                .then(response => response.json())
                .then(data => {
                    $("#send").show();
                    $(".alertNotAccess").hide();

                    if (data.status) {
                        var visitor = data.visitor;
                        $("#name").val(visitor.name);
                        $("#mobile").val(visitor.mobile);
                        if (visitor.type == 2) {
                            $("#type").val("EMPLEADO");
                        } else {
                            $("#type").val("VISITANTE");
                        }
                        if (visitor.visitorStatus === '1') {

                            if (visitor.status === '1') {
                                $('#edifice option[value=' + visitor.edifice + ']').prop('selected', true);
                                $.ajax({
                                    url: 'obtener_apartemantos/' + visitor.edifice,
                                    type: "GET",
                                    dataType: "json",
                                    success: function(data) {
                                        $('select[name="apartment"]').empty();
                                        $('select[name="apartment"]').append('<option value="">SELECCIONE UN APARTAMENTO</option>');
                                        $.each(data, function(key, value) {
                                            $('select[name="apartment"]').append('<option value="' + value.id + '" ' + (visitor.apartment === value.id ? "selected" : "") + ' >' + value.apartment + '</option>');
                                        });
                                    }
                                });
                                $('#status option[value=' + 2 + ']').prop('selected', true);
                                $("#observation").val(visitor.observation);
                                $("#licence_plate").val(visitor.licence_plate);

                            } else {
                                $('#status option[value=' + 1 + ']').prop('selected', true);
                            }

                            if (visitor.type == 2) {
                                $.ajax({
                                    url: './obtener_empleado/' + document,
                                    type: "GET",
                                    dataType: "json",
                                    success: function(data) {
                                        console.log(data);
                                        $('#edifice option[value=' + data.edifice + ']').prop('selected', true);
                                        var apartment = data.apto_id;
                                        $.ajax({
                                            url: 'obtener_apartemantos/' + data.edifice,
                                            type: "GET",
                                            dataType: "json",
                                            success: function(data) {
                                                $('select[name="apartment"]').empty();
                                                $('select[name="apartment"]').append('<option value="">SELECCIONE UN APARTAMENTO</option>');
                                                $.each(data, function(key, value) {
                                                    $('select[name="apartment"]').append('<option value="' + value.id + '" ' + (visitor.apartment === value.id ? "selected" : "") + ' >' + value.apartment + '</option>');
                                                });

                                                $('#apartment option[value=' + apartment + ']').prop('selected', true);
                                            }
                                        });


                                    }
                                });
                            }
                        } else {
                            $("#send").hide();
                            $(".alertNotAccess").show();
                            $("#textAlert").html("El visitante " + visitor.name + " no puede acceder al condominio");
                            Swal.fire({
                                position: 'top-end',
                                icon: 'error',
                                title: "El visitante " + visitor.name + " no puede acceder al condominio",
                                text: visitor.visitorObservation
                            });
                        }



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