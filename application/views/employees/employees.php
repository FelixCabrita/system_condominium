<link href="<?php echo base_url() ?>assets/scripts/datatables/css/jquery.dataTables.min.css" rel="stylesheet"> 
<div class="app-main__inner"> 
    <div class="card">
        <div class="card-body">
        <div class="row page-titles" style = "background: rgba(255, 255, 255, 0);margin-bottom:3rem">
                <div class="col-sm-9">
                    <h4>Listado de empleados</h4>
                </div>
                <?php if($permissions->C==1){ ?>
                    <div class="col-sm-3 "> 
                            <button onclick ="window.location = 'agregarempleados'" type="button" class="btn btn-primary btn-lg btn-block"><span class="btn-icon-right"><i class="fa fa-plus"> </i> Agregar nuevo empleado </span></button> 
                    </div>
                <?php } ?>
            </div>
            <div class="table-responsive">
                <table id="example" class="display" style="min-width: 845px">
                <thead>
                    <tr> 
                        <th class="text-center">NOMBRE</th> 
                        <th class="text-center">CARGO</th>  
                        <th class="text-center">EDIF. - APTO.</th>
                        <th class="text-center">INICIO DE TRABAJO</th> 
                        <th class="text-center">ESTADO</th> 
                        <th class="text-center">ACCIONES</th>
                    </tr>
                </thead>
                <tbody> 
                    <?php if(!empty($employeesRecords)) { foreach($employeesRecords as $record) { ?>
                    <tr>  
                        <td class="text-center"style="vertical-align: middle;"><?php echo "(".$record->document.") ".$record->name  ?></td>
                        <td class="text-center" style="vertical-align: middle;"><?php echo $record->work_type ?></td>
                        <td class="text-center" style="vertical-align: middle;"><?php echo   $record->code . ' - '. $record->apartment ?></td> 
                        <td class="text-center" style="vertical-align: middle;"><?php echo  $record->started_work ?></td> 
                        <td class="text-center" style="vertical-align: middle;">
                            <?php  if($record->status==STATUS_ACTIVE){ echo '<span class="badge badge-success">ACTIVO</span>'; }else if($record->status==STATUS_INACTIVE){  echo '<span class="badge badge-danger">INACTIVO</span>';  } ?>
                        </td> 
                        <td class="text-center" style="vertical-align: middle;">
                            <?php if($permissions->U==1){ ?><a class="btn btn-sm btn-primary changeStatusEmployees"  href="#" data-titulo="<?php echo  "(".$record->document.") ".$record->name; ?>" data-isactive="<?php echo $record->status; ?>" data-userid="<?php echo $record->id; ?>" title="Cambio de estado"><i class="fa fa-eye"></i></a>  <?php } ?>
                            <?php if(empty(!$record->observation)){ ?>
                                 <a class="btn btn-sm btn-info observationEmployees"  href="#" data-name="<?php echo  "(".$record->document.") ".$record->name; ?>" data-observations="<?php echo $record->observation; ?>" data-userid="<?php echo $record->id; ?>" title="OBSERVACION"><i class="fa fa-info"></i></a>  
                            <?php } ?>
                            <?php  if($record->status==STATUS_ACTIVE){ ?>
                            <a class="btn btn-sm btn-info" href="<?php echo base_url().'editarempleados/'.$record->id; ?>" title="Editar"><i class="fa fa-paint-brush"></i></a> <?php } ?>
                        </td>
                    </tr>
                    <?php } } ?>
                </tbody>
                <tfoot>
                    <tr> 
                        <th class="text-center">NOMBRE</th> 
                        <th class="text-center">CARGO</th>  
                        <th class="text-center">EDIF. - APTO.</th>
                        <th class="text-center">INICIO DE TRABAJO</th>
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


