<link href="<?php echo base_url() ?>assets/scripts/datatables/css/jquery.dataTables.min.css" rel="stylesheet"> 
 
<div class="app-main__inner"> 
    <div class="card">
        <div class="card-body">
            <div class="row page-titles" style = "background: rgba(255, 255, 255, 0); ">
                <div class="col-sm-12">
                    <h4>Reporte diario</h4>
                    <div class="row">
                        <div class="form-group col-sm-4">  
                            <label for="document">Fecha </label>
                            <input type="text" class="form-control mb-2 mr-sm-5" value="<?php echo $date_report; ?>" readonly>  
                        </div>   
                    </div>   
                </div> 
            </div> 
            <hr>
            <div class="table-responsive">
                <table id="visitante" class="display" style="min-width: 845px">
                    <thead>
                        <tr> 
                            <th class="text-center">#</th> 
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
                            <td class="text-center" style="vertical-align: middle;"><?php echo date_format (new DateTime($record->created_at), 'Y-m-d g:i A') ?></td> 
                            <td class="text-center" style="vertical-align: middle;"><?php 
                            if($record->status==VIGILANT_INPUT){
                                echo '<span class="badge badge-success">ENTRADA</span>';
                            }else if($record->status==VIGILANT_EXIT){
                                echo '<span class="badge badge-danger">SALIDA</span>';
                            } ?></td> 
                            <td class="text-center" style="vertical-align: middle;"><?php echo  $record->code . ' - '. $record->apartment ?></td>
                            <td class="text-center" style="vertical-align: middle;"><?php echo  $record->licence_plate  ?></td>
                            <td class="text-center" style="vertical-align: middle;"><?php echo  empty($record->observation)?"SIN OBSERVACION":$record->observation ?></td>
                        </tr>
                        <?php } } ?>
                    </tbody>
                    <tfoot>
                        <tr> 
                            <th class="text-center">#</th> 
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
<script src="<?php echo base_url() ?>assets/scripts/datatables/js/dataTables.buttons.min.js"></script> 
<script src="<?php echo base_url() ?>assets/scripts/datatables/js/buttons.print.min.js"></script>
<script src="<?php echo base_url() ?>assets/scripts/datatables/js/buttons.html5.min.js"></script>   
<script src="<?php echo base_url() ?>assets/scripts/datatables/js/jszip.min.js"></script>   


<script src="<?php echo base_url() ?>assets/scripts/datatables.init.js"></script>  



