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

    var addForm = $("#add");

    var validator = addForm.validate({
        rules: {
            serial: {
                required: true,
                number: true
            },
            edifice: {
                required: true
            },
            apartment: {
                required: true
            }
        },
        messages: {
            serial: {
                required: "Este campo es necesario",
                number: "Esta caja de texto solo admite numeros"
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


});