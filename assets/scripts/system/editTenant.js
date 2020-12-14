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
                required: true
            },
            mobile: {
                required: true,
                digits: true
            },
            landline_phone: {
                required: true,
                digits: true
            },
            email: {
                required: true,
                email: true
            },
            document: {
                required: true,
                remote: {
                    url: baseURL + "chequeo_inquilinos",
                    type: "post",
                    data: { id: function() { return $("#id").val(); } }
                }
            }
        },
        messages: {
            name: {
                required: "Este campo es necesario"
            },
            mobile: {
                required: "Este campo es necesario",
                digits: "solo debe colocar numeros en esta casilla"
            },
            landline_phone: {
                required: "Este campo es necesario",
                digits: "solo debe colocar numeros en esta casilla"
            },
            email: {
                required: "Este campo es necesario",
                email: "Ingrese una dirección de correo electrónico válida",
            },
            document: {
                required: "Este campo es necesario",
                remote: "Esta combinacion ya existe"
            }
        }
    });

});