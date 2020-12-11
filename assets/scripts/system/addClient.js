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

    var addClientForm = $("#addClient");

    var validator = addClientForm.validate({

        rules: {
            code: { required: true, remote: { url: baseURL + "checkCodeExistc", type: "post" } },
            name: { required: true },
            ruc: { required: true },
            email: {
                required: true,
                email: true
            },
            address: { required: true },
            phone_1: {
                required: true,
                digits: true
            },
            phone_2: {
                required: true,
                digits: true
            },
            vendor: { required: true, selected: true },
            route: { required: true, selected: true }
        },
        messages: {
            code: {
                required: "Este campo es necesario",
                remote: "Este codigo ya existe"
            },
            name: {
                required: "Este campo es necesario"
            },
            ruc: {
                required: "Este campo es necesario"
            },
            email: {
                required: "Este campo es necesario",
                email: "Please enter valid email address"
            },
            address: {
                required: "Este campo es necesario"
            },
            phone_1: {
                required: "Este campo es necesario",
                digits: "Por favor solo inserte numeros"
            },
            phone_2: {
                required: "Este campo es necesario",
                digits: "Por favor solo inserte numeros"
            },
            vendor: {
                required: "Este campo es requerido",
                selected: "Por favor seleccione al menos una opción"
            },
            route: {
                required: "Este campo es requerido",
                selected: "Por favor seleccione al menos una opción"
            }
        }
    });
});