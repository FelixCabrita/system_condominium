<link href="<?php echo base_url() ?>assets/scripts/datatables/css/jquery.dataTables.min.css" rel="stylesheet"> 

<div class="app-main__inner"> 
    <div class="card">
        <div class="card-body">
             <div class="row page-titles" style = "background: rgba(255, 255, 255, 0);">
                <div class="col-sm-9">
                    <h4>Listado de rubros</h4>
                </div>
                <?php if($permissions->C==1){ ?>
                    <div class="col-sm-3 margin-bottom:3rem"> 
                        <a href="#"  class="btn btn-primary btn-lg btn-block" data-toggle="modal" data-target="#importheadings">
                            <span class="btn-icon-right"><i class="fa fa-upload"> </i> Importar rubros </span>
                        </a>  
                    </div> 
                <?php } ?>
            </div>
            <hr>
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

        <form class="form-inlne" id="add"  action="<?php echo base_url() ?>nuevo_rubro" method="post" role="form"> 
            <div class="row">  
                <div class="form-group col-sm-5">
                    <label for="code">Codigo</label>
                    <input type="text"  class="form-control mb-2 mr-sm-5" id="code"  name="code" value="<?php echo set_value('code'); ?>"   maxlength="100" required> 
                    <?php echo validation_error('name');?> 
                </div>   
                <div class="form-group col-sm-5">
                    <label for="name">Concepto</label>
                    <input type="text"  class="form-control mb-2 mr-sm-5" id="name"  name="name" value="<?php echo set_value('name'); ?>"   maxlength="200" required> 
                    <?php echo validation_error('name');?> 
                </div>    
                <div class="form-group col-sm-2 text-center"> 
                    <button type="submit" class="btn btn-sm btn-info text-center"  style="height: 100%;width:100%;max-height:75;   min-height: 100%;    display: flex;align-items: center;" title="ASIGNAR APARTAMENTO"><i class="fa fa-check"></i>&nbsp; AGREGAR RUBRO</button>
                </div>   
            </div>   
        </form>
        <hr>
            <div class="table-responsive">
                <table id="example" class="display" style="min-width: 845px">
                <thead>
                    <tr> 
                        <th class="text-center">CODIGO</th>
                        <th class="text-center">CONCEPTO</th> 
                        <th class="text-center">FECHA CREACION</th> 
                        <th class="text-center">ESTADO</th> 
                        <th class="text-center">ACCIONES</th>
                    </tr>
                </thead>
                <tbody> 
                    <?php
                    if(!empty($headingRecords))
                    {
                        foreach($headingRecords as $record)
                        {
                    ?>
                    <tr>  
                        <td class="text-center"style="vertical-align: middle;"><?php echo $record->code ?></td>
                        <td class="text-center" style="vertical-align: middle;"><?php echo $record->name ?></td>
                        <td class="text-center" style="vertical-align: middle;"><?php echo date_format (new DateTime($record->created_at), 'Y-m-d g:i A') ?></td> 
                        <td class="text-center" style="vertical-align: middle;"><?php 
                        if($record->status==STATUS_ACTIVE){
                            echo '<span class="badge badge-success">ACTIVO</span>';
                        }else if($record->status==STATUS_INACTIVE){
                            echo '<span class="badge badge-danger">INACTIVO</span>';
                        } ?></td>
                        <td class="text-center" style="vertical-align: middle;">
                            <?php if($permissions->U==1){ ?><a class="btn btn-sm btn-primary changeStatusheading"  href="#" data-titulo="<?php echo $record->name; ?>" data-isactive="<?php echo $record->status; ?>" data-userid="<?php echo $record->id; ?>" title="Cambio de estado"><i class="fa fa-eye"></i></a> 
                            <a class="btn btn-sm btn-info changeDataHeading" href="#" data-name="<?php echo $record->name; ?>" data-userid="<?php echo $record->id; ?>" title="Editar"><i class="fa fa-paint-brush"></i></a>  <?php } ?>  
                            <?php if($permissions->C==1){ ?>  
                                <a class="btn btn-sm btn-success" href="<?php echo base_url().'asignacion_cuenta_control/'.$record->id; ?>" title="AGREGAR CUENTA CONTROL"><i class="fa fa-plus"></i></a>
                            <?php } ?>                         
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
                        <th class="text-center">CONCEPTO</th> 
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
<script src="<?php echo base_url(); ?>assets/scripts/system/addHeading.js" type="text/javascript"></script>  

