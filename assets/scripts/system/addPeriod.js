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
            start: {
                required: true,
                date: true
            },
            end: {
                required: true,
                date: true
            }
        },
        messages: {
            name: {
                required: "Este campo es necesario"
            },
            start: {
                required: "Este campo es necesario",
                date: "Debe colocar un formato de fecha valido"
            },
            end: {
                required: "Este campo es necesario",
                date: "Debe colocar un formato de fecha valido"
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