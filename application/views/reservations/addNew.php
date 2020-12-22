<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/scripts/calendario/css/pignose.calendar.min.css" />
<script> var reservations =  JSON.parse('<?php echo ($reservations); ?>'); 
        console.log(  reservations); </script>   
<div class="app-main__inner">
    <div class="card form-card">
        <div class="card-body">
            <h4 class="card-title mb-4"> <i class="fa fa-users"></i> RESERVANDO AREA <br> </h4>
            <?php
                $this->load->helper('form');
                $error = $this->session->flashdata('error');
                if ($error) {?>
                    <div class="alert alert-danger alert-dismissible fade show">
                        <button type="button" class="close h-100" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span> </button>
                        <?php echo $error; ?>
                    </div>
                <?php }
                    $success = $this->session->flashdata('success');
                    if ($success) {?>
                    <div class="alert alert-success alert-dismissible fade show">
                        <button type="button" class="close h-100" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span> </button>
                        <?php echo $success; ?>
                    </div>
            <?php }?>
          
            <div class="row">
                <div class="form-group col-sm-6">
                    <div class="calendar-schedules"></div>
                    <?php echo validation_error('name'); ?>
                </div>
                <div class="form-group col-sm-6">
                        <label for="name">Reservado por: </label>
                    <div class="row form-group ">
                        <?php if($rol <= ROLE_VIGILANT) { ?>
                            <div class="form-group col-sm-6">
                                <label for="type">TIPO USUARIO</label>
                                <input type="text"  class="form-control mb-2 mr-sm-5"  name="type" value="<?php echo checkRole($rol) ?>"  readonly>  
                            </div>  
                            <div class="form-group col-sm-6">
                                <label for="apartment">(EDIFICIO - APARTAMENTO)</label>
                                <select name="apartment"  id="apartment"  class="form-control">  
                                    <?php  if(!empty($apartmentRecords)) { foreach($apartmentRecords as $record) {  ?>
                                        <option value ="<?php echo $record->id ?>" ><?php echo $record->code ?></option>
                                    <?php }} ?> 
                                </select>
                            </div>  
                        <?php }else{ ?>
                            <div class="form-group col-sm-6">
                                <label for="type">TIPO USUARIO</label>
                                <input type="text"  class="form-control mb-2 mr-sm-5"  name="type" value="<?php echo checkRole($rol) ?>"  readonly>  
                            </div>  
                            <div class="form-group col-sm-6">
                                <label for="apartment">(EDIFICIO - APARTAMENTO)</label>
                                <select name="apartment"  id="apartment"  class="form-control">  
                                    <?php  if(!empty($apartmentRecords)) { foreach($apartmentRecords as $record) {  ?>
                                        <option value ="<?php echo $record->apartment ?>" ><?php echo $record->edifice_code." - ".$record->apartment_code ?></option>
                                    <?php }} ?> 
                                </select>
                            </div>  
                        <?php } ?>
                    </div>
                    <div class="form-group ">
                        <label for="commonarea">Area comun a reservar</label>
                        <select name="commonarea" id="commonarea" class="form-control">
                            <option value ="">SELECCIONE UN AREA COMUN</option>
                            <?php if (!empty($commonareaRecords)) {foreach ($commonareaRecords as $record) {?>
                                <option value ="<?php echo $record->id ?>" data-max="<?php echo $record->max_person ?>" ><?php echo $record->name . " (" . $record->max_person . ") " ?></option>
                            <?php }}?>
                        </select>
                        <?php echo validation_error('commonarea'); ?>
                    </div>
                    <div class="form-group ">
                        <label for="quantity_persons">Cantidad de personas <spam style="font-size:12px" id="max"><spam></label>
                        <input type="number"  class="form-control mb-2 mr-sm-5"  id="quantity_persons"  name="quantity_persons" min="1" value="1">
                        <?php echo validation_error('quantity_persons'); ?>
                    </div>
                    <div class="row form-group ">
                        <div class="form-group col-sm-6">
                            <label for="start_time">Hora de inicio</label>
                            <input type="time"  class="form-control mb-2 mr-sm-5" id="start_time"  name="start_time" value="<?php echo set_value('start_time'); ?>">
                            <?php echo validation_error('block'); ?>
                        </div>
                        <div class="form-group col-sm-6">
                            <label for="ending_time">Hora de finalizacion</label>
                            <input type="time"  class="form-control mb-2 mr-sm-5" id="ending_time"  name="ending_time" value="<?php echo set_value('ending_time'); ?>" >
                            <?php echo validation_error('end'); ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group text-right">
                <a id="send" href="#" class="btn btn-primary btn-form">AGREGAR</a>
            </div> 
        </div>
    </div>
</div>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/scripts/calendario/js/pignose.calendar.full.min.js"></script>
<script type="text/javascript">
        var date_selected="";
        $(function() {
            $('#wrapper .version strong').text('v' + $.fn.pignoseCalendar.version);

            function onSelectHandler(date, context) {
                /**
                 * @date is an array which be included dates(clicked date at first index)
                 * @context is an object which stored calendar interal data.
                 * @context.calendar is a root element reference.
                 * @context.calendar is a calendar element reference.
                 * @context.storage.activeDates is all toggled data, If you use toggle type calendar.
                 * @context.storage.events is all events associated to this date
                 */

                var $element = context.element;
                var $calendar = context.calendar;
                var $box = $element.siblings('.box').show();
                var text = 'You selected date ';

                if (date[0] !== null) {
                    text += date[0].format('YYYY-MM-DD');
                }

                if (date[0] !== null && date[1] !== null) {
                    text += ' ~ ';
                } else if (date[0] === null && date[1] == null) {
                    text += 'nothing';
                }

                if (date[1] !== null) {
                    text += date[1].format('YYYY-MM-DD');
                }

                $box.text(text);
            }

            function onApplyHandler(date, context) {
                /**
                 * @date is an array which be included dates(clicked date at first index)
                 * @context is an object which stored calendar interal data.
                 * @context.calendar is a root element reference.
                 * @context.calendar is a calendar element reference.
                 * @context.storage.activeDates is all toggled data, If you use toggle type calendar.
                 * @context.storage.events is all events associated to this date
                 */

                var $element = context.element;
                var $calendar = context.calendar;
                var $box = $element.siblings('.box').show();
                var text = 'You applied date ';

                if (date[0] !== null) {
                    text += date[0].format('YYYY-MM-DD');
                }

                if (date[0] !== null && date[1] !== null) {
                    text += ' ~ ';
                } else if (date[0] === null && date[1] == null) {
                    text += 'nothing';
                }

                if (date[1] !== null) {
                    text += date[1].format('YYYY-MM-DD');
                }

                $box.text(text);
            }


            // Schedule Calendar
            $('.calendar-schedules').pignoseCalendar({
                scheduleOptions: {
                    colors: {
                        select: '#2fabb7', 
                    }
                },
                schedules: reservations,
                select: function(date, context) {
                    if(date[0] !== null){
                        date_selected =date[0].format('YYYY-MM-DD') ; 
                         var message = ""; 
                        for (var idx in context.storage.schedules) {
                            var schedule = context.storage.schedules[idx];
                            if (typeof schedule !== 'object') {
                                continue;
                            }
                            message =message+ (message!==''?"<br><hr>" :"<hr>")+  "Apart.:"  +schedule.code 
                             +" | Desde: " + schedule.start_time  
                             +" hasta las: " + schedule.ending_time +" | " + schedule.commonarea ;
                        }
                        if(message!==''){
                            Swal.fire({
                                position: 'top-end',
                                icon: 'info',
                                title: "Fecha: "+date[0].format('YYYY-MM-DD'),
                                showConfirmButton: false,
                                html:message 
                            });
                        }
                        
                    }  else{
                        date_selected = '';
                    }
                }
            });


        });

        $("#edifice").change(function() {
            var stateID = $(this).val();
            if (stateID) {
                $.ajax({
                    url: 'obtener_apartemantos/' + stateID,
                    type: "GET",
                    dataType: "json",
                    success: function(data) {


                        $('select[name="apartment"]').empty();
                        $('select[name="apartment"]').append('<option value="">SELECCIONE UN APARTAMENTO</option>');
                        $.each(data, function(key, value) {
                            $('select[name="apartment"]').append('<option value="' + value.id + '">' + value.apartment + '</option>');
                        });
                    }
                });
            } else {
                $('select[name="apartment"]').empty();
                $('select[name="apartment"]').append('<option value="">SELECCIONE UN EDIFICIO</option>');
            }
        });
        jQuery(document).ready(function() {
                jQuery(document).on("change", "#commonarea", function() {
                    var stateID = $(this).val();
                    var max = $(this).find(':selected').data("max"); 
                    $("#max").html("(Capacidad maxima segun area: <b>"+max+"</b>)")
                    $("#quantity_persons").attr({
                        "max" : max,
                        "min" : 1
                    });
                });

                jQuery(document).on("click", "#send", function() {
                    var edifice = $("#edifice").val(),
                        apartment = $("#apartment").val(),
                        commonarea = $("#commonarea").val(),
                        quantity_persons = $("#quantity_persons").val(),
                        start_time = $("#start_time").val(),
                        ending_time = $("#ending_time").val(), 
                        hitURL = baseURL + "reservar_areacomun"; 
                        if(apartment !=='' && commonarea!==''&& quantity_persons!==''&& start_time!==''&& ending_time!=='' && date_selected !==''){
                            const data = new FormData();
                            data.append('edifice', edifice);
                            data.append('apartment', apartment);
                            data.append('commonarea', commonarea);
                            data.append('quantity_persons', quantity_persons);
                            data.append('start_time', start_time);
                            data.append('ending_time', ending_time); 
                            data.append('reservation_date', date_selected); 
                            Swal.queue([{
                                title: "¿Desea reservar esta area comun?",
                                showCancelButton: true,
                                confirmButtonColor: '#3085d6',
                                cancelButtonColor: '#d33',
                                confirmButtonText: 'SI!',
                                cancelButtonText: 'No, cancelar!',
                                text: 'Se procedera a evaluar sus datos, si todo esta bien se realizara la peticion de reserva.',
                                showLoaderOnConfirm: true,
                                preConfirm: () => {
                                    return fetch(hitURL, {
                                            method: 'POST',
                                            body: data
                                        })
                                        .then(response => response.json())
                                        .then(data => (data.status ?
                                            Swal.fire({
                                                position: 'top-end',
                                                icon: 'success',
                                                title: "Se ha enviado la peticion de reserva exitosamente.",
                                                showConfirmButton: false,
                                                timer: 2000
                                            }).then((result) => {
                                                /* Read more about handling dismissals below */
                                                if (result.dismiss === Swal.DismissReason.timer) {
                                                    window.location = baseURL + "agregarreservaciones"
                                                }
                                            }) :
                                            Swal.fire({
                                                position: 'top-end',
                                                icon: 'error',
                                                title: data.error!==''?data.error:"Por alguna razon no hemos podido reservar esta area comun, por favor comuniquese con su administrador del condominio",
                                                showConfirmButton: false,
                                                timer: 3000
                                            })))
                                        .catch(() => {
                                            Swal.insertQueueStep({
                                                icon: 'error',
                                                title: 'acceso denegado..!'
                                            })
                                        })
                                }
                            }]);
                        }else{
                            Swal.fire({
                                position: 'top-end',
                                icon: 'error',
                                title: "Debe llenar todos los campos y seleccionar una fecha correcta!",
                                showConfirmButton: false,
                                timer: 2000
                            });
                        }  
                });
        });
</script>