<div class="app-main__inner"> 
<?php if (($rol==ROLE_ACCOUNTING || $rol ==ROLE_ADMIN || $rol ==ROLE_MANAGER) && $condominium > 0 ) { ?>
    <div class="row">    
        <div class="col-md-4"> 
            <div class="main-card mb-3 card">
                <div class="card-body">
                    <ul class="list-group">
                        <li class="list-group-item">
                            <div class="widget-content p-0">
                                <div class="widget-content-outer">
                                    <div class="widget-content-wrapper">
                                        <div class="widget-content-left">
                                            <div class="widget-heading">FACTURAS DEL MES</div> 
                                        </div>
                                        <div class="widget-content-right">
                                            <div class="widget-numbers text-success"><?php echo $total_bill->count ?></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </li>
                        <li class="list-group-item">
                            <div class="widget-content p-0">
                                <div class="widget-content-outer">
                                    <div class="widget-content-wrapper">
                                        <div class="widget-content-left">
                                            <div class="widget-heading">MONTO FACTURADO</div> 
                                        </div>
                                        <div class="widget-content-right">
                                            <div class="widget-numbers text-primary">  <?php echo number_format( $total_bill->amount,0, '.', ',')  ?></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </li> 
                    </ul>
                </div>
            </div>
        </div>

        <div class="col-md-4">
             
            <div class="main-card mb-3 card">
                <div class="card-body">
                    <ul class="list-group">
                        <li class="list-group-item">
                            <div class="widget-content p-0">
                                <div class="widget-content-outer">
                                    <div class="widget-content-wrapper">
                                        <div class="widget-content-left">
                                            <div class="widget-heading">FACTURAS PAGADAS</div> 
                                        </div>
                                        <div class="widget-content-right">
                                            <div class="widget-numbers text-success"><?php echo $pay->count ?></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </li>
                        <li class="list-group-item">
                            <div class="widget-content p-0">
                                <div class="widget-content-outer">
                                    <div class="widget-content-wrapper">
                                        <div class="widget-content-left">
                                            <div class="widget-heading">MONTO PAGADO</div> 
                                        </div>
                                        <div class="widget-content-right">
                                            <div class="widget-numbers text-primary"><?php echo number_format( $pay->amount,0, '.', ',')  ?></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </li> 
                    </ul>
                </div>
            </div>
        </div>

        <div class="col-md-4"> 
            <div class="main-card mb-3 card">
                <div class="card-body">
                    <ul class="list-group">
                        <li class="list-group-item">
                            <div class="widget-content p-0">
                                <div class="widget-content-outer">
                                    <div class="widget-content-wrapper">
                                        <div class="widget-content-left">
                                            <div class="widget-heading">FACTURAS PENDIENTES</div> 
                                        </div>
                                        <div class="widget-content-right">
                                            <div class="widget-numbers text-success"><?php echo $pending->count ?></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </li>
                        <li class="list-group-item">
                            <div class="widget-content p-0">
                                <div class="widget-content-outer">
                                    <div class="widget-content-wrapper">
                                        <div class="widget-content-left">
                                            <div class="widget-heading">MONTO PENDIENTE</div> 
                                        </div>
                                        <div class="widget-content-right">
                                            <div class="widget-numbers text-primary"><?php echo number_format($pending->amount,0, '.', ',')  ?></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </li> 
                    </ul>
                </div>
            </div>
        </div>
 
        <div class="col-md-6 col-xl-4">
            <div class="card mb-3 widget-content bg-midnight-bloom">
                <div class="widget-content-wrapper text-white">
                    <div class="widget-content-left">
                        <div class="widget-heading">FACTURAS DEL MES</div> 
                    </div>
                    <div class="widget-content-right">
                        <div class="widget-numbers text-white"><span><?php echo $total_bill->count ?></span></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-xl-4">
            <div class="card mb-3 widget-content bg-arielle-smile">
                <div class="widget-content-wrapper text-white">
                    <div class="widget-content-left">
                        <div class="widget-heading">FACTURAS PAGADAS</div>
                    </div>
                    <div class="widget-content-right">
                        <div class="widget-numbers text-white"><span><?php echo $pay->count ?></span></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-xl-4">
            <div class="card mb-3 widget-content bg-grow-early">
                <div class="widget-content-wrapper text-white">
                    <div class="widget-content-left">
                        <div class="widget-heading">FACTURAS PENDIENTES</div> 
                    </div>
                    <div class="widget-content-right">
                    <div class="widget-numbers text-white"><span><?php echo ($pending->count) ?></span></div>
                    </div>
                </div>
            </div>
        </div> 
    </div> 

    <div class="row">   
        <div class="col-md-6 col-xl-4">
            <div class="card mb-3 widget-content bg-midnight-bloom">
                <div class="widget-content-wrapper text-white">
                    <div class="widget-content-left">
                        <div class="widget-heading">TOTAL EN FACTURAS</div> 
                    </div>
                    <div class="widget-content-right">
                        <div class="widget-numbers text-white"><span>$ <?php echo number_format($total_bill->amount, 0, '.', ',') ?></span></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-xl-4">
            <div class="card mb-3 widget-content bg-arielle-smile">
                <div class="widget-content-wrapper text-white">
                    <div class="widget-content-left">
                        <div class="widget-heading">TOTAL PAGADO</div> 
                    </div>
                    <div class="widget-content-right">
                        <div class="widget-numbers text-white"><span>$ <?php echo number_format($pay->amount, 0, '.', ',') ?></span></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-xl-4">
            <div class="card mb-3 widget-content bg-grow-early">
                <div class="widget-content-wrapper text-white">
                    <div class="widget-content-left">
                        <div class="widget-heading">DEUDA PENDIENTE</div> 
                    </div>
                    <div class="widget-content-right">
                    <div class="widget-numbers text-white"><span>$ <?php echo number_format($pending->amount, 0, '.', ',') ?></span></div>
                    </div>
                </div>
            </div>
        </div> 
    </div>

<?php } ?> 

<?php if ($rol==ROLE_VENDOR) {   ?> 
    <div class="row">   
        <div class="col-md-6 col-xl-4">
            <div class="card mb-3 widget-content bg-midnight-bloom">
                <div class="widget-content-wrapper text-white">
                    <div class="widget-content-left">
                        <div class="widget-heading">PAGOS ADELANTADOS</div> 
                    </div>
                    <div class="widget-content-right">
                        <div class="widget-numbers text-white"><span>$ <?php echo number_format($payment_date->amount, 0, '.', ',') ?></span></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-xl-4">
            <div class="card mb-3 widget-content bg-arielle-smile">
                <div class="widget-content-wrapper text-white">
                    <div class="widget-content-left">
                        <div class="widget-heading">FACTURAS COBRADAS</div> 
                    </div>
                    <div class="widget-content-right">
                        <div class="widget-numbers text-white"><span>$ <?php echo $paymentaa ?></span></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-xl-4">
            <div class="card mb-3 widget-content bg-midnight-bloom">
                <div class="widget-content-wrapper text-white">
                    <div class="widget-content-left">
                        <div class="widget-heading">MONTO COBRADO</div> 
                    </div>
                    <div class="widget-content-right">
                        <div class="widget-numbers text-white"><span>$ <?php echo number_format($payment->amount, 0, '.', ',') ?></span></div>
                    </div>
                </div>
            </div>
        </div> 
    </div>

    <div class="row">   
        <div class="col-md-6 col-xl-3">
            <div class="card mb-3 widget-content bg-midnight-bloom">
                <div class="widget-content-wrapper text-white">
                    <div class="widget-content-left">
                        <div class="widget-heading">EFECTIVO</div> 
                    </div>
                    <div class="widget-content-right">
                        <div class="widget-numbers text-white"><span>$ <?php echo number_format($cash->amount, 0, '.', ',') ?></span></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-xl-3">
            <div class="card mb-3 widget-content bg-arielle-smile">
                <div class="widget-content-wrapper text-white">
                    <div class="widget-content-left">
                        <div class="widget-heading">TRANSFERENCIA</div> 
                    </div>
                    <div class="widget-content-right">
                        <div class="widget-numbers text-white"><span>$ <?php echo number_format($transfer->amount, 0, '.', ',') ?></span></div>
                    </div>
                </div>
            </div>
        </div> 
        <div class="col-md-6 col-xl-3">
            <div class="card mb-3 widget-content bg-midnight-bloom">
                <div class="widget-content-wrapper text-white">
                    <div class="widget-content-left">
                        <div class="widget-heading">CHEQUE</div> 
                    </div>
                    <div class="widget-content-right">
                        <div class="widget-numbers text-white"><span>$ <?php echo number_format($check->amount, 0, '.', ',') ?></span></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-xl-3">
            <div class="card mb-3 widget-content bg-arielle-smile">
                <div class="widget-content-wrapper text-white">
                    <div class="widget-content-left">
                        <div class="widget-heading">TARJETA</div> 
                    </div>
                    <div class="widget-content-right">
                        <div class="widget-numbers text-white"><span>$ <?php echo number_format($card->amount, 0, '.', ',') ?></span></div>
                    </div>
                </div>
            </div>
        </div> 
    </div>
    
    
<?php } ?> 


<?php if ($rol>=ROLE_OWNER) {   ?> 
    <div class="row">   
        <div class="col-md-6 col-xl-6">
            <div class="card mb-3 widget-content bg-midnight-bloom">
                <div class="widget-content-wrapper text-white">
                    <div class="widget-content-left">
                        <div class="widget-heading">MONTO ADEUDADO</div> 
                    </div>
                    <div class="widget-content-right">
                        <div class="widget-numbers text-white"><span>$ <?php echo number_format($total_bill->amount, 0, '.', ',') ?></span></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-xl-6">
            <div class="card mb-3 widget-content bg-arielle-smile">
                <div class="widget-content-wrapper text-white">
                    <div class="widget-content-left">
                        <div class="widget-heading">MONTO PAGADO</div> 
                    </div>
                    <div class="widget-content-right">
                        <div class="widget-numbers text-white"><span>$ <?php echo number_format($pay->amount, 0, '.', ',') ?></span></div>
                    </div>
                </div>
            </div>
        </div>
         
    </div>

   
    
    
<?php } ?>  
    <div class="row">
    <?php if ($rol==ROLE_ADMIN ||$rol==ROLE_MANAGER || $rol >=ROLE_VIGILANT ) { ?>
        <div class="col-md-6">
            <div class="main-card mb-3 card">
                <div class="card-header">RESERVACIONES  
                </div>
                <div class="table-responsive">
                    <table class="align-middle mb-0 table table-borderless table-striped table-hover">
                        <thead>
                            <tr>
                                <th class="text-center">APTO.</th>
                                <th>FECHA</th>
                                <th class="text-center">DESDE - HASTA</th>
                                <th class="text-center">ESTADO</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(!empty($reservation)) {  foreach($reservation as $record) { ?>
                                <tr>
                                    <td class="text-center text-muted"><?php echo $record->apartment_code ?></td>
                                    <td class="text-center text-muted"><?php echo date_format (new DateTime($record->reservation_date), 'Y-m-d') ?></td>  
                                    <td class="text-center text-muted"><?php echo  date_format (new DateTime($record->start_time), 'g:i A') . " - " . date_format (new DateTime($record->ending_time), 'g:i A')   ?></td>
                                    <td class="text-center" style="vertical-align: middle;"><?php 
                                        if($record->status== RESERVE_APPROVED){
                                            echo '<span class="badge badge-success">APROBADO</span>';
                                        }else if($record->status== RESERVE_PENDING){
                                            echo '<span class="badge badge-warning">PENDIENTE</span>';
                                        }else if($record->status== RESERVE_DECLINED){
                                            echo '<span class="badge badge-danger">DECLINADA</span>';
                                        } ?>
                                    </td>
                                        
                                </tr> 
                            <?php  } }  ?>
                        </tbody>
                    </table>
                </div>
                <div class="d-block text-center card-footer">
                <a href="<?php echo base_url().'reservaciones'; ?>" class="btn-wide btn btn-success">Ver mas...</a>
                </div>
            </div>
        </div>
   
        <div class="col-md-6">
            <div class="main-card mb-3 card">
                <div class="card-header">INCIDENCIAS
                    
                </div>
                <div class="table-responsive">
                    <table class="align-middle mb-0 table table-borderless table-striped table-hover">
                        <thead>
                            <tr>
                                <th class="text-center"># </th>
                                <th class="text-center">USUARIO </th> 
                                <th class="text-center">FECHA CREACION</th> 
                                <th class="text-center">ESTADO</th>  
                            </tr>   
                        </thead>
                        <tbody>
                        <?php if(!empty($incidents)) { foreach($incidents as $record) { ?>
                            <td class="text-center"style="vertical-align: middle;"><?php echo str_pad($record->id, 4, '0', STR_PAD_LEFT) ?></td>
                            <td class="text-center"style="vertical-align: middle;"><?php echo $record->name ?></td> 
                            <td class="text-center" style="vertical-align: middle;"><?php echo  date_format (new DateTime($record->created_at), 'Y-m-d g:i A')  ?></td>
                            <td class="text-center" style="vertical-align: middle;"><?php 
                            if($record->status== INCIDENTS_IN_REVIEW){
                                echo '<span class="badge badge-warning">EN REVISION</span>';
                            }else if($record->status== INCIDENTS_PENDING){
                                echo '<span class="badge badge-danger">PENDIENTE</span>';
                            }else if($record->status== INCIDENTS_SOLVED){
                                echo '<span class="badge badge-success">SOLUCIONADO</span>';
                            } ?></td> 
                        <?php  } }  ?>  
                        </tbody>
                    </table>
                </div>
                <div class="d-block text-center card-footer">
                    <a href="<?php echo base_url().'insidencias'; ?>" class="btn-wide btn btn-success">Ver mas...</a>
                </div>
            </div>
        </div>
    <?php } ?>
    </div>
   
</div>