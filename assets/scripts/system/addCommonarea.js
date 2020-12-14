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
            max_person: {
                required: true,
                number: true
            }
        },
        messages: {
            name: {
                required: "Este campo es necesario"
            },
            max_person: {
                required: "Este campo es necesario",
                number: "Esta caja de texto solo admite numeros y el simbolo (.) como separador decimal"
            }
        }
    });
});