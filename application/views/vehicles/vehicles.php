<link href="<?php echo base_url() ?>assets/scripts/datatables/css/jquery.dataTables.min.css" rel="stylesheet"> 
<div class="app-main__inner"> 
    <div class="card">
        <div class="card-body">
        <div class="row page-titles" style = "background: rgba(255, 255, 255, 0);margin-bottom:3rem">
                <div class="col-sm-9">
                    <h4>Listado de vehiculos</h4>
                </div>
                <?php if($permissions->C==1){ ?>
                    <div class="col-sm-3 "> 
                            <button onclick ="window.location = 'agregarvehiculos'" type="button" class="btn btn-primary btn-lg btn-block"><span class="btn-icon-right"><i class="fa fa-plus"> </i> Agregar nuevo vehiculo </span></button> 
                    </div>
                <?php } ?>
            </div>
            <div class="table-responsive">
                <table id="example" class="display" style="min-width: 845px">
                <thead>
                    <tr>   
                        <?php if($rol<=ROLE_VIGILANT){ ?><th class="text-center">PROPIETARIO</th> <?php } ?>
                        <th class="text-center">PLACA</th>  
                        <th class="text-center">MARCA</th> 
                        <th class="text-center">MODELO</th>
                        <th class="text-center">COLOR</th>  
                        <th class="text-center">AÑO</th>  
                        <th class="text-center">ESTADO</th> 
                        <th class="text-center">ACCIONES</th>
                    </tr>
                </thead>
                <tbody> 
                    <?php if(!empty($vehiclesRecords)) { foreach($vehiclesRecords as $record) { ?>
                    <tr>  
                         <?php if($rol<=ROLE_VIGILANT){ ?><td class="text-center"style="vertical-align: middle;"><?php echo "(".$record->document.") ".$record->name  ?></td> <?php } ?>
                        <td class="text-center" style="vertical-align: middle;"><?php echo $record->licence_plate ?></td> 
                        <td class="text-center" style="vertical-align: middle;"><?php echo  $record->brand ?></td> 
                        <td class="text-center" style="vertical-align: middle;"><?php echo   $record->model ?></td> 
                        <td class="text-center" style="vertical-align: middle;"><?php echo  $record->colour ?></td> 
                        <td class="text-center" style="vertical-align: middle;"><?php echo  $record->year ?></td>   
                        <td class="text-center" style="vertical-align: middle;">
                            <?php  if($record->status==STATUS_ACTIVE){ echo '<span class="badge badge-success">ACTIVO</span>'; }else if($record->status==STATUS_INACTIVE){  echo '<span class="badge badge-danger">INACTIVO</span>';  } ?>
                        </td> 
                        <td class="text-center" style="vertical-align: middle;">
                            <?php if($permissions->U==1){ ?><a class="btn btn-sm btn-primary changeStatusVehicles"  href="#" data-titulo="<?php echo  "(".$record->document.") ".$record->name; ?>" data-isactive="<?php echo $record->status; ?>" data-userid="<?php echo $record->id; ?>" title="Cambio de estado"><i class="fa fa-eye"></i></a>  <?php } ?>
                            <?php if(empty(!$record->condition_car)){ ?>
                                 <a class="btn btn-sm btn-info observationVehicles"  href="#" data-name="<?php echo  $record->licence_plate ?>" data-observation="<?php echo $record->condition_car; ?>" data-userid="<?php echo $record->id; ?>" title="CONDICION DEL VEHICULO"><i class="fa fa-info"></i></a>  
                            <?php } ?>
                            <?php  if($record->status==STATUS_ACTIVE){ ?>
                            <a class="btn btn-sm btn-info" href="<?php echo base_url().'editarvehiculos/'.$record->id; ?>" title="Editar"><i class="fa fa-paint-brush"></i></a> <?php } ?>
                        </td>
                    </tr>
                    <?php } } ?>
                </tbody>
                <tfoot>
                    <tr> 
                        <?php if($rol<=ROLE_VIGILANT){ ?><th class="text-center">PROPIETARIO</th> <?php } ?>
                        <th class="text-center">PLACA</th>  
                        <th class="text-center">MARCA</th> 
                        <th class="text-center">MODELO</th>
                        <th class="text-center">COLOR</th>  
                        <th class="text-center">AÑO</th>  
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


