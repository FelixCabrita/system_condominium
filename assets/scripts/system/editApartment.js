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
            number_parking: {
                required: true
            }
        },
        messages: {
            number_parking: {
                required: "Este campo es necesario"
            }
        }
    });
});