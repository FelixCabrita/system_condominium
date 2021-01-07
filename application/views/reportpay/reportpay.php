<link href="<?php echo base_url() ?>assets/scripts/datatables/css/jquery.dataTables.min.css" rel="stylesheet"> 

<div class="app-main__inner"> 
    <div class="card">
        <div class="card-body">
        <div class="row page-titles" style = "background: rgba(255, 255, 255, 0);margin-bottom:3rem">
                <div class="col-sm-9">
                    <?php if($rol < ROLE_VIGILANT) { ?><h4>Reportes de pago</h4><?php }else{ ?><h4>Mis reportes de pago</h4><?php }  ?>
                </div>
                <?php if($rol>ROLE_VIGILANT){ ?>
                    <div class="col-sm-3 "> 
                            <button onclick ="window.location = 'crearreportepago'" type="button" class="btn btn-primary btn-lg btn-block"><span class="btn-icon-right"><i class="fa fa-plus"> </i> Crear un reporte </span></button>          
                    </div>
                <?php } ?>
            </div>
            <div class="table-responsive">
                <table id="example" class="display" style="min-width: 845px">
                <thead>
                    <tr> 
                        <th class="text-center"># </th>
                        <th class="text-center">CLIENTE</th>
                        <th class="text-center">MONTO</th>
                        <th class="text-center">COD. TRANSFERENCIA</th>
                        <th class="text-center">FCH TRANSFERENCIA</th>  
                        <th class="text-center">FCH CREACION</th> 
                        <th class="text-center">ESTADO</th>
                        <th class="text-center">ACCIONES</th>
                    </tr>
                </thead>
                <tbody> 
                    <?php  if(!empty($reportpayRecords)) {  foreach($reportpayRecords as $record) {   ?>
                    <tr>  
                        <td class="text-center"style="vertical-align: middle;"><?php echo str_pad($record->id,4,"0", STR_PAD_LEFT)  ?></td>
                        <td class="text-center" style="vertical-align: middle;"><?php echo  $record->tenant ?></td>
                        <td class="text-center" style="vertical-align: middle;"><?php echo number_format($record->amount, 2, ',', '') ?></td>
                        <td class="text-center" style="vertical-align: middle;"><?php echo  $record->code_transfer ?></td>
                        <td class="text-center" style="vertical-align: middle;"><?php echo  $record->date_transfer ?></td>     
                        <td class="text-center" style="vertical-align: middle;"><?php echo  date_format (new DateTime($record->created_at), 'Y-m-d g:i A')  ?></td>
                        <td class="text-center" style="vertical-align: middle;"><?php 
                        if($record->status==PAY_APPROVED){
                            echo '<span class="badge badge-success">APROBADO</span>';
                        }else if($record->status==PAY_PENDING){
                            echo '<span class="badge badge-warning">PENDIENTE</span>';
                        }else if($record->status==PAY_DECLINED){
                            echo '<span class="badge badge-danger">RECHAZADO</span>';
                        } ?></td>
                        <td class="text-center" style="vertical-align: middle;"> 
                            <a class="btn btn-sm btn-info" href="<?php echo base_url().'reporte/'.$record->id; ?>" title="VER INFORMACION"><i class="fa fa-info"></i></a>   
                            <?php if($record->status==PAY_PENDING && $rol>ROLE_VIGILANT){ ?> <a class="btn btn-sm btn-danger deleteReportPay" href="#" data-userid="<?php echo $record->id; ?>" data-photo="<?php echo $record->photo; ?>" data-name="<?php echo str_pad($record->id,4,"0", STR_PAD_LEFT) ?>" title="Eliminar"><i class="fa fa-trash"></i></a>  <?php } ?>
                        </td>
                    </tr>
                    <?php
                        }
                    }
                    ?>
                </tbody>
                <tfoot>
                    <tr> 
                        <th class="text-center"># </th>
                        <th class="text-center">CLIENTE</th>
                        <th class="text-center">MONTO</th>
                        <th class="text-center">COD. TRANSFERENCIA</th>
                        <th class="text-center">FCH. TRANSFERENCIA</th>  
                        <th class="text-center">FCH. CREACION</th> 
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


