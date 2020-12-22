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
    $("#edifice").keyup(function() {
        $("#code").val($("#block").val() + $("#edifice").val());
    });

    $("#block").keyup(function() {
        $("#code").val($("#block").val() + $("#edifice").val());
    });
    var validator = addForm.validate({

        rules: {
            edifice: {
                required: true
            },
            manager: {
                required: true
            },
            block: {
                required: true
            },
            code: {
                required: true,
                remote: {
                    url: baseURL + "chequeo_edificio",
                    type: "post"
                }
            }
        },
        messages: {
            edifice: {
                required: "Este campo es necesario"
            },
            block: {
                required: "Este campo es necesario"
            },
            manager: {
                required: "Este campo es necesario"
            },
            code: {
                required: "Este campo es necesario",
                remote: "Esta combinacion ya existe"
            }
        }
    });



    $("#manager").focus(function() {
        validator.form();
        validator.element("#code");
    });
    $("#observation").focus(function() {
        validator.form();
        validator.element("#code");
    });
});