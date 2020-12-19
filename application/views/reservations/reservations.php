<link href="<?php echo base_url() ?>assets/scripts/datatables/css/jquery.dataTables.min.css" rel="stylesheet">


<div class="app-main__inner"> 
    <div class="card">
        <div class="card-body">
        <div class="row page-titles" style = "background: rgba(255, 255, 255, 0);margin-bottom:3rem">
                <div class="col-sm-9">
                    <h4>Listado de reservaciones</h4>
                </div>
                <?php if($permissions->C==1){ ?>
                    <div class="col-sm-3 "> 
                            <button onclick ="window.location = 'agregarreservaciones'" type="button" class="btn btn-primary btn-lg btn-block"><span class="btn-icon-right"><i class="fa fa-plus"> </i> Agregar nuevo reservaciones </span></button>          
                    </div>
                <?php } ?>
            </div>
            <div class="table-responsive">
                <table id="example" class="display" style="min-width: 845px">
                <thead>
                    <tr> 
                        <th class="text-center">RESERVADO POR </th>
                        <th class="text-center">FECHA</th>
                        <th class="text-center">DESDE - HASTA</th>
                        <th class="text-center">FECHA CREACION</th> 
                        <th class="text-center">ESTADO</th> 
                        <th class="text-center">ACCIONES</th>
                    </tr>
                </thead>
                <tbody> 
                    <?php
                    if(!empty($ReservationRecords))
                    {
                        foreach($ReservationRecords as $record)
                        {
                    ?>
                    <tr>  
                        <td class="text-center"style="vertical-align: middle;"><?php echo $record->apartment_code ?></td>
                        <td class="text-center" style="vertical-align: middle;"><?php echo $record->reservation_date ?></td>
                        <td class="text-center" style="vertical-align: middle;"><?php echo  $record->start_time . " - " . $record->ending_time ?></td>
                        <td class="text-center" style="vertical-align: middle;"><?php echo  $record->created_at  ?></td>
                        <td class="text-center" style="vertical-align: middle;"><?php 
                        if($record->status== RESERVE_APPROVED){
                            echo '<span class="badge badge-success">APROBADO</span>';
                        }else if($record->status== RESERVE_PENDING){
                            echo '<span class="badge badge-warning">PENDIENTE</span>';
                        }else if($record->status== RESERVE_DECLINED){
                            echo '<span class="badge badge-danger">DECLINADA</span>';
                        } ?></td>
                        <td class="text-center" style="vertical-align: middle;">
                             <?php if($permissions->U==1){ ?>
                                <?php if($rol!=ROLE_TENANT && $record->status == RESERVE_PENDING){ ?>
                                    <a class="btn btn-sm btn-success changeStatusReservation"  href="#" data-titulo="<?php echo $record->apartment_code; ?>" data-isactive="1" data-userid="<?php echo $record->id; ?>" title="APROBAR"><i class="fa fa-check"></i></a> 
                                <?php } ?> 
                                <?php if($rol!=ROLE_TENANT && $record->status > RESERVE_DECLINED){ ?>
                                    <a class="btn btn-sm btn-danger changeStatusReservation"  href="#" data-titulo="<?php echo $record->apartment_code; ?>" data-isactive="-1" data-userid="<?php echo $record->id; ?>" title="DECLINAR"><i class="fa fa-trash"></i></a>
                                <?php } ?>  
                            <?php } ?> 
                            <?php if(empty(!$record->observations)){ ?> <a class="btn btn-sm btn-info observationTag"  href="#" data-name="<?php echo $record->apartment_code; ?>" data-observations="<?php echo $record->observations; ?>" data-userid="<?php echo $record->id; ?>" title="OBSERVACION"><i class="fa fa-info"></i></a>   <?php } ?> 
                       </td>
                    </tr>
                    <?php
                        }
                    }
                    ?>
                </tbody>
                <tfoot>
                    <tr> 
                        <th class="text-center">RESERVADO POR </th>
                        <th class="text-center">FECHA</th>
                        <th class="text-center">DESDE - HASTA</th>
                        <th class="text-center">FECHA CREACION</th> 
                        <th class="text-center">ESTADO</th> 
                        <th class="text-center">ACCIONES</th>
                    </tr>
                </tfoot>
            </table>
            </div>
        </div>
    </div>
    
</div>    
<script src="<?php echo base_url() ?>assets/scripts/bootstrap4-notify/bootstrap-notify.min.js"></script>
<script src="<?php echo base_url() ?>assets/scripts/bootstrap-notify-init.js"></script>
<script src="<?php echo base_url() ?>assets/scripts/datatables/js/jquery.dataTables.min.js"></script>
<script src="<?php echo base_url() ?>assets/scripts/datatables.init.js"></script>  


