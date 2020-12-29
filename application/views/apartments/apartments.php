<link href="<?php echo base_url() ?>assets/scripts/datatables/css/jquery.dataTables.min.css" rel="stylesheet"> 

<div class="app-main__inner"> 
    <div class="card">
        <div class="card-body">
        <div class="row page-titles" style = "background: rgba(255, 255, 255, 0);margin-bottom:3rem">
                <div class="col-sm-6">
                    <h4>Listado de apartamentos</h4>
                </div>
                <?php if($permissions->C==1){ ?>
                    <div class="col-sm-3 "> 
                        <a href="#"  class="btn btn-primary btn-lg btn-block" data-toggle="modal" data-target="#importApartmentModal">
                            <span class="btn-icon-right"><i class="fa fa-upload"> </i> Importar apartamentos </span>
                        </a>  
                    </div>
                    <div class="col-sm-3 "> 
                        <button onclick ="window.location = 'agregareapartamento'" type="button" class="btn btn-primary btn-lg btn-block"><span class="btn-icon-right"><i class="fa fa-plus"> </i> Agregar nuevo apartamento </span></button> 
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
                        <th class="text-center">COD. UNICO</th>
                        <th class="text-center">CODIGO </th>
                        <th class="text-center">EDIFICIO</th> 
                        <th class="text-center">NUMERO DE PARQUEO</th>
                        <th class="text-center">CONDICION</th>
                        <th class="text-center">FECHA CREACION</th>
                        <th class="text-center">ACCIONES</th> 
                    </tr>
                </thead>
                <tbody> 
                    <?php
                    if(!empty($ApartmentRecords))
                    {
                        foreach($ApartmentRecords as $record)
                        {
                    ?>
                    <tr>  
                        <td class="text-center"style="vertical-align: middle;"><?php echo $record->code ?></td>
                        <td class="text-center" style="vertical-align: middle;"><?php echo $record->apartment ?></td>
                        <td class="text-center" style="vertical-align: middle;"><?php echo $record->edifice ?></td>
                        <td class="text-center" style="vertical-align: middle;"><?php echo  $record->number_parking ?></td>
                        <td class="text-center" style="vertical-align: middle;"><?php echo  $record->conditions ?></td>
                        <td class="text-center" style="vertical-align: middle;"><?php echo  date_format (new DateTime($record->created_at), 'Y-m-d g:i A')  ?></td>
                        <td class="text-center" style="vertical-align: middle;">
                            <?php if($permissions->U==1){ ?><a class="btn btn-sm btn-info" href="<?php echo base_url().'editarapartamento/'.$record->id; ?>" title="Editar"><i class="fa fa-paint-brush"></i></a> <?php }else { ?> <a class="btn btn-sm btn-info" href="<?php echo base_url().'editarapartamento/'.$record->id; ?>" title="VER INFORMACION"><i class="fa fa-info"></i></a>  <?php } ?>
                            <?php if($permissions->D==1){ ?> <a class="btn btn-sm btn-danger deleteApartment" href="#" data-userid="<?php echo $record->id; ?>" data-name="<?php echo $record->code ?>" title="Eliminar"><i class="fa fa-trash"></i></a>  <?php } ?>
                        </td>
                    </tr>
                    <?php
                        }
                    }
                    ?>
                </tbody>
                <tfoot>
                    <tr> 
                        <th class="text-center">COD. UNICO</th> 
                        <th class="text-center">CODIGO </th> 
                        <th class="text-center">EDIFICIO</th> 
                        <th class="text-center">NUMERO DE PARQUEO</th> 
                        <th class="text-center">CONDICION</th>
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


