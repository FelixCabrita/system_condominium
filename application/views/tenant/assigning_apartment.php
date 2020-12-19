<link href="<?php echo base_url() ?>assets/scripts/datatables/css/jquery.dataTables.min.css" rel="stylesheet"> 
<?php
$id = $TenantInfo->id;
$document = $TenantInfo->document;
$name = $TenantInfo->name;    
$this->load->helper('form'); 
?>
<div class="app-main__inner"> 
    <div class="card">
        <div class="card-body">
            <div class="row page-titles" style = "background: rgba(255, 255, 255, 0);">
                <div class="col-sm-6">
                    <h4>Asignacion de departamentos</h4> 
                </div> 
                <div class="col-sm-6 text-center"> 
                    <h4><b><?php echo "($document) ".$name ?></b> </h4> 
                </div> 
            </div>
            <hr>  
            <div class="row page-titles" style = "background: rgba(255, 255, 255, 0); "> 
                <div class="form-group col-sm-4">
                    <label for="edifice">Edificio</label>
                    <select name="edifice" id="edifice" class="form-control">
                        <option value ="">SELECCIONE UN EDIFICIO</option>
                        <?php  if(!empty($edificeRecords)) { foreach($edificeRecords as $record) {  ?>
                            <option value ="<?php echo $record->id ?>"  ><?php echo $record->code." - ".$record->manager ?></option>
                        <?php }} ?> 
                    </select> 
                </div>  
                <div class="form-group col-sm-3">
                    <label for="apartment">Apartamento</label>
                    <select name="apartment" id="apartment" class="form-control">
                        <option value ="">SELECCIONE UN EDIFICIO</option>
                    </select> 
                </div>  
                <div class="form-group col-sm-3">
                    <label for="date_start">Inicio de estancia</label>
                    <input type="date"  class="form-control mb-2 mr-sm-5" id="date_start"  name="date_start"  >  
                </div>  
                <div class="form-group col-sm-2 "> 
                     <a class="btn btn-sm btn-info set_asignation"  style="height: 100%;max-height:75;   min-height: 100%;    display: flex;align-items: center;" data-userid="<?php echo $id; ?>" data-name="<?php echo $name ?>"  href="#" title="ASIGNAR APARTAMENTO"><i class="fa fa-check"></i>&nbsp; ASIGNAR DE APARTAMENTO</a>
                </div>   
            </div>
            <hr>  
            <div class="table-responsive">
                <table id="example" class="display" style="min-width: 845px">
                <thead>
                    <tr> 
                        <th class="text-center">EDIFICIO </th>
                        <th class="text-center">APARTAMENTO</th>
                        <th class="text-center">FECHA ASIGNACION</th> 
                        <th class="text-center">ESTADO</th> 
                        <th class="text-center">ACCIONES</th>
                    </tr>
                </thead>
                <tbody> 
                    <?php if(!empty($AssignmentRecords)) { foreach($AssignmentRecords as $record) { ?>
                    <tr>  
                        <td class="text-center"style="vertical-align: middle;"><?php echo $record->edifice_code ?></td>
                        <td class="text-center" style="vertical-align: middle;"><?php echo $record->apartment_code ?></td>
                        <td class="text-center" style="vertical-align: middle;"><?php echo  $record->created_at ?></td>
                        <td class="text-center" style="vertical-align: middle;"><?php 
                        if($record->status==1){
                            echo '<span class="badge badge-success">VINCULADO</span>';
                        }else if($record->status==0){
                            echo '<span class="badge badge-danger">DESVINCULADO</span>';
                        } ?></td> 
                        <td class="text-center" style="vertical-align: middle;"> 
                            <?php if($record->status==0){ ?> <a class="btn btn-sm btn-info view_asignation" href="#" data-date="<?php echo $record->updated_at ?>"  data-observation="<?php echo $record->observation ?>" data-name="<?php echo $record->apartment_code ?>" title="Eliminar"><i class="fa fa-info"></i></a>  <?php }  ?>
                            
                            <?php if($permissions->D==1 && $record->status==1){ ?> <a class="btn btn-sm btn-danger delete_asignation" href="#" data-userid="<?php echo $record->id; ?>" data-name="<?php echo $record->apartment_code ?>" title="Eliminar"><i class="fa fa-trash"></i></a>  <?php } ?>
                        </td>
                    </tr>
                    <?php } } ?>
                </tbody>
                <tfoot>
                    <tr> 
                        <th class="text-center">EDIFICIO </th>
                        <th class="text-center">APARTAMENTO</th> 
                        <th class="text-center">FECHA ASIGNACION</th> 
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
<script>
$(document).ready(function() { 
    $("#edifice").change(function() {
        var stateID = $(this).val();
        if (stateID) {
            $.ajax({
                url: '../obtener_apartemantos/' + stateID,
                type: "GET",
                dataType: "json",
                success: function(data) { 
                    
                    $('select[name="apartment"]').empty();
                    $('select[name="apartment"]').append('<option value="">SELECCIONE UN APARTAMENTO</option>');
                    $.each(data, function(key, value) {
                        $('select[name="apartment"]').append('<option value="' + value.id + '">' + value.apartment + '</option>');
                    });
                }
            });
        } else {
            $('select[name="apartment"]').empty();
            $('select[name="apartment"]').append('<option value="">SELECCIONE UN EDIFICIO</option>');
        }
    }); 

    table
});
</script>  

