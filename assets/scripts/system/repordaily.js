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

    var table = $('#reportdaily').DataTable({
        "ordering": false,
        "searching": false,
        "lengthMenu": [
            [-1],
            ["All"]
        ],
        columnDefs: [{
                targets: [0, 1, 2, 3, 4, 5],
                className: 'dt-body-center'
            },
            {
                "targets": [0],
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

    jQuery(document).on("click", ".deleteBillsPayment", function() {
        var currentRow = $(this).parents('tr');
        $('#reportdaily').DataTable().rows(currentRow).remove().draw();
        calculate();
    });
    calculate();

    function calculate() {
        var data = table.rows().data().toArray();
        var debit = 0;
        var credit = 0;
        $.each(data, function(key, value) {
            debit = parseFloat(debit) + parseFloat(value[2]);
            credit = parseFloat(credit) + parseFloat(value[3]);
        });
        var balance = debit - credit;
        $("#debit_total").val((debit).toFixed(2));
        $("#credit_total").val((credit).toFixed(2));
        $("#balance").val((balance).toFixed(2));
    }
    getDocumentType();
    $("#module").change(function() {
        getDocumentType();
    });


    jQuery(document).on("click", ".add", function() {
        add();
    });


    function add() {
        var account = $("#account").val(),
            namme_account = $("#account").find(':selected').data('name'),
            credit = parseFloat($("#credit").val()),
            debit = parseFloat($("#debit").val()),
            note = ($("#note").val()) == '' ? "NINGUNA" : $("#note").val();

        credit = credit >= 0 ? credit : 0;
        debit = debit >= 0 ? debit : 0;
        if (credit == 0 && debit == 0) {

        } else {
            table.row.add([
                account,
                namme_account,
                debit,
                credit,
                note,
                '<a class="btn btn-sm btn-danger deleteBillsPayment" href="#" title="Eliminar"><i class="fa fa-trash"></i></a>'
            ]).draw(false);
            calculate();
            $("#credit").val("0");
            $("#debit").val("0");
        }

    }

    function getDocumentType() {

        var stateID = $("#module").val();
        $.ajax({
            url: baseURL + 'obtener_tipo_documento/' + stateID,
            type: "GET",
            dataType: "json",
            success: function(data) {


                $('select[name="documentType"]').empty();
                $.each(data, function(key, value) {
                    $('select[name="documentType"]').append('<option value="' + value.id + '" ' + (documenttype_ == value.id ? "SELECTED" : "") + ' >' + value.code + " - " + value.name + '</option>');
                });
            }
        });
    }

    jQuery(document).on("click", ".pay", function() {
        var balance = parseFloat($("#balance").val()),
            debit = parseFloat($("#debit_total").val()),
            credit = parseFloat($("#credit_total").val()),
            date = ($("#date").val()),
            module = parseFloat($("#module").val()),
            documentType = parseFloat($("#documentType").val()),
            pay = true;

        if (balance != 0 || debit == 0 || credit == 0 | date == '') {
            pay = false;
        }

        if (pay) {

            var data = table.rows().data().toArray();
            var json = JSON.stringify(data);


            var hitURL = baseURL + "guarda_entrada_diario";

            const data11 = new FormData();
            data11.append('module', module);
            data11.append('documentType', documentType);
            data11.append('json', json);
            data11.append('date', date);
            data11.append('id_', id_);

            Swal.queue([{
                title: id_ == '' ? '¿Desea registrar esta entrada del diario ?' : 'Desea actualizar esta entrada de diario?',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'SI!',
                cancelButtonText: 'No, cancelar!',
                input: 'text',
                inputValue: description,
                inputPlaceholder: 'Coloque una observacion',
                showLoaderOnConfirm: true,
                inputValidator: (value) => {
                    if (!value) {
                        return 'Debes colocar una observacion!'
                    }
                },
                preConfirm: () => {
                    data11.append('observation', $('.swal2-input').val());

                    return fetch(hitURL, {
                            method: 'POST',
                            body: data11
                        })
                        .then(response => response.json())
                        .then(data => (data.status ?
                            Swal.fire({
                                position: 'top-end',
                                icon: 'success',
                                title: id_ == '' ? 'Entrada de diario registrada con exito!' : 'Entrada de diario actualizada con exito!',
                                showConfirmButton: false,
                                timer: 2000
                            }).then((result) => {
                                /* Read more about handling dismissals below */
                                if (result.dismiss === Swal.DismissReason.timer) {
                                    window.location = baseURL + (id_ == '' ? "entrada_de_diario" : "editar_entrada_diario/" + id_)
                                }
                            }) :
                            Swal.fire({
                                position: 'top-end',
                                icon: 'error',
                                title: "No es posible procesar esta operacion en estos momentos",
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
                title: "Los valores no coinciden",
                showConfirmButton: false,
                timer: 2000
            });
        }

    });

});