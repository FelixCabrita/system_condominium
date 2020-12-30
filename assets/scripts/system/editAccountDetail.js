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
            accountcontrol: {
                required: true,
            },
            name: {
                required: true,
            },
            code: {
                required: true,
                remote: {
                    url: baseURL + "chequeo_cuenta_detalle",
                    type: "post"
                }
            }
        },
        messages: {
            accountcontrol: {
                required: "Este campo es necesario"
            },
            name: {
                required: "Este campo es necesario"
            },
            code: {
                required: "Este campo es necesario",
                remote: "Esta combinacion ya existe"
            }
        }
    });

    jQuery(document).on("change", "#apartment", function() {

        var edifice = $(this).children('option:selected').data('edifice'),
            apartment = $(this).children('option:selected').data('apartment'),
            valor = $(this).val();
        if (valor !== '') {
            $("#name").val(edifice + '-' + apartment)
        } else {
            $("#name").val("")
        }

    });
});