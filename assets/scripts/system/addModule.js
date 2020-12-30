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
                required: true,

            },
            code: {
                required: true,
                remote: {
                    url: baseURL + "chequeo_modulos",
                    type: "post"
                }
            }
        },
        messages: {
            name: {
                required: "Este campo es necesario"
            },
            code: {
                required: "Este campo es necesario",
                remote: "Esta combinacion ya existe"
            }
        }
    });
});