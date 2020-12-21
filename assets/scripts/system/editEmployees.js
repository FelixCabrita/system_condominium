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
            apartment: {
                required: true
            },
            mobile: {
                required: true,
                digits: true
            },
            started_work: {
                required: true,
                date: true
            },
            work_type: {
                required: true
            },
            document: {
                required: true,
                remote: {
                    url: baseURL + "chequeo_empleados",
                    type: "post",
                    data: { id: function() { return $("#id").val(); }, apartment: function() { return $("#apartment").val(); } }
                }
            }
        },
        messages: {
            name: {
                required: "Este campo es necesario"
            },
            apartment: {
                required: "Este campo es necesario"
            },
            mobile: {
                required: "Este campo es necesario",
                digits: "solo debe colocar numeros en esta casilla"
            },
            started_work: {
                required: "Este campo es necesario",
                date: "solo debe colocar fecha en esta casilla"
            },
            work_type: {
                required: "Este campo es necesario"
            },
            document: {
                required: "Este campo es necesario",
                remote: "Esta combinacion ya existe"
            }
        }
    });

});