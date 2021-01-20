<div class="app-main__inner"> 
<?php if ($rol==ROLE_ADMIN ||$rol==ROLE_MANAGER || $rol >=ROLE_OWNER ) { ?>
    <div class="row">   
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
                        <div class="widget-numbers text-white"><span>$ <?php echo number_format($total_bill->amount, 2, ',', '') ?></span></div>
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
                        <div class="widget-numbers text-white"><span>$ <?php echo number_format($pay->amount, 2, ',', '') ?></span></div>
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
                    <div class="widget-numbers text-white"><span>$ <?php echo number_format($pending->amount, 2, ',', '') ?></span></div>
                    </div>
                </div>
            </div>
        </div> 
    </div>
    <?php } ?> 
    <div class="row">
    <?php if ($rol==ROLE_ADMIN ||$rol==ROLE_MANAGER || $rol >=ROLE_VIGILANT ) { ?>
        <div class="col-md-12">
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
                                <th class="text-center">ACCION</th>
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
                                        } ?></td>
                                        <td class="text-center" style="vertical-align: middle;">
                                    
                                            <?php if($rol<ROLE_VIGILANT && $record->status == RESERVE_PENDING){ ?>
                                                <a class="btn btn-sm btn-success changeStatusReservation"  href="#" data-titulo="<?php echo $record->apartment_code; ?>" data-isactive="<?php echo RESERVE_APPROVED ?>" data-userid="<?php echo $record->id; ?>" title="APROBAR"><i class="fa fa-check"></i></a> 
                                            <?php } ?> 
                                            <?php if($record->status > RESERVE_DECLINED){ ?>
                                                <a class="btn btn-sm btn-danger changeStatusReservation"  href="#" data-titulo="<?php echo $record->apartment_code; ?>" data-isactive="<?php echo RESERVE_DECLINED ?>" data-userid="<?php echo $record->id; ?>" title="DECLINAR"><i class="fa fa-trash"></i></a>
                                            <?php } ?>   
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
   
        <div class="col-md-12">
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
                                <th class="text-center">ACCIONES</th>
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
                            <td class="text-center" style="vertical-align: middle;">
                                <?php if($rol < ROLE_VIGILANT){ ?>
                                    <a class="btn btn-sm btn-info" href="<?php echo base_url().'ver_informacion_insidencias/'.$record->id; ?>" title="VER INFORMACION"><i class="fa fa-info"></i></a>  
                                <?php }  if($id == $record->user && $record->user_type == $rol){   ?>     
                                        <a class="btn btn-sm btn-primary" href="<?php echo base_url().'editarinsidencias/'.$record->id; ?>" title="EDITAR INFORMACION"><i class="fa fa-paint-brush"></i></a>  
                                <?php }   ?>
                                <?php if(empty(!$record->observations)){ ?> <a class="btn btn-sm btn-success observationIncidents"  href="#" data-name="<?php echo str_pad($record->id, 4, '0', STR_PAD_LEFT); ?>" data-observations="<?php echo $record->observations; ?>" data-userid="<?php echo $record->id; ?>" title="COMENTARIOS"><i class="fa fa-comment"></i></a>   <?php } ?> 
                            </td>
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