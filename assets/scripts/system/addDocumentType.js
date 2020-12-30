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
            module: {
                required: true,
            },
            name: {
                required: true,
            },
            code: {
                required: true,
                remote: {
                    url: baseURL + "chequeo_tipodocumento",
                    type: "post"
                }
            }
        },
        messages: {
            module: {
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
});