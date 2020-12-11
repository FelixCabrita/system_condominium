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

    var addUserForm = $("#addUser");

    var validator = addUserForm.validate({

        rules: {
            fname: {
                required: true
            },
            email: {
                required: true,
                email: true,
                remote: {
                    url: baseURL + "checkEmailExists",
                    type: "post"
                }
            },
            password: {
                required: true
            },
            cpassword: {
                required: true,
                equalTo: "#password"
            },
            mobile: {
                required: true,
                digits: true
            }
        },
        messages: {
            fname: {
                required: "Este campo es necesario"
            },
            email: {
                required: "Este campo es necesario",
                email: "Por favor ingrese una dirección de correo electrónico válida",
                remote: "Correo electrónico ya tomado"
            },
            password: {
                required: "Este campo es necesario"
            },
            cpassword: {
                required: "Este campo es necesario",
                equalTo: "Ingrese la misma contraseña"
            },
            mobile: {
                required: "Este campo es necesario",
                digits: "Por favor solo inserte numeros"
            }
        }
    });
});