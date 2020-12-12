<div class="app-main__inner">  
    <div class="card form-card">
        <div class="card-body"> 
            <h4 class="card-title mb-4"> <i class="fa fa-users"></i> Periodos <br>
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
            <form class="form-inlne" id="add"  autocomplete="nope" autocomplete="off" action="<?php echo base_url() ?>nuevo_periodo" method="post" role="form">
                <div class="row">
                    <div class="form-group col-sm-4">
                        <label for="name">Nombre</label>
                        <input type="text"  class="form-control mb-2 mr-sm-5"  id="name"  name="name" value="<?php echo set_value('name'); ?>"   maxlength="200" > 
                        <?php echo validation_error('name');?> 
                    </div> 
                    <div class="form-group col-sm-4">
                        <label for="start">Fecha inicial</label>
                        <input type="date"  class="form-control mb-2 mr-sm-5" id="start"  name="start" value="<?php echo set_value('start'); ?>"> 
                        <?php echo validation_error('block');?> 
                    </div>  
                    <div class="form-group col-sm-4">
                        <label for="end">Fecha final</label>
                        <input type="date"  class="form-control mb-2 mr-sm-5" id="end"  name="end" value="<?php echo set_value('end'); ?>" > 
                        <?php echo validation_error('end');?> 
                    </div>  
                </div>  
                <div class="form-group">
                    <button type="submit" class="btn btn-primary btn-form">AGREGAR</button>
                </div>
                    
            </form>
        </div>
    </div> 
</div>  
<script src="<?php echo base_url(); ?>assets/scripts/system/addPeriod.js" type="text/javascript"></script>  