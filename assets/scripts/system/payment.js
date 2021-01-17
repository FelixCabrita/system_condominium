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
    $("#document").focus();

});

jQuery(document).ready(function() {

    $("#document").focus();
    jQuery(document).on("click", "#search", function() {
        search_document();
    });


    var table = $('#payment').DataTable({
        "ordering": false,
        "searching": false,
        "lengthMenu": [
            [-1],
            ["All"]
        ],
        columnDefs: [{
                targets: [0, 1, 2, 3, 4, 5, 6],
                className: 'dt-body-center'
            },
            {
                "targets": [0, 5],
                "visible": false
            }
        ]
    });
    table.on('click', 'tbody tr', function() {
        var $row = table.row(this).nodes().to$();
        var hasClass = $row.hasClass('selected');
        if (hasClass) {
            $row.removeClass('selected')
        } else {
            $row.addClass('selected')
        }
    })

    table.rows().every(function() {
        this.nodes().to$().removeClass('selected')
    });
    $("#document").keypress(function(event) {
        if (event.which === 13 || event.which === 9)
            search_document();
    });

    $("body").keypress(function(event) {
        console.log(event.which)

    });

    function search_document() {
        var document = $("#document").val(),
            hitURL = baseURL + "busqueda_cliente";

        const data = new FormData();
        data.append('document', document);
        if (document !== '') {
            fetch(hitURL, {
                    method: 'POST',
                    body: data
                })
                .then(response => response.json())
                .then(data => {
                    if (data.status) {
                        var tenant = data.tenant;
                        $("#client").val(tenant.id);
                        $("#name").val(tenant.name);
                        $("#type").val(tenant.type == 6 ? "PROPIETARIO APTO." : "INQUILINO APTO.");
                        var total = 0;
                        $('#payment').DataTable().rows().remove().draw();

                        $.each(data.bills, function(key, value) {
                            total = parseFloat(total) + parseFloat(value.amount);
                            table.row.add([
                                value.id,
                                value.description,
                                parseFloat(value.amount),
                                value.code,
                                value.emision, -1,
                                '<a class="btn btn-sm btn-danger deleteBillsPayment" href="#" title="Eliminar"><i class="fa fa-trash"></i></a>'
                            ]).draw(false);
                        });
                        $("#total").html((total).toFixed(2));
                        $("#amount").val((total).toFixed(2));


                        var ApartmentsAssignation = data.ApartmentsAssignation;

                        $('select[name="apartment"]').empty();
                        $.each(ApartmentsAssignation, function(key, value) {
                            $('select[name="apartment"]').append('<option value="' + value.apartment + '" data-code="' + value.edifice_code + "" + value.apartment_code + '">' + value.edifice_code + " - " + value.apartment_code + '</option>');
                        });
                    } else {
                        Swal.fire({
                            position: 'top-end',
                            icon: 'error',
                            title: data.erro == '' ? "No hemos encontrado alguna coincidencia con el documento: " + document : data.error,
                            showConfirmButton: false,
                            timer: 7000
                        });
                    }
                })
                .catch(() => {
                    Swal.insertQueueStep({
                        icon: 'error',
                        title: 'acceso denegado..!'
                    })
                })
        }
    }
    jQuery(document).on("click", ".deleteBillsPayment", function() {
        var currentRow = $(this).parents('tr');
        $('#payment').DataTable().rows(currentRow).remove().draw();
        calculate();
    });

    jQuery(document).on("click", ".payment_bills", function() {
        var type = $(this).data("payment");
        $("#payment_type_id").val(type);
        $("#payment_type_text").html(getTextPaymentType(type));
        if (type == 2) {
            console.log("type == 2");
            $("#transfer_code").val("");
            $("#transfer_date").val("");

            $(".payment_transfer").css("display", "none");
            $(".payment_check").css("display", "block");
        }
        if (type == 1) {
            console.log("type == 1");
            $("#check_number").val("");
            $("#check_transmitter").val("");

            $(".payment_check").css("display", "none");
            $(".payment_transfer").css("display", "block");
        }

        if (type == 0 || type == 3) {
            $("#transfer_code").val("");
            $("#transfer_date").val("");
            $("#check_number").val("");
            $("#check_transmitter").val("");
            $(".payment_transfer").css("display", "none");
            $(".payment_check").css("display", "none");
        }
    });

    function getTextPaymentType(type) {
        switch (type) {
            case 0:
                return "EFECTIVO";
                break;
            case 1:
                return "TRANFERENCIA";
                break;
            case 2:
                return "CHEQUE";
                break;
            case 3:
                return "TARJETA";
                break;
        }
    }
    jQuery(document).on("click", ".addservice", function() {

        var service = $("#service").val(),
            service_name = $("#service").find(':selected').data('name'),
            amount = parseFloat($("#service").find(':selected').data('amount')),
            apartment = $("#apartment").val(),
            apartment_code = $("#apartment").find(':selected').data('code'),
            payment_mode = $("#payment_mode").val();
        console.log(apartment)
        var d = new Date();
        var month = (d.getMonth() + 1) > 9 ? (d.getMonth() + 1) : "0" + (d.getMonth() + 1);
        var day = (d.getDate()) > 9 ? (d.getDate()) : "0" + (d.getDate());
        var strDate = d.getFullYear() + "-" + month + "-" + day;
        if (apartment !== null) {
            if (payment_mode === '0') {
                var insert = true;
                var data = table.rows().data().toArray();
                $.each(data, function(key, value) {
                    if (value[0] == service && value[3] == apartment_code && value[5] == 0) {
                        insert = false;
                    }
                });
                if (insert) {
                    table.row.add([service,
                        service_name,
                        amount,
                        apartment_code,
                        strDate,
                        payment_mode,
                        '<a class="btn btn-sm btn-danger deleteBillsPayment" href="#" title="Eliminar"><i class="fa fa-trash"></i></a>'
                    ]).draw(false);
                    calculate();
                }
            } else {
                var hitURL = baseURL + "calculando_abono";

                const data = new FormData();
                data.append('service', service);
                data.append('apartment', apartment);

                Swal.queue([{
                    title: "¿Desea abonar pagos a este servicio {" + service_name + "}'?",
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'SI!',
                    cancelButtonText: 'No, cancelar!',
                    input: 'number',
                    text: "Ingresa cantidad de meses a abonar",
                    inputPlaceholder: '0',
                    showLoaderOnConfirm: true,
                    inputValidator: (value) => {
                        if (!value || value < 1) {
                            return 'Debes colocar un numero mayor a 0!'
                        }
                    },
                    preConfirm: () => {
                        data.append('quantity', $('.swal2-input').val());

                        return fetch(hitURL, {
                                method: 'POST',
                                body: data
                            })
                            .then(response => response.json())
                            .then(data => {
                                if (data.status) {
                                    var bills_prox = data.bills_month;


                                    $.each(bills_prox, function(key, value) {
                                        var data = table.rows().data().toArray();
                                        var insert = true;
                                        $.each(data, function(key, v) {
                                            if (v[0] == service && v[3] == apartment_code && v[5] == value.payment_prox) {
                                                insert = false;
                                            }
                                        });
                                        if (insert) {
                                            table.row.add([
                                                service,
                                                value.description,
                                                parseFloat(value.amount),
                                                apartment_code,
                                                strDate,
                                                value.payment_prox,
                                                '<a class="btn btn-sm btn-danger deleteBillsPayment" href="#" title="Eliminar"><i class="fa fa-trash"></i></a>'
                                            ]).draw(false);
                                        }
                                    });
                                    calculate();

                                }
                            })

                    }
                }])
            }
        }

    });

    function calculate() {
        var data = table.rows().data().toArray();
        var total = 0;
        $.each(data, function(key, value) {
            total = parseFloat(total) + parseFloat(value[2]);
        });
        $("#total").html((total).toFixed(2));
        $("#amount").val((total).toFixed(2));
    }

    jQuery(document).on("click", ".pay", function() {
        var amount = parseFloat($("#amount").val()),
            check_number = $("#check_number").val(),
            check_transmitter = $("#check_transmitter").val(),
            transfer_code = $("#transfer_code").val(),
            transfer_date = $("#transfer_date").val(),
            payment_type_id = $("#payment_type_id").val(),
            pay = true;
        if (payment_type_id == 2 && (check_number === '' || check_transmitter === '')) {
            pay = false;
        }

        if (payment_type_id == 1 && (transfer_date == '' || transfer_code == '')) {
            pay = false;
        }

        if (pay) {
            if (amount > 0) {
                var data = table.rows().data().toArray();
                var json = JSON.stringify(data);
                var total = 0,
                    ids = "";
                console.log(json)
                $.each(data, function(key, value) {
                    total = parseFloat(total) + parseFloat(value[2]);
                    if (value[5] == -1)
                        ids = (ids === '' ? "" : ids + ",") + value[0];
                });

                if (amount >= total) {
                    var userId = $("#client").val(),
                        hitURL = baseURL + "pagar_facturas";
                    var residue = parseFloat(amount) - parseFloat(total);
                    const data11 = new FormData();
                    data11.append('id', userId);
                    data11.append('residue', residue);
                    data11.append('amount', amount);
                    data11.append('ids', ids);
                    data11.append('json', json);
                    data11.append('check_number', check_number);
                    data11.append('check_transmitter', check_transmitter);
                    data11.append('transfer_code', transfer_code);
                    data11.append('transfer_date', transfer_date);
                    data11.append('payment_type_id', payment_type_id);



                    Swal.queue([{
                        title: '¿Desea procesar el pago de estas facturas?',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'SI!',
                        cancelButtonText: 'No, cancelar!',
                        html: "Total a pagar: <b>" + amount.toFixed(2) + "</b><br>Usted Pago:<b> " + amount.toFixed(2) + "</b><br>Su cambio:<b>" + residue.toFixed(2) + "</b>",
                        showLoaderOnConfirm: true,
                        preConfirm: () => {
                            return fetch(hitURL, {
                                    method: 'POST',
                                    body: data11
                                })
                                .then(response => response.json())
                                .then(data => {
                                    if (data.status) {
                                        Swal.queue([{
                                            title: 'Factura procesada con exito! ¿Desea imprimir esta factura?',
                                            showCancelButton: true,
                                            confirmButtonColor: '#3085d6',
                                            cancelButtonColor: '#d33',
                                            confirmButtonText: 'SI!',
                                            cancelButtonText: 'No, cancelar!',
                                            showLoaderOnConfirm: true,
                                            preConfirm: () => {
                                                window.location = baseURL + "caja"
                                                window.open(baseURL + "reporte_factura/" + data.daily, '_blank');
                                            },

                                        }]).then((result) => {
                                            if (result.dismiss) {
                                                window.location = baseURL + "caja"
                                            }
                                        })
                                    } else {
                                        Swal.fire({
                                            position: 'top-end',
                                            icon: 'error',
                                            title: "No es posible procesar el pago en estos momentos",
                                            showConfirmButton: false,
                                            timer: 2000
                                        })
                                    }

                                })
                                .catch(() => {
                                    Swal.insertQueueStep({
                                        icon: 'error',
                                        title: 'acceso denegado..!'
                                    })
                                })
                        }
                    }])
                } else {
                    Swal.fire({
                        position: 'top-end',
                        icon: 'error',
                        title: "No debe pagar menos del monto total {" + total.toFixed(2) + "}",
                        showConfirmButton: false,
                        timer: 2000
                    });
                }

            } else {
                Swal.fire({
                    position: 'top-end',
                    icon: 'error',
                    title: "EL monto pagado debe ser mayor a 0",
                    showConfirmButton: false,
                    timer: 2000
                });
            }
        } else {
            Swal.fire({
                position: 'top-end',
                icon: 'error',
                title: "Debe rellenar los datos del tipo de pago especicificado",
                showConfirmButton: false,
                timer: 2000
            });
        }

    });


});