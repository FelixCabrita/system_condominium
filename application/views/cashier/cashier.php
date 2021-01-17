<link href="<?php echo base_url() ?>assets/scripts/datatables/css/jquery.dataTables.min.css" rel="stylesheet"> 

<div class="app-main__inner"> 
    <div class="card">
        <div class="card-body">
        <div class="row page-titles" style = "background: rgba(255, 255, 255, 0);margin-bottom:3rem">
                <div class="col-sm-9">
                    <h4>Cierre de caja</h4>
                </div>
                <?php if($permissions->C==1){ ?> 
                    <div class="col-sm-3 "> 
                            <button onclick ="window.location = 'cierredecaja'" type="button" class="btn btn-primary btn-lg btn-block">
                            <span class="btn-icon-right"><i class="fa fa-plus"> </i> Nuevo cierre de caja </span></button> 
                    </div>
                <?php } ?>
            </div>
            <?php
                $this->load->helper('form');
                $error = $this->session->flashdata('error');   
                if($error) { ?>
                    <div class="alert alert-danger alert-dismissible fade show">
                        <button type="button" class="close h-100" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span> </button> 
                        <?php echo $error; ?>          
                    </div> 
                <?php }
                $success = $this->session->flashdata('success');
                if($success)  { ?> 
                    <div class="alert alert-success alert-dismissible fade show">
                        <button type="button" class="close h-100" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span> </button> 
                        <?php echo $success; ?>              
                    </div>  
            <?php } ?>
            <div class="table-responsive">
                <table id="example" class="display" style="min-width: 845px">
                <thead>
                    <tr> 
                        <th class="text-center">#</th>
                        <th class="text-center">USUARIO</th> 
                        <th class="text-center">MONTO CIERRE</th>  
                        <th class="text-center">FECHA</th>     
                        <th class="text-center">ESTADO</th>   
                         <th class="text-center">ACCION</th> 
                    </tr>
                </thead>
                <tbody> 
                    <?php
                    if(!empty($cashierRecords))
                    {
                        foreach($cashierRecords as $record)
                        {
                    ?>
                    <tr>  
                        <td class="text-center"style="vertical-align: middle;"><?php echo str_pad($record->id,4,"0", STR_PAD_LEFT) ?></td>
                        <td class="text-center" style="vertical-align: middle;"><?php echo $record->name ?></td>
                        <td class="text-center" style="vertical-align: middle;"><?php echo number_format(($record->cash_input +$record->transfer_input + $record->check_input +$record->card_input ), 2, ',', '') ?></td>
                        <td class="text-center" style="vertical-align: middle;"><?php echo date_format (new DateTime($record->created_at), 'Y-m-d g:i A') ?></td>  
                        <td class="text-center" style="vertical-align: middle;"><?php 
                        if($record->status==STATUS_ACTIVE){
                            echo '<span class="badge badge-success">ACTIVO</span>';
                        }else if($record->status==STATUS_INACTIVE){
                            echo '<span class="badge badge-danger">ANULADO</span>';
                        } ?></td>
                        <td class="text-center" style="vertical-align: middle;">
                            <?php if($permissions->U==1 && $record->status==1){ ?><a class="btn btn-sm btn-danger changeCashierClosing"  href="#" data-titulo="<?php echo $record->name; ?>" data-isactive="<?php echo $record->status; ?>" data-userid="<?php echo $record->id; ?>" title="Cambio de estado"><i class="fa fa-eye"></i>ANULAR</a> <?php }?> 
                            <a class="btn btn-sm btn-success" href="<?php echo base_url().'reporte_cierre_caja/'.$record->id; ?>" target="_blank" title="Editar"><i class="fa fa-print"></i> REIMPRIMIR</a>                           
                             
                        </td>
                    </tr>
                    <?php
                        }
                    }
                    ?>
                </tbody>
                <tfoot>
                <tr> 
                        <th class="text-center">#</th>
                        <th class="text-center">USUARIO</th> 
                        <th class="text-center">MONTO CIERRE</th>  
                        <th class="text-center">FECHA</th>     
                        <th class="text-center">ESTADO</th>   
                         <th class="text-center">ACCION</th> 
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


