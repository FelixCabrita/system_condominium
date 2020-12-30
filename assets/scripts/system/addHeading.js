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
            code: {
                required: true,
                remote: {
                    url: baseURL + "chequeo_rubro",
                    type: "post"
                }
            },
            name: {
                required: true
            }
        },
        messages: {
            code: {
                required: "Este campo es necesario",
                remote: "Esta combinacion ya existe"
            },
            name: {
                required: "Este campo es necesario"
            }
        }
    });
});