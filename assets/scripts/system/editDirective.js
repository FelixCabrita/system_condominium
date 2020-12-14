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

    var addForm = $("#edit");

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
        var stateID = $(this).val();
        $('select[name="apartment"]').empty();
        $('select[name="apartment"]').append('<option value="">SELECCIONE UN EDIFICIO</option>');

        if (stateID) {
            $.ajax({
                url: '../obtener_apartemantos/' + stateID,
                type: "GET",
                dataType: "json",
                success: function(data) {

                    console.log(data);
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



    $.ajax({
        url: '../obtener_apartemantos/' + e,
        type: "GET",
        dataType: "json",
        success: function(data) {

            console.log(data);
            $('select[name="apartment"]').empty();
            $('select[name="apartment"]').append('<option value="">SELECCIONE UN APARTAMENTO</option>');
            $.each(data, function(key, value) {
                var s = (a === value.id) ? "selected" : "";
                $('select[name="apartment"]').append('<option value="' + value.id + '" ' + s + ' >' + value.apartment + '</option>');
            });
        }
    });

});