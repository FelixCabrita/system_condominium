<link href="<?php echo base_url() ?>assets/scripts/datatables/css/jquery.dataTables.min.css" rel="stylesheet">
<link href="<?php echo base_url() ?>assets/scripts/sweetalert2/dist/sweetalert2.min.css" rel="stylesheet">

<div class="app-main__inner"> 
    <div class="card">
        <div class="card-body">
        <div class="row page-titles" style = "background: rgba(255, 255, 255, 0);margin-bottom:3rem">
                <div class="col-sm-9">
                    <h4>Listado de usuarios</h4>
                </div>
                <?php if($permissions->C==1){ ?>
                <div class="col-sm-3 ">
                    
                        <button onclick ="window.location = 'addNew'" type="button" class="btn btn-primary btn-lg btn-block"><span class="btn-icon-right"><i class="fa fa-plus"> </i> Agregar nuevo usuario </span></button> 
                    
                </div>
                <?php } ?>
            </div>
            <div class="table-responsive">
                <table id="example" class="display" style="min-width: 845px">
                <thead>
                    <tr> 
                        <th class="text-center">NOMBRE </th>
                        <th class="text-center">CORREO ELECTRONOCO</th>
                        <th class="text-center">TIPO</th>
                        <th class="text-center">FECHA CREACION</th>
                        <th class="text-center">ESTADO</th>
                        <th class="text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody> 
                    <?php
                    if(!empty($userRecords))
                    {
                        foreach($userRecords as $record)
                        {
                    ?>
                    <tr>  
                        <td class="text-center"style="vertical-align: middle;"><?php echo $record->name ?></td>
                        <td class="text-center" style="vertical-align: middle;"><?php echo $record->username ?></td>
                        <td class="text-center" style="vertical-align: middle;"><?php echo checkRole($record->type) ?></td>
                        <td class="text-center" style="vertical-align: middle;"><?php echo  $record->created_at  ?></td>
                        <td class="text-center" style="vertical-align: middle;"><?php 
                        if($record->status==1){
                            echo '<span class="badge badge-success">ACTIVO</span>';
                        }else if($record->status==0){
                            echo '<span class="badge badge-danger">INACTIVO</span>';
                        } ?></td>
                        <td class="text-center" style="vertical-align: middle;">
                            <?php if($permissions->U==1){ ?><a class="btn btn-sm btn-primary activ"  href="#" data-titulo="<?php echo $record->name; ?>" data-isactive="<?php echo $record->status; ?>" data-userid="<?php echo $record->id; ?>" title="Cambio de estado"><i class="fa fa-eye"></i></a>
                            <a class="btn btn-sm btn-info" href="<?php echo base_url().'editarcondominio/'.$record->id; ?>" title="Editar"><i class="fa fa-paint-brush"></i></a> <?php }else { ?> <a class="btn btn-sm btn-info" href="<?php echo base_url().'editarcondominio/'.$record->id; ?>" title="VER INFORMACION"><i class="fa fa-info"></i></a>  <?php } ?>
                            <?php if($permissions->D==1){ ?> <a class="btn btn-sm btn-danger deleteUser" href="#" data-userid="<?php echo $record->id; ?>" data-name="<?php echo $record->name ?>" title="Eliminar"><i class="fa fa-trash"></i></a>  <?php } ?>
                        </td>
                    </tr>
                    <?php
                        }
                    }
                    ?>
                </tbody>
                <tfoot>
                    <tr> 
                        <th class="text-center">NOMBRE </th>
                        <th class="text-center">CORREO ELECTRONOCO</th>
                        <th class="text-center">TIPO</th>
                        <th class="text-center">FECHA CREACION</th>
                        <th class="text-center">ESTADO</th>
                        <th class="text-center">Acciones</th>
                    </tr>
                </tfoot>
            </table>
            </div>
        </div>
    </div>
    
</div>  

<script>var baseURL = "<?php echo base_url();?>" </script>  
<script src="<?php echo base_url(); ?>assets/scripts/jquery-3.5.1.min.js"></script>  
<script src="<?php echo base_url() ?>assets/scripts/sweetalert2@10.js"></script>
<script src="<?php echo base_url() ?>assets/scripts/bootstrap4-notify/bootstrap-notify.min.js"></script>
<script src="<?php echo base_url() ?>assets/scripts/bootstrap-notify-init.js"></script>
<script src="<?php echo base_url() ?>assets/scripts/datatables/js/jquery.dataTables.min.js"></script>
<script src="<?php echo base_url() ?>assets/scripts/datatables.init.js"></script>  


