<link href="<?php echo base_url() ?>assets/scripts/datatables/css/jquery.dataTables.min.css" rel="stylesheet">


<div class="app-main__inner"> 
    <div class="card">
        <div class="card-body">
        <div class="row page-titles" style = "background: rgba(255, 255, 255, 0);margin-bottom:3rem">
                <div class="col-sm-9">
                    <h4>Listado de insidencias</h4>
                </div>
                <?php if($permissions->C==1){ ?>
                    <div class="col-sm-3 "> 
                            <button onclick ="window.location = 'agregarinsidencias'" type="button" class="btn btn-primary btn-lg btn-block"><span class="btn-icon-right"><i class="fa fa-plus"> </i> Agregar nuevo insidencias </span></button>          
                    </div>
                <?php } ?>
            </div>
            <div class="table-responsive">
                <table id="example" class="display" style="min-width: 845px">
                <thead>
                    <tr> 
                        <th class="text-center"># </th>
                        <th class="text-center">USUARIO </th>
                        <th class="text-center">TIPO</th>
                        <th class="text-center">FECHA CREACION</th> 
                        <th class="text-center">ESTADO</th> 
                        <th class="text-center">ACCIONES</th>
                    </tr>
                </thead>
                <tbody> 
                    <?php 
                    if(!empty($incidentsRecords))
                    {
                        foreach($incidentsRecords as $record)
                        {
                    ?>
                    <tr>   
                        <td class="text-center"style="vertical-align: middle;"><?php echo str_pad($record->id, 4, '0', STR_PAD_LEFT) ?></td>
                        <td class="text-center"style="vertical-align: middle;"><?php echo $record->name ?></td>
                        <td class="text-center" style="vertical-align: middle;"><?php echo checkRole($record->user_type) ?></td> 
                        <td class="text-center" style="vertical-align: middle;"><?php echo  date_format (new DateTime($record->created_at), 'Y-m-d g:i A')  ?></td>
                        <td class="text-center" style="vertical-align: middle;"><?php 
                        if($record->status== INCIDENTS_IN_REVIEW){
                            echo '<span class="badge badge-warning">EN REVISION</span>';
                        }else if($record->status== INCIDENTS_PENDING){
                            echo '<span class="badge badge-danger">PENDIENTE</span>';
                        }else if($record->status== INCIDENTS_SOLVED){
                            echo '<span class="badge badge-success">SOLUCIONADO</span>';
                        } ?></td>
                        <td class="text-center" style="vertical-align: middle;">
                            <?php if($rol < ROLE_VIGILANT && $permissions->U==1 ){ ?>
                                <a class="btn btn-sm btn-info" href="<?php echo base_url().'ver_informacion_insidencias/'.$record->id; ?>" title="VER INFORMACION"><i class="fa fa-info"></i></a>  
                            <?php }  if($id == $record->user){   ?>     
                                    <a class="btn btn-sm btn-primary" href="<?php echo base_url().'editarinsidencias/'.$record->id; ?>" title="EDITAR INFORMACION"><i class="fa fa-paint-brush"></i></a>  
                            <?php }   ?>
                            <?php if(empty(!$record->observations)){ ?> <a class="btn btn-sm btn-success observationIncidents"  href="#" data-name="<?php echo str_pad($record->id, 4, '0', STR_PAD_LEFT); ?>" data-observations="<?php echo $record->observations; ?>" data-userid="<?php echo $record->id; ?>" title="COMENTARIOS"><i class="fa fa-comment"></i></a>   <?php } ?> 
                       </td>
                    </tr>
                    <?php }  } ?>
                </tbody>
                <tfoot>
                    <tr> 
                        <th class="text-center"># </th>
                        <th class="text-center">USUARIO </th>
                        <th class="text-center">TIPO</th>
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


