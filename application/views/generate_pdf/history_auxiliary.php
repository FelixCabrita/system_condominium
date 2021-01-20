<link href="<?php echo base_url() ?>assets/scripts/datatables/css/jquery.dataTables.min.css" rel="stylesheet"> 

<div class="app-main__inner"> 
    <div class="card">
        <div class="card-body">
        <div class="row page-titles" style = "background: rgba(255, 255, 255, 0);margin-bottom:3rem">
                <div class="col-sm-6">
                    <h4>Historial de auxiliares generados</h4>
                </div>
               
            </div>
            
            <div class="table-responsive">
                <table id="example" class="display" style="min-width: 845px">
                <thead>
                    <tr> 
                        <th class="text-center">CODIGO</th>
                        <th class="text-center">DOCUMENTO</th> 
                        <th class="text-center">FECHA DE GENERACION</th>  
                        <th class="text-center">ACCIONES</th>
                    </tr>
                </thead>
                <tbody> 
                    <?php
                    if(!empty($data))
                    {
                        foreach($data as $record)
                        {
                    ?>
                    <tr>  
                        <td class="text-center"style="vertical-align: middle;"><?php echo str_pad($record->id, 4, '0', STR_PAD_LEFT) ?></td>
                        <td class="text-center" style="vertical-align: middle;"><?php echo $record->file ?></td>
                     
                        <td class="text-center" style="vertical-align: middle;"><?php echo date_format (new DateTime($record->created_at), 'Y-m-d g:i A') ?></td> 
                        
                        <td class="text-center" style="vertical-align: middle;"> 
                        <a class="btn btn-sm btn-success"  target="_blank"  title="IMPRIMIR ETIQUETA" href="<?php echo base_url().PDF_PATH.$record->file; ?>" ><i class="fa fa-print"></i> REIMPRIMIR</a> </a>
                        <a class="btn btn-sm btn-danger deleteAuxiliary" href="#" data-userid="<?php echo $record->id; ?>" data-file="<?php echo $record->file ?>" title="Eliminar"><i class="fa fa-trash"></i></a>
                        </td>
                    </tr>
                    <?php
                        }
                    }
                    ?>
                </tbody>
                <tfoot>
                    <tr> 
                        <th class="text-center">CODIGO</th>
                        <th class="text-center">DOCUMENTO</th> 
                        <th class="text-center">FECHA DE GENERACION</th>  
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


