<link href="<?php echo base_url() ?>assets/scripts/datatables/css/jquery.dataTables.min.css" rel="stylesheet"> 

<div class="app-main__inner"> 
    <div class="card">
        <div class="card-body">
        <div class="row page-titles" style = "background: rgba(255, 255, 255, 0);margin-bottom:3rem">
                <div class="col-sm-9">
                    <h4>Entradas de diario</h4>
                </div>
                <?php if($permissions->C==1){ ?>
                    <div class="col-sm-3 "> 
                            <button onclick ="window.location = 'entrada_de_diario'" type="button" class="btn btn-primary btn-lg btn-block">
                                <span class="btn-icon-right"><i class="fa fa-plus"> </i> Agregar nueva entrada </span>
                            </button>          
                    </div>
                <?php } ?>
            </div>
            <div class="table-responsive">
                <table id="example" class="display" style="min-width: 845px">
                <thead>
                    <tr> 
                        <th class="text-center"># </th>
                        <th class="text-center">MODULO</th>
                        <th class="text-center">TIPO DOCUMENTO</th>
                        <th class="text-center">CONCEPTO</th> 
                        <th class="text-center">FECHA CREACION</th> 
                        <th class="text-center">ACCIONES</th>
                    </tr>
                </thead>
                <tbody> 
                    <?php
                    if(!empty($dailyRecords))
                    {
                        foreach($dailyRecords as $record)
                        {  
                    ?>
                    <tr>  

                        <td class="text-center"style="vertical-align: middle;"><?php echo str_pad($record->id,4,"0", STR_PAD_LEFT) ?></td>
                        <td class="text-center" style="vertical-align: middle;"><?php echo $record->module ?></td>
                        <td class="text-center" style="vertical-align: middle;"><?php echo  $record->documentType ?></td>
                        <td class="text-center" style="vertical-align: middle;"><?php echo  $record->description ?></td>
                        <td class="text-center" style="vertical-align: middle;"><?php echo  date_format (new DateTime($record->created_at), 'Y-m-d g:i A')  ?></td>
                        <td class="text-center" style="vertical-align: middle;">
                            <a class="btn btn-sm btn-success changeStatusPeriod"  href="#"  data-userid="<?php echo $record->id; ?>" title="IMPRIMIR"><i class="fa fa-print"></i></a> 
                            <a class="btn btn-sm btn-info" href="<?php echo base_url().'editar_entrada_diario/'.$record->id; ?>" title="EDITAR"><i class="fa fa-paint-brush"></i></a>  
                        </td>
                    </tr>
                    <?php } } ?>
                </tbody>
                <tfoot>
                    <tr> 
                        <th class="text-center"># </th>
                        <th class="text-center">MODULO</th>
                        <th class="text-center">TIPO DOCUMENTO</th>
                        <th class="text-center">CONCEPTO</th> 
                        <th class="text-center">FECHA CREACION</th> 
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


