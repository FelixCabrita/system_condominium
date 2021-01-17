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
    var addForm = $("#exit_cashier");
    getinfo();
    total();
    var validator = addForm.validate({

        rules: {
            cash_input: {
                required: true,
            },
            cash_exit: {
                required: true,
            },
            card_input: {
                required: true,
            },
            card_exit: {
                required: true,
            },
            transfer_input: {
                required: true,
            },
            transfer_exit: {
                required: true,
            },
            check_input: {
                required: true,
            },
            check_exit: {
                required: true,
            }
        },
        messages: {
            cash_input: {
                required: "Este campo es necesario"
            },
            cash_exit: {
                required: "Este campo es necesario"
            },
            card_input: {
                required: "Este campo es necesario"
            },
            card_exit: {
                required: "Este campo es necesario"
            },
            transfer_input: {
                required: "Este campo es necesario"
            },
            transfer_exit: {
                required: "Este campo es necesario"
            },
            check_input: {
                required: "Este campo es necesario"
            },
            check_exit: {
                required: "Este campo es necesario"
            }
        }
    });

    $("#cash_input, #cash_exit").keyup(function() {
        calculate("#cash_input", "#cash_exit", "#cash_result");
    });

    $("#card_input, #card_exit").keyup(function() {
        calculate("#card_input", "#card_exit", "#card_result");
    });

    $("#transfer_input, #transfer_exit").keyup(function() {
        calculate("#transfer_input", "#transfer_exit", "#transfer_result");

    });

    $("#check_input, #check_exit").keyup(function() {
        calculate("#check_input", "#check_exit", "#check_result");

    });

    $("#transfer_input, #transfer_exit").keyup(function() {
        calculate("#transfer_input", "#transfer_exit", "#check_result");

    });
    $("#card_input, #card_exit").keyup(function() {
        calculate("#card_input", "#card_exit", "#check_result");

    });

    jQuery(document).on("click", ".clear", function() {
        $("#exit_cashier")[0].reset();
        getinfo();
    });


    jQuery(document).on("click", ".send", function() {
        var total_01 = parseFloat($(".01_total").html()),
            total_02 = parseFloat($(".02_total").html())
        if ($.isNumeric(total_01) && $.isNumeric(total_02)) {
            if (total_01 == total_02 && total_01 > 0) {
                Swal.queue([{
                    title: "¿Hacer este cierre de caja?",
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'SI!',
                    html: "ENTRADA <b> " + total_01.toFixed(2) + " </b> - SISTEMA <b> " + total_02.toFixed(2) + " </b>",
                    cancelButtonText: 'No, cancelar!',
                    showLoaderOnConfirm: true,
                    input: 'text',
                    inputPlaceholder: 'Observacion',
                    preConfirm: () => {
                        $("#observation").val($('.swal2-input').val());
                        document.forms["exit_cashier"].submit();
                    }
                }])
            } else {
                Swal.fire({
                    position: 'top-end',
                    icon: 'error',
                    title: "No permitimos el cierre con descuadre",
                    showConfirmButton: false,
                    timer: 4000
                });
            }

        }
    });

    function calculate(input_, exit_, result_) {
        var input = parseFloat($(input_).val()),
            exit = parseFloat($(exit_).val());
        var result = ($.isNumeric(input) ? input : 0) - ($.isNumeric(exit) ? exit : 0);
        $(result_).val(result.toFixed(2));
        total();
    }

    function getinfo() {
        var date = $("#date").val(),
            hitURL = baseURL + "obtener_cierre";

        const data = new FormData();
        data.append('date', date);

        fetch(hitURL, {
                method: 'POST',
                body: data
            })
            .then(response => response.json())
            .then(data => {
                console.log()
                if (data.status) {

                    $("#cash_exit").val(data.cash);
                    $("#card_exit").val(data.card);
                    $("#transfer_exit").val(data.transfer);
                    $("#check_exit").val(data.check);
                    total();
                }

            });

    }

    function total() {
        var cash_input = parseFloat($("#cash_input").val()),
            cash_exit = parseFloat($("#cash_exit").val());
        var card_input = parseFloat($("#card_input").val()),
            card_exit = parseFloat($("#card_exit").val());
        var transfer_input = parseFloat($("#transfer_input").val()),
            transfer_exit = parseFloat($("#transfer_exit").val());
        var check_input = parseFloat($("#check_input").val()),
            check_exit = parseFloat($("#check_exit").val());

        var total_01 = ($.isNumeric(cash_input) ? cash_input : 0) + ($.isNumeric(card_input) ? card_input : 0) +
            ($.isNumeric(transfer_input) ? transfer_input : 0) + ($.isNumeric(check_input) ? check_input : 0);

        var total_02 = ($.isNumeric(cash_exit) ? cash_exit : 0) + ($.isNumeric(card_exit) ? card_exit : 0) +
            ($.isNumeric(transfer_exit) ? transfer_exit : 0) + ($.isNumeric(check_exit) ? check_exit : 0);

        var total_03 = ($.isNumeric(total_01) ? total_01 : 0) - ($.isNumeric(total_02) ? total_02 : 0);
        $(".01_total").html(total_01.toFixed(2));
        $(".02_total").html(total_02.toFixed(2));
        $(".03_total").html(total_03.toFixed(2));

    }



    jQuery(document).on("change", "#date", function() { getinfo(); });
});