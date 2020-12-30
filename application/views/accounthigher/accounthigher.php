<link href="<?php echo base_url() ?>assets/scripts/datatables/css/jquery.dataTables.min.css" rel="stylesheet"> 

<div class="app-main__inner"> 
    <div class="card">
        <div class="card-body">
        <div class="row page-titles" style = "background: rgba(255, 255, 255, 0);margin-bottom:3rem">
                <div class="col-sm-6">
                    <h4>Listado de cuentas mayor</h4>
                </div>
                <?php if($permissions->C==1){ ?>
                    <div class="col-sm-3 "> 
                        <a href="#"  class="btn btn-primary btn-lg btn-block" data-toggle="modal" data-target="#importaccounthighers">
                            <span class="btn-icon-right"><i class="fa fa-upload"> </i> Importar cuentas mayor </span>
                        </a>  
                    </div>
                    <div class="col-sm-3 "> 
                            <button onclick ="window.location = 'agregarcuenta_mayor'" type="button" class="btn btn-primary btn-lg btn-block"><span class="btn-icon-right"><i class="fa fa-plus"> </i> Agregar nuevo cuenta mayor </span></button> 
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
                        <th class="text-center">CODIGO</th>
                        <th class="text-center">DESCRIPCION</th> 
                        <th class="text-center">CUENTA MAYOR</th> 
                        <th class="text-center">FECHA CREACION</th> 
                        <th class="text-center">ESTADO</th> 
                        <th class="text-center">ACCIONES</th>
                    </tr>
                </thead>
                <tbody> 
                    <?php
                    if(!empty($accounthigherRecords))
                    {
                        foreach($accounthigherRecords as $record)
                        {
                    ?>
                    <tr>  
                        <td class="text-center"style="vertical-align: middle;"><?php echo $record->code ?></td>
                        <td class="text-center" style="vertical-align: middle;"><?php echo $record->name ?></td>
                        <td class="text-center" style="vertical-align: middle;">
                        <a class="btn btn-sm btn-success" href="<?php echo base_url().'asignacion_cuenta_mayor/'.$record->code_control; ?>" title="Ver todos los documentos"><i class="fa fa-list"></i>   <?php echo $record->code_control . ' - '.$record->name_control ?></a> </td>
                        <td class="text-center" style="vertical-align: middle;"><?php echo date_format (new DateTime($record->created_at), 'Y-m-d g:i A') ?></td> 
                        <td class="text-center" style="vertical-align: middle;"><?php 
                        if($record->status==STATUS_ACTIVE){
                            echo '<span class="badge badge-success">ACTIVO</span>';
                        }else if($record->status==STATUS_INACTIVE){
                            echo '<span class="badge badge-danger">INACTIVO</span>';
                        } ?></td>
                        <td class="text-center" style="vertical-align: middle;">
                            <?php if($permissions->U==1){ ?><a class="btn btn-sm btn-primary changeStatusDocumentType"  href="#" data-titulo="<?php echo $record->name; ?>" data-isactive="<?php echo $record->status; ?>" data-userid="<?php echo $record->id; ?>" title="Cambio de estado"><i class="fa fa-eye"></i></a> 
                            <a class="btn btn-sm btn-info" href="<?php echo base_url().'editarcuenta_mayor/'.$record->id; ?>" title="Editar"><i class="fa fa-paint-brush"></i></a> <?php }else { ?> <a class="btn btn-sm btn-info" href="<?php echo base_url().'editarmodulos/'.$record->id; ?>" title="VER INFORMACION"><i class="fa fa-info"></i></a>  <?php } ?>                           
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
                        <th class="text-center">DESCRIPCION</th> 
                        <th class="text-center">CUENTA MAYOR</th> 
                        <th class="text-center">FECHA CREACION</th> 
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


