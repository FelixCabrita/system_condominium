<link href="<?php echo base_url() ?>assets/scripts/datatables/css/jquery.dataTables.min.css" rel="stylesheet"> 
<?php 
$tenant = $reportpayRecords->tenant;
$type = $reportpayRecords->type;
$amount = number_format($reportpayRecords->amount, 2, ',', ' ');
$code_transfer = $reportpayRecords->code_transfer;
$date_transfer = $reportpayRecords->date_transfer;
$photo = $reportpayRecords->photo;
$id = $reportpayRecords->id;
$status = $reportpayRecords->status;
?>

<div class="app-main__inner"> 
    <div class="card">
        <div class="card-body">
            <div class="row page-titles" style = "background: rgba(255, 255, 255, 0);">
                <div class="col-sm-8">
                    <h4>Reporte de pago <b>#<?php echo str_pad($reportpayRecords->id,4,"0", STR_PAD_LEFT) ?></b></h4>
                </div> 
                <div class="col-sm-4 text-right">

                    <?php
                    if($status==PAY_PENDING){ 
                        if($permissions->U==1){ ?>
                            <a class="btn btn-sm btn-success changeStatusReportPay"  href="#" data-userid="<?php echo $id; ?>" data-isactive="<?php echo 1; ?>" data-name="<?php echo str_pad($reportpayRecords->id,4,"0", STR_PAD_LEFT) ?>"><i class="fa fa-check"></i> APROBAR</a>
                            <a class="btn btn-sm btn-warning changeStatusReportPay"  href="#" data-userid="<?php echo $id; ?>" data-isactive="<?php echo -1; ?>" data-name="<?php echo str_pad($reportpayRecords->id,4,"0", STR_PAD_LEFT) ?>"><i class="fa fa-times"></i> RECHAZAR</a>
                        <?php } 
                        if($permissions->D==1){ ?> 
                            <a class="btn btn-sm btn-danger deleteReportPay" href="#" data-userid="<?php echo $id; ?>" data-photo="<?php echo $photo; ?>" data-name="<?php echo str_pad($reportpayRecords->id,4,"0", STR_PAD_LEFT) ?>"><i class="fa fa-trash"></i> BORRAR</a>
                        <?php }
                    }else{
                        if($status==PAY_APPROVED){
                            echo '<span class="badge badge-success">APROBADO</span>';
                        }else if($status==PAY_DECLINED){
                            echo '<span class="badge badge-danger">RECHAZADO</span>';
                        } 
                    } ?> 
                </div> 
            </div>
            <div class="row page-titles" style = "background: rgba(255, 255, 255, 0);"> 
                <div class="col-sm-6 dataclient">
                    <div class="input-group"> 
                        <input type="text"  class="form-control " value="<?php echo $tenant ?>"   id="name" readonly > 
                    </div> 
                </div> 
                <div class="col-sm-6 dataclient">
                    <div class="input-group"> 
                        <input type="text"  class="form-control "   value="<?php echo checkRole($type) ?>"  id="type"  readonly >  
                    </div> 
                </div> 
            </div> 
            <hr>
            <div class="row"> 
              
                <div class="col-sm-12">
                    <div class="row"> 
                        <div class=" col-sm-6">
                            <div class="row"> 
                                <div class=" col-6">
                                    <spam style = "font-size:16px"for="code">Monto a pagar</spam> 
                                </div>   
                                <div class=" col-6"> 
                                    <label for="code"><b><spam  style = "font-size:16px;color:red" id="total"  name="total" readonly><?php echo $amount ?></spam></b></label>  
                                </div>   
                            </div> 
                        </div>   
                        <div class=" col-6"> 
                            <div class="row"> 
                                <div class=" col-5">
                                    <spam style = "font-size:16px"for="code">Pago en:</spam> 
                                </div>   
                                <div class=" col-7"> 
                                    <label for="code"><b><spam id="payment_type_text"  style = "font-size:16px;color:red">TRANSFERENCIA</spam></b></label> 
                                </div>   
                            </div>
                        </div>   
                    </div>  
                    <div class="row payment_transfer"> 
                        <div class="col-sm-4">
                            <label for="transfer_code">Codigo de transferencia:<b><spam  style = "font-size:16px;color:black" readonly> <?php echo $code_transfer ?></spam></b> </label>
                        </div>

                        <div class="col-sm-4">
                            <label for="transfer_date">Fecha emision de transferencia:<b><spam  style = "font-size:16px;color:black" readonly> <?php echo $date_transfer ?></spam></b></label>
                        </div> 
                        <div class="col-sm-4">
                            <label for="transfer_date"> <a   href="<?php echo base_url()."assets/images/pay/".$photo; ?>" target= "_blank">VER FOTO DE TRANSFERENCIA AQUI</a></label>
                        </div> 
                    </div> 
                </div>   
                <div class="form-group col-sm-12">  
                    <div class="table-responsive">
                        <table id="example" class="display compact stripe" style="min-width: 100%">
                            <thead>
                                <tr>  
                                    <th class="text-center" >#</th>
                                    <th class="text-center">DETALLE</th>
                                    <th class="text-center">MONTO</th>
                                    <th class="text-center">APTO.</th>  
                                    <th class="text-center">EMISION</th>   
                                 
                                </tr>
                            </thead>
                            <tbody>  
                            <?php
                                if(!empty($reportpaydetailsRecords))
                                {   
                                    $count=1;
                                    foreach($reportpaydetailsRecords as $record)
                                    {
                                ?>
                                <tr>  
                                    <td class="text-center"style="vertical-align: middle;"><?php echo $count ?></td>
                                    <td class="text-center"style="vertical-align: middle;"><?php echo $record->description ?></td>
                                    <td class="text-center" style="vertical-align: middle;"><?php echo $record->amount ?></td>
                                    <td class="text-center" style="vertical-align: middle;"><?php echo  $record->code ?></td>
                                    <td class="text-center" style="vertical-align: middle;"><?php echo  date_format (new DateTime($record->created_at), 'Y-m-d g:i A')  ?></td>
                                </tr>
                                <?php
                                $count++;
                                    }
                                }
                            ?>
                            </tbody>
                            <tfoot>
                                <tr>  
                                <th class="text-center" >#</th>
                                    <th class="text-center">DETALLE</th>
                                    <th class="text-center">MONTO</th>
                                    <th class="text-center">APTO.</th>  
                                    <th class="text-center">EMISION</th>   
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
<script src="<?php echo base_url() ?>assets/scripts/datatables.init.js"></script> 
 


