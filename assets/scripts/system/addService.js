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
            name: {
                required: true
            },
            amount: {
                required: true,
                number: true
            },
            code: {
                required: true,
                remote: {
                    url: baseURL + "chequeo_servicio",
                    type: "post"
                }
            }
        },
        messages: {
            name: {
                required: "Este campo es necesario"
            },
            amount: {
                required: "Este campo es necesario",
                number: "Esta caja de texto solo admite numeros y el simbolo (.) como separador decimal"
            },
            code: {
                required: "Este campo es necesario",
                remote: "Esta combinacion ya existe"
            }
        }
    });
});