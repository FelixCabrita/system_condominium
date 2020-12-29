<link href="<?php echo base_url() ?>assets/scripts/datatables/css/jquery.dataTables.min.css" rel="stylesheet"> 

<div class="app-main__inner"> 
    <div class="card">
        <div class="card-body">
            <div class="row page-titles" style = "background: rgba(255, 255, 255, 0);margin-bottom:3rem">
                <div class="col-sm-9">
                    <h4>Listado de miembros de la junta directiva</h4>
                </div>
                <?php if($permissions->C==1){ ?>
                <div class="col-sm-3 "> 
                        <button onclick ="window.location = 'agregarmiembro'" type="button" class="btn btn-primary btn-lg btn-block"><span class="btn-icon-right"><i class="fa fa-plus"> </i> Agregar nuevo miembro </span></button> 
                </div>
                <?php } ?>
            </div>
            
            <ul class="body-tabs body-tabs-layout tabs-animated body-tabs-animated nav">
                <li class="nav-item">
                    <a role="tab" class="nav-link active" id="tab-1" data-toggle="tab" href="#tab-content-1">
                        <span>   <?php  if(!empty($periodRecords)) { foreach($periodRecords as $record) {   
                                   echo "SOLO DEL PERIODO ACTIVO {".$record->name."}"; 
                               }}else{
                                   echo "NO HAY PERIODOS ACTIVOS";
                               } ?> </span>
                    </a>
                </li>
                <li class="nav-item">
                    <a role="tab" class="nav-link" id="tab-2" data-toggle="tab" href="#tab-content-2">
                        <span>VER TODOS</span>
                    </a>
                </li>
            </ul>
            <div class="tab-content">
                <div class="tab-pane tabs-animation fade show active" id="tab-content-1" role="tabpanel">
                    <div class="row">
                        <?php if(!empty($DirectiveRecordsActive))  {  foreach($DirectiveRecordsActive as $record)  { ?>
                            <div class="col-md-6"> 
                                <div class="mb-3 text-center card card-body " <?php if($record->status==0){ ?> style="background-color: #E8E8E8"<?php } ?>> 
                                    <div class="row">
                                        <div class="col-md-4"> 
                                            <img  width="100%" style="max-with:60px" src = "<?php echo base_url()."assets/images/directive_photo/".$record->photo_1 ?>" >
                                        </div> 
                                        <div class="col-md-8 text-center" style="margin-top:20px"> 
                                            <h5 class="card-title text-center"><?php echo "($record->document) ".$record->name ?></h5>
                                            <p><b>Puesto:</b> <?php echo DIRECTIVE_CHARGUE[$record->position]?>  
                                             <b>Cargo:</b> <?php echo  $record->chargue ?> </p>
                                            <?php if($record->status==0){ ?> <p><b>MIEMBRO DESACTIVADO</b>  </p><?php } ?>
                                            <?php if($permissions->U==1){ ?><a class="btn btn-sm btn-primary changeStatusDirective"  href="#" data-titulo="<?php echo $record->name; ?>" data-isactive="<?php echo $record->status; ?>" data-userid="<?php echo $record->id; ?>" title="Cambio de estado"><i class="fa fa-eye"></i></a> 
                                            <a class="btn btn-sm btn-info" href="<?php echo base_url().'editarmiembro/'.$record->id; ?>" title="Editar"><i class="fa fa-paint-brush"></i></a> <?php }else { ?> <a class="btn btn-sm btn-info" href="<?php echo base_url().'editarmiembro/'.$record->id; ?>" title="VER INFORMACION"><i class="fa fa-info"></i></a>  <?php } ?>
                                            <?php if($permissions->D==1){ ?> <a class="btn btn-sm btn-danger deleteDirective" href="#"  data-carnet="1" data-userid="<?php echo $record->id; ?>" data-name="<?php echo $record->code ?>" title="Eliminar"><i class="fa fa-trash"></i></a>  <?php } ?>
                                        </div> 
                                    </div> 
                                </div> 
                            </div>
                        <?php } } ?> 
                    </div>
                </div>
                <div class="tab-pane tabs-animation fade" id="tab-content-2" role="tabpanel">
                    <div class="table-responsive">
                        <table id="directive" class="display" style="min-width: 845px">
                            <thead>
                                <tr> 
                                    <th class="text-center">DOCUMENTO </th>
                                    <th class="text-center">NOMBRE</th>
                                    <th class="text-center">APARTAMENTO</th>
                                    <th class="text-center">EDIFICIO</th>    
                                    <th class="text-center">CARGO</th>
                                    <th class="text-center">PERIODO</th>    
                                    <th class="text-center">ESTADO	</th> 
                                    <th class="text-center">ACCIONES</th>
                                </tr>
                            </thead>
                            <tbody> 
                            <?php if(!empty($DirectiveRecords))  {  foreach($DirectiveRecords as $record)  { ?>
                            <tr>  
                                <td class="text-center"style="vertical-align: middle;"><?php echo $record->document ?></td>
                                <td class="text-center" style="vertical-align: middle;"><?php echo $record->name ?></td>
                                <td class="text-center" style="vertical-align: middle;"><?php echo $record->apartment ?></td>
                                <td class="text-center" style="vertical-align: middle;"><?php echo  $record->code ?></td> 
                                <td class="text-center" style="vertical-align: middle;"><?php echo  $record->chargue  ?></td>
                                <td class="text-center" style="vertical-align: middle;"><?php echo  $record->period  ?></td>
                                <td class="text-center" style="vertical-align: middle;"><?php 
                                if($record->status==STATUS_ACTIVE){
                                    echo '<span class="badge badge-success">ACTIVO</span>';
                                }else if($record->status==STATUS_INACTIVE){
                                    echo '<span class="badge badge-danger">INACTIVO</span>';
                                } ?></td>
                                <td class="text-center" style="vertical-align: middle;">
                                    <?php if($permissions->U==1){ ?><a class="btn btn-sm btn-primary changeStatusDirective"  href="#" data-titulo="<?php echo $record->name; ?>" data-isactive="<?php echo $record->status; ?>" data-userid="<?php echo $record->id; ?>" title="Cambio de estado"><i class="fa fa-eye"></i></a> 
                                    <a class="btn btn-sm btn-info" href="<?php echo base_url().'editarmiembro/'.$record->id; ?>" title="Editar"><i class="fa fa-paint-brush"></i></a> <?php }else { ?> <a class="btn btn-sm btn-info" href="<?php echo base_url().'editarmiembro/'.$record->id; ?>" title="VER INFORMACION"><i class="fa fa-info"></i></a>  <?php } ?>
                                    <?php if($permissions->D==1){ ?> <a class="btn btn-sm btn-danger deleteDirective" data-carnet="0" href="#" data-userid="<?php echo $record->id; ?>" data-name="<?php echo $record->code ?>" title="Eliminar"><i class="fa fa-trash"></i></a>  <?php } ?>
                                </td>
                            </tr>
                            <?php } } ?> 
                            </tbody>
                            <tfoot>
                                <tr> 
                                    <th class="text-center">DOCUMENTO </th>
                                    <th class="text-center">NOMBRE</th>
                                    <th class="text-center">APARTAMENTO</th>
                                    <th class="text-center">EDIFICIO</th>    
                                    <th class="text-center">CARGO</th>
                                    <th class="text-center">PERIODO</th>    
                                    <th class="text-center">ESTADO	</th> 
                                    <th class="text-center">ACCIONES</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
               
        </div>
    </div>
    
</div>    
<script src="<?php echo base_url() ?>assets/scripts/bootstrap4-notify/bootstrap-notify.min.js"></script>
<script src="<?php echo base_url() ?>assets/scripts/bootstrap-notify-init.js"></script>
<script src="<?php echo base_url() ?>assets/scripts/datatables/js/jquery.dataTables.min.js"></script>
<script src="<?php echo base_url() ?>assets/scripts/datatables/js/dataTables.buttons.min.js"></script> 
<script src="<?php echo base_url() ?>assets/scripts/datatables/js/buttons.print.min.js"></script>
<script src="<?php echo base_url() ?>assets/scripts/datatables/js/buttons.html5.min.js"></script>   
<script src="<?php echo base_url() ?>assets/scripts/datatables/js/jszip.min.js"></script>   
<script src="<?php echo base_url() ?>assets/scripts/datatables.init.js"></script>  


