/**
 * File : addUser.js
 * 
 * This file contain the validation of add user form
 * 
 * Using validation plugin : jquery.validate.js
 * 
 * @author Felix Cabrita
 */


jQuery(document).ready(function() {
    search_document();


    var table = $('#payment').DataTable({
        "ordering": false,
        "searching": false,
        "lengthMenu": [
            [-1],
            ["All"]
        ],
        columnDefs: [{
                targets: [0, 1, 2, 3, 4, 5, 6, 7],
                className: 'dt-body-center'
            },
            {
                "targets": [0, 5, 6],
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

    function search_document() {
        var document = $("#client").val(),
            hitURL = baseURL + "busqueda_deudas";

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

                        $.each(data.reportpay, function(key, value) {
                            total = parseFloat(total) + parseFloat(value.amount);
                            table.row.add([
                                value.id,
                                value.description,
                                parseFloat(value.amount),
                                value.code,
                                value.emision, -1,
                                value.type,
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
                            title: "No hemos encontrado alguna coincidencia con el documento: " + document,
                            showConfirmButton: false,
                            timer: 2000
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
            transfer_code = $("#transfer_code").val(),
            transfer_date = $("#transfer_date").val(),
            payment_type_id = $("#payment_type_id").val(),
            pay = true;
        console.log(transfer_date);
        if (payment_type_id == 1 && (transfer_date == '' || transfer_code == '')) {
            pay = false;
        }

        if (pay) {
            if (amount > 0) {
                var files = $('#transfer_photo')[0].files;
                var data = table.rows().data().toArray();
                var json = JSON.stringify(data);
                var total = 0,
                    ids = "";
                $.each(data, function(key, value) {
                    total = parseFloat(total) + parseFloat(value[2]);

                });
                if (amount >= total) {
                    var userId = $("#client").val(),
                        hitURL = baseURL + "reportar_facturas";
                    var residue = parseFloat(amount) - parseFloat(total);
                    const data11 = new FormData();
                    data11.append('id', userId);
                    data11.append('residue', residue);
                    data11.append('amount', amount);
                    data11.append('photo', files[0]);
                    data11.append('json', json);
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
                        html: "Total a pagar: <b>" + amount.toFixed(2) + "</b><br>Usted Pago:<b> " + amount.toFixed(2) + "</b><br>Su cambio:<b>" + residue.toFixed(2) + "</b><br>No podra editar este proceso y tendra que borrarlo y rehacerlo de nuevo si comete un error!",
                        showLoaderOnConfirm: true,
                        preConfirm: () => {
                            return fetch(hitURL, {
                                    method: 'POST',
                                    body: data11
                                })
                                .then(response => response.json())
                                .then(data => (data.status ?
                                    Swal.fire({
                                        position: 'top-end',
                                        icon: 'success',
                                        title: 'Pago realizado con exito!',
                                        showConfirmButton: false,
                                        timer: 2000
                                    }).then((result) => {
                                        /* Read more about handling dismissals below */
                                        if (result.dismiss === Swal.DismissReason.timer) {
                                            window.location = baseURL + "reportesdepago"
                                        }
                                    }) :
                                    Swal.fire({
                                        position: 'top-end',
                                        icon: 'error',
                                        title: "No es posible procesar el pago en estos momentos",
                                        showConfirmButton: false,
                                        timer: 2000
                                    })))
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