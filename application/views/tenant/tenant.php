<link href="<?php echo base_url() ?>assets/scripts/datatables/css/jquery.dataTables.min.css" rel="stylesheet"> 
<div class="app-main__inner"> 
    <div class="card">
        <div class="card-body">
        <div class="row page-titles" style = "background: rgba(255, 255, 255, 0);margin-bottom:3rem">
                <div class="col-sm-6">
                    <h4>Listado de clientes</h4>
                </div>
                <?php if($permissions->C==1){ ?>
                    <div class="col-sm-3 "> 
                        <a href="#"  class="btn btn-primary btn-lg btn-block" data-toggle="modal" data-target="#importClientss">
                            <span class="btn-icon-right"><i class="fa fa-upload"> </i> Importar clientes </span>
                        </a>  
                    </div>
                    <div class="col-sm-3 "> 
                            <button onclick ="window.location = 'agregarclientes'" type="button" class="btn btn-primary btn-lg btn-block"><span class="btn-icon-right"><i class="fa fa-plus"> </i> Agregar nuevo cliente </span></button> 
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
                <p><b>CLIENTES CON USUARIO: <?php echo $parameter ?> / PERMITIDOS <?php echo $max_user_tenant ?></b></p>
                <table id="example" class="display" style="min-width: 845px">
                <thead>
                    <tr> 
                        <th class="text-center">DOCUMENTO </th>
                        <th class="text-center">NOMBRE</th>
                        <th class="text-center">TELEFONO MOVIL</th>
                        <th class="text-center">CORREO ELETRONICO</th> 
                        <th class="text-center">ACCIONES</th>
                    </tr>
                </thead>
                <tbody> 
                    <?php
                    if(!empty($TenantRecords))
                    {
                        foreach($TenantRecords as $record)
                        {
                    ?>
                    <tr>  
                        <td class="text-center"style="vertical-align: middle;"><?php echo $record->document ?></td>
                        <td class="text-center" style="vertical-align: middle;"><?php echo $record->name ?></td>
                        <td class="text-center" style="vertical-align: middle;"><?php echo  $record->mobile ?></td>
                        <td class="text-center" style="vertical-align: middle;"><?php echo  $record->email  ?></td> 
                        <td class="text-center" style="vertical-align: middle;">
                           <?php if($permissions->U==1){ ?> 
                            <?php if(!empty($record->password)){ ?>
                            <a class="btn btn-sm btn-warning changeStatusTenant" href="#" data-userid="<?php echo $record->id; ?>" data-name="<?php echo $record->name ?>" title="DESHABILTAR EL USUARIO"><i class="fa fa-user-times"></i></a>
                            <?php } ?>
                            <a class="btn btn-sm btn-info" href="<?php echo base_url().'editarclientes/'.$record->id; ?>" title="Editar"><i class="fa fa-paint-brush"></i></a> 
                             <?php }else { ?>  
                                <a class="btn btn-sm btn-info" href="<?php echo base_url().'editarclientes/'.$record->id; ?>" title="VER INFORMACION"><i class="fa fa-info"></i></a>  
                            <?php } ?>
                            <a class="btn btn-sm btn-success" href="<?php echo base_url().'asignaciones/'.$record->id; ?>" title="Apartamentos asignados"><i class="fa fa-home"></i></a>
                            <?php if($permissions->D==1){ ?> <a class="btn btn-sm btn-danger deleteTenant" href="#" data-userid="<?php echo $record->id; ?>" data-name="<?php echo $record->name ?>" title="Eliminar"><i class="fa fa-trash"></i></a>  <?php } ?>
                        </td>
                    </tr>
                    <?php
                        }
                    }
                    ?>
                </tbody>
                <tfoot>
                <tr> 
                        <th class="text-center">DOCUMENTO </th>
                        <th class="text-center">NOMBRE</th>
                        <th class="text-center">TELEFONO MOVIL</th>
                        <th class="text-center">CORREO ELETRONICO</th> 
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


