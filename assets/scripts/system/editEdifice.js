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

    var editForm = $("#edit");

    var validator = editForm.validate({

        rules: {
            manager: {
                required: true
            }
        },
        messages: {
            manager: {
                required: "Este campo es necesario"
            }
        }
    });
});