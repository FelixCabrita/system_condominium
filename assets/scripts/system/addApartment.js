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

    $("#apartment").keyup(function() {
        $("#code").val($("#edifice").val() + $("#apartment").val());
    });

    $("#edifice").change(function() {
        $("#code").val($("#edifice").val() + $("#apartment").val());
    });

    var validator = addForm.validate({
        rules: {
            edifice: {
                required: true
            },
            apartment: {
                required: true
            },
            period: {
                required: true
            },
            code: {
                required: true,
                remote: {
                    url: baseURL + "chequeo_apartamento",
                    type: "post"
                }
            }
        },
        messages: {
            edifice: {
                required: "Este campo es necesario"
            },
            apartment: {
                required: "Este campo es necesario"
            },
            number_parking: {
                required: "Este campo es necesario"
            },
            code: {
                required: "Este campo es necesario",
                remote: "Esta combinacion ya existe"
            }
        }
    });



    $("#number_parking").focus(function() {
        validator.form();
        validator.element("#code");
    });
    $("#condition").focus(function() {
        validator.form();
        validator.element("#code");
    });
});