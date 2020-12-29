<link href="<?php echo base_url() ?>assets/scripts/datatables/css/jquery.dataTables.min.css" rel="stylesheet"> 
<div class="app-main__inner"> 
    <div class="card">
        <div class="card-body">
        <div class="row page-titles" style = "background: rgba(255, 255, 255, 0);margin-bottom:3rem">
                <div class="col-sm-9">
                    <h4>Listado de visitantes</h4>
                </div> 
            </div>
            <div class="table-responsive">
                <table id="example" class="display" style="min-width: 845px">
                <thead>
                    <tr> 
                        <th class="text-center">DOCUMENTO</th> 
                        <th class="text-center">NOMBRE</th> 
                        <th class="text-center">TELEFONO</th>  
                        <th class="text-center">TIPO</th>
                        <th class="text-center">FECHA DE CREACION</th> 
                        <th class="text-center">ESTADO</th> 
                        <th class="text-center">ACCIONES</th>
                    </tr>
                </thead>
                <tbody> 
                    <?php if(!empty($visitorRecords)) { foreach($visitorRecords as $record) { ?>
                    <tr>  
                        <td class="text-center"style="vertical-align: middle;"><?php echo $record->document   ?></td>
                        <td class="text-center"style="vertical-align: middle;"><?php echo  $record->name  ?></td>
                        <td class="text-center" style="vertical-align: middle;"><?php echo $record->mobile ?></td>
                        <td class="text-center" style="vertical-align: middle;"><?php echo   checkVisitor($record->type) ?></td> 
                        <td class="text-center" style="vertical-align: middle;"><?php echo  date_format (new DateTime($record->created_at), 'Y-m-d g:i A') ?></td> 
                        <td class="text-center" style="vertical-align: middle;">
                            <?php  if($record->status==STATUS_ACTIVE){ echo '<span class="badge badge-success">ACTIVO</span>'; }else if($record->status==STATUS_INACTIVE){  echo '<span class="badge badge-danger">SUSPENDIDO</span>';  } ?>
                        </td> 
                        <td class="text-center" style="vertical-align: middle;">
                            <?php if($permissions->U==1){ ?><a class="btn btn-sm btn-primary changeStatusVisitor"  href="#" data-titulo="<?php echo  "(".$record->document.") ".$record->name; ?>" data-isactive="<?php echo $record->status; ?>" data-userid="<?php echo $record->id; ?>" title="Cambio de estado"><i class="fa fa-eye"></i></a>  <?php } ?>
                            <?php if(empty(!$record->observation)){ ?>
                                 <a class="btn btn-sm btn-info observationVisitor"  href="#" data-name="<?php echo  "(".$record->document.") ".$record->name; ?>" data-observations="<?php echo $record->observation; ?>" data-userid="<?php echo $record->id; ?>" title="OBSERVACION"><i class="fa fa-info"></i></a>  
                            <?php } ?>
                            <a  class="btn btn-sm btn-success  "  href="<?php echo base_url()."historial_visitante/".$record->document ?>" title="Ver historial"> <i class="fa fa-history"></i></a>
                        </td>
                    </tr>
                    <?php } } ?>
                </tbody>
                <tfoot>
                    <tr> 
                        <th class="text-center">DOCUMENTO</th> 
                        <th class="text-center">NOMBRE</th> 
                        <th class="text-center">TELEFONO</th>  
                        <th class="text-center">TIPO</th>
                        <th class="text-center">FECHA DE CREACION</th> 
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


