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
                required: true,

            },
            code: {
                required: true,
                remote: {
                    url: baseURL + "chequeo_modulos",
                    type: "post",
                    data: { id: function() { return $("#id").val(); } }
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