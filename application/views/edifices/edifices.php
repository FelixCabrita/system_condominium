<link href="<?php echo base_url() ?>assets/scripts/datatables/css/jquery.dataTables.min.css" rel="stylesheet">
<link href="<?php echo base_url() ?>assets/scripts/sweetalert2/dist/sweetalert2.min.css" rel="stylesheet">

<div class="app-main__inner"> 
    <div class="card">
        <div class="card-body">
        <div class="row page-titles" style = "background: rgba(255, 255, 255, 0);margin-bottom:3rem">
                <div class="col-sm-9">
                    <h4>Listado de edificios</h4>
                </div>
                <?php if($permissions->C==1){ ?>
                <div class="col-sm-3 ">
                    
                        <button onclick ="window.location = 'agregaredificio'" type="button" class="btn btn-primary btn-lg btn-block"><span class="btn-icon-right"><i class="fa fa-plus"> </i> Agregar nuevo edificio </span></button> 
                    
                </div>
                <?php } ?>
            </div>
            <div class="table-responsive">
                <table id="example" class="display" style="min-width: 845px">
                <thead>
                    <tr> 
                        <th class="text-center">CODIGO </th>
                        <th class="text-center">ENCARGADO</th>
                        <th class="text-center">OBSERVACION</th>
                        <th class="text-center">FECHA CREACION</th> 
                        <th class="text-center">ACCIONES</th>
                    </tr>
                </thead>
                <tbody> 
                    <?php
                    if(!empty($EdificeRecords))
                    {
                        foreach($EdificeRecords as $record)
                        {
                    ?>
                    <tr>  
                        <td class="text-center"style="vertical-align: middle;"><?php echo $record->code ?></td>
                        <td class="text-center" style="vertical-align: middle;"><?php echo $record->manager ?></td>
                        <td class="text-center" style="vertical-align: middle;"><?php echo  $record->observation ?></td>
                        <td class="text-center" style="vertical-align: middle;"><?php echo  $record->created_at  ?></td>
                         
                        <td class="text-center" style="vertical-align: middle;">
                           <!-- <?php if($permissions->U==1){ ?><a class="btn btn-sm btn-primary changeStatusEdifice"  href="#" data-titulo="<?php echo $record->code; ?>" data-isactive="<?php echo $record->status; ?>" data-userid="<?php echo $record->id; ?>" title="Cambio de estado"><i class="fa fa-eye"></i></a>-->
                            <a class="btn btn-sm btn-info" href="<?php echo base_url().'editaredificio/'.$record->id; ?>" title="Editar"><i class="fa fa-paint-brush"></i></a> <?php }else { ?> <a class="btn btn-sm btn-info" href="<?php echo base_url().'editaredificio/'.$record->id; ?>" title="VER INFORMACION"><i class="fa fa-info"></i></a>  <?php } ?>
                            <?php if($permissions->D==1){ ?> <a class="btn btn-sm btn-danger deleteEdifice" href="#" data-userid="<?php echo $record->id; ?>" data-name="<?php echo $record->code ?>" title="Eliminar"><i class="fa fa-trash"></i></a>  <?php } ?>
                        </td>
                    </tr>
                    <?php
                        }
                    }
                    ?>
                </tbody>
                <tfoot>
                    <tr> 
                        <th class="text-center">CODIGO </th>
                        <th class="text-center">ENCARGADO</th>
                        <th class="text-center">OBSERVACION</th>
                        <th class="text-center">FECHA CREACION</th> 
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


