/**
 * File : editUser.js 
 * 
 * This file contain the validation of edit user form
 * 
 * @author Felix Cabrita
 */
$(document).ready(function() {

    var editUserForm = $("#editUser");

    var validator = editUserForm.validate({

        rules: {
            fname: { required: true },
            email: { required: true, email: true, remote: { url: baseURL + "checkEmailExists", type: "post", data: { userId: function() { return $("#userId").val(); } } } },
            cpassword: { equalTo: "#password" },
            mobile: { required: true, digits: true },
            division: { required: true, selected: true }
        },
        messages: {
            fname: { required: "Este campo es necesario" },
            email: { required: "Este campo es necesario", email: "Please enter valid email address", remote: "Email already taken" },
            cpassword: { equalTo: "Please enter same password" },
            mobile: { required: "Este campo es necesario", digits: "Por favor solo inserte numeros" }
        }
    });

    var editProfileForm = $("#editProfile");

    var validator = editProfileForm.validate({

        rules: {
            fname: { required: true },
            email: { required: true, email: true, remote: { url: baseURL + "checkEmailExists", type: "post", data: { userId: function() { return $("#userId").val(); } } } },
            mobile: { required: true, digits: true },
        },
        messages: {
            fname: { required: "Este campo es necesario" },
            email: { required: "Este campo es necesario", email: "Please enter valid email address", remote: "Email already taken" },
            mobile: { required: "Este campo es necesario", digits: "Por favor solo inserte numeros" },
        }
    });

});