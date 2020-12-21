<div class="app-main__inner">  
    <div class="card form-card">
        <div class="card-body"> 
            <h4 class="card-title mb-4"> <i class="fa fa-users"></i> Edificios <br>
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
            <form class="form-inlne" id="add"  action="<?php echo base_url() ?>nuevo_edificio" method="post" role="form">
                <div class="row">
                    <div class="form-group col-sm-4">
                        <label for="edifice">Edificio</label>
                        <input type="text"  class="form-control mb-2 mr-sm-5"  autocomplete="nope" autocomplete="off"  id="edifice"  name="edifice" value="<?php echo set_value('edifice'); ?>"   maxlength="10" > 
                        <?php echo validation_error('edifice');?> 
                    </div> 
                    <div class="form-group col-sm-4">
                        <label for="block">Manzana</label>
                        <input type="text"  class="form-control mb-2 mr-sm-5" id="block"  name="block" value="<?php echo set_value('block'); ?>"   maxlength="10" > 
                        <?php echo validation_error('block');?> 
                    </div>  
                    <div class="form-group col-sm-4">
                        <label for="code">Codigo Unico(Manzana-Edificio)</label>
                        <input type="text"  class="form-control mb-2 mr-sm-5" id="code"  name="code" value="<?php echo set_value('code'); ?>" readonly > 
                        <?php echo validation_error('code');?> 
                    </div>  
                </div>  
                <div class="row">
                    <div class="form-group col-sm-6">
                        <label for="manager">Encargado</label>
                        <input type="text"  class="form-control mb-2 mr-sm-5" id="manager"  name="manager" value="<?php echo set_value('manager'); ?>"   maxlength="250" > 
                        <?php echo validation_error('manager');?> 
                    </div> 
                    <div class="form-group col-sm-6">
                        <label for="observation">Observacion</label>
                        <input type="text"  class="form-control mb-2 mr-sm-5" id="observation"  name="observation" value="<?php echo set_value('observation'); ?>"> 
                        <?php echo validation_error('observation');?> 
                    </div>  
                </div>  
                <div class="form-group">
                    <button type="submit" class="btn btn-primary btn-form">AGREGAR</button>
                </div>
                    
            </form>
        </div>
    </div> 
</div>  
<script src="<?php echo base_url(); ?>assets/scripts/system/addEdifice.js" type="text/javascript"></script>  