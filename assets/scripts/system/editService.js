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
            }
        },
        messages: {
            name: {
                required: "Este campo es necesario"
            }
        }
    });
});