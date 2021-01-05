<link href="<?php echo base_url() ?>assets/scripts/datatables/css/jquery.dataTables.min.css" rel="stylesheet"> 

<div class="app-main__inner"> 
    <div class="card">
        <div class="card-body">
            <div class="row page-titles" style = "background: rgba(255, 255, 255, 0);margin-bottom:3rem">
                <div class="col-sm-9">
                    <h4>Listado de visitas</h4>
                </div>
                <?php if($permissions->C==1){ ?>
                <div class="col-sm-3 "> 
                    <button onclick ="window.location = 'entrada_salida'" type="button" class="btn btn-primary btn-lg btn-block"><span class="btn-icon-right"><i class="fa fa-plus"> </i> Agregar entrada o salida </span></button> 
                </div>
                <?php } 
                if($generates_report==0){
                    echo "<style>.dt-buttons{display:none}</style>";
                }
                ?>
            </div> 
            <div class="table-responsive">
                <table id="example" class="display" style="min-width: 845px">
                    <thead>
                        <tr> 
                            <th class="text-center">#</th>
                            <th class="text-center">VISITANTE</th>
                            <th class="text-center">FECHA DE EMISION </th>
                            <th class="text-center">TIPO</th>  
                            <th class="text-center">EDIF. - APTO.</th>    
                            <th class="text-center">PLACA</th>  
                            <th class="text-center">OBSERVACION</th> 
                        </tr>
                    </thead>
                    <tbody> 
                        <?php  if(!empty($VigilantRecords))  { foreach($VigilantRecords as $record)  {  ?>
                        <tr>  
                            <td class="text-center"style="vertical-align: middle;"><?php echo str_pad($record->id, 4, '0', STR_PAD_LEFT) ?> </td>
                            <td class="text-center"style="vertical-align: middle;"><a href="<?php echo base_url()."historial_visitante/".$record->document ?>" title="Ver historial"><?php echo "(".$record->document.") ".$record->name ?></a></td>
                            <td class="text-center" style="vertical-align: middle;"><?php echo date_format (new DateTime($record->created_at), 'Y-m-d g:i A') ?></td> 
                            <td class="text-center" style="vertical-align: middle;"><?php 
                            if($record->status==VIGILANT_INPUT){
                                echo '<span class="badge badge-success">ENTRADA</span>';
                            }else if($record->status==VIGILANT_EXIT){
                                echo '<span class="badge badge-danger">SALIDA</span>';
                            } ?></td> 
                            <td class="text-center" style="vertical-align: middle;"><?php echo  $record->code . ' - '. $record->apartment ?></td>  
                             <td class="text-center" style="vertical-align: middle;"><?php echo  $record->licence_plate ?></td>
                            <td class="text-center" style="vertical-align: middle;"> 
                                <?php if(!empty($record->observation)){ ?><a class="btn btn-sm btn-info observationVigilant" href="#" data-status="<?php echo $record->status; ?>" data-observation="<?php echo  $record->observation ?>"  data-name="<?php echo "(".$record->document.") ".$record->name ?>" title="Ver observacion"><i class="fa fa-info"></i> OBSERVACION</a> <?php }else{ echo "SIN OBSERVACION";} ?> 
                            </td>
                        </tr>
                        <?php } } ?>
                    </tbody>
                    <tfoot>
                        <tr> 
                            <th class="text-center">#</th>
                            <th class="text-center">VISITANTE</th>
                            <th class="text-center">FECHA DE EMISION </th>
                            <th class="text-center">TIPO</th>  
                            <th class="text-center">EDIF. - APTO.</th>     
                            <th class="text-center">PLACA</th>  
                            <th class="text-center">OBSERVACION</th>
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


