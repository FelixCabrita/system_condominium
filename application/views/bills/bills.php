<link href="<?php echo base_url() ?>assets/scripts/datatables/css/jquery.dataTables.min.css" rel="stylesheet"> 
<div class="app-main__inner"> 
    <div class="card">
        <div class="card-body">
        
        <div class="row page-titles" style = "background: rgba(255, 255, 255, 0);margin-bottom:3rem">
                <div class="col-sm-9">
                    <h4>Facturas</h4>
                </div> 
                <?php if($permissions->C==1){ ?>
                    <div class="col-sm-3 "> 
                        <a href="#"  class="btn btn-primary btn-lg btn-block" data-toggle="modal" data-target="#load_bills">
                            <span class="btn-icon-right"><i class="fa fa-upload"> </i> Cargar facturas </span>
                        </a>  
                    </div> 
                <?php } ?>
            </div>  <?php
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
                        <th class="text-center"># </th>
                        <th class="text-center">CLIENTE</th>
                        <th class="text-center">MONTO</th> 
                        <th class="text-center">EMISION</th> 
                        <th class="text-center">ACCIONES</th>
                    </tr>
                </thead>
                <tbody> 
                    <?php if(!empty($billsRecords)){ foreach($billsRecords as $record) { ?>
                    <tr>
                        <td class="text-center"style="vertical-align: middle;"><?php echo str_pad($record->bill_group,4,"0", STR_PAD_LEFT) ?></td>
                        <td class="text-center" style="vertical-align: middle;"><?php echo strtoupper($record->client) ?></td>
                        <td class="text-center" style="vertical-align: middle;"><?php echo number_format($record->amount, 2, ',', '') ?></td>
                        <td class="text-center" style="vertical-align: middle;"><?php echo date_format (new DateTime($record->created_at), 'Y-m-d g:i A')  ?></td>
                        <td class="text-center" style="vertical-align: middle;">
                            <a class="btn btn-sm btn-success"  href="<?php echo base_url().'reporte_factura/'.$record->bill_group; ?>" target="_blank"  title="REIMPRIMIR"><i class="fa fa-print"></i> REIMPRIMIR</a> 
                        </td>
                    </tr>
                    <?php } } ?>
                </tbody>
                <tfoot>
                    <tr> 
                        <th class="text-center"># </th>
                        <th class="text-center">CLIENTE</th>
                        <th class="text-center">MONTO</th> 
                        <th class="text-center">EMISION</th> 
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


