<div class="app-main__inner">  
    <div class="card form-card">
        <div class="card-body"> 
            <h4 class="card-title mb-4"> <i class="fa fa-users"></i> Etiquetas <br>
                <small> <b>Agregar</b> / Editar</small></h4>
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
            <form class="form-inlne" id="add"  action="<?php echo base_url() ?>nuevo_etiquetas" method="post" role="form"> 
                <div class="row"> 
                    <div class="form-group col-sm-4">
                        <label for="serial">Cantidad de etiquetas</label>
                        <input type="number"  class="form-control mb-2 mr-sm-5" id="serial"  name="serial" min = "1" value="1"  > 
                        <?php echo validation_error('name');?> 
                    </div>  
                    <div class="form-group col-sm-4">
                        <label for="edifice">Edificio</label>
                        <select name="edifice" id="edifice" class="form-control">
                            <option value ="">SELECCIONE UN EDIFICIO</option>
                            <?php  if(!empty($edificeRecords)) { foreach($edificeRecords as $record) {  ?>
                                <option value ="<?php echo $record->id ?>" <?php echo set_value('edifice')==$record->code?"selected":"" ?>><?php echo $record->code." - ".$record->manager ?></option>
                            <?php }} ?> 
                        </select>
                        <?php echo validation_error('edifice');?> 
                    </div>  
                    <div class="form-group col-sm-4">
                        <label for="apartment">Apartamento</label>
                        <select name="apartment" id="apartment" class="form-control">
                            <option value ="">SELECCIONE UN EDIFICIO</option>
                        </select>
                        <?php echo validation_error('apartment');?> 
                    </div>  
                </div>  
                <div class="form-group">
                    <button type="submit" class="btn btn-primary btn-form">AGREGAR</button>
                </div>
                    
            </form>
        </div>
    </div> 
</div>  
<script src="<?php echo base_url(); ?>assets/scripts/system/addTag.js" type="text/javascript"></script>  