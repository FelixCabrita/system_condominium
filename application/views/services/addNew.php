<div class="app-main__inner">  
    <div class="card form-card">
        <div class="card-body"> 
            <h4 class="card-title mb-4"> <i class="fa fa-users"></i> Servicios <br>
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
            <form class="form-inlne" id="add"  action="<?php echo base_url() ?>nuevo_servicio" method="post" role="form">
                <div class="row">
                    <div class="form-group col-sm-6">
                        <label for="code">Codigo</label>
                        <input type="text"  class="form-control mb-2 mr-sm-5"  autocomplete="nope" autocomplete="off"  id="code"  name="code" value="<?php echo set_value('edifice'); ?>"   maxlength="20" > 
                        <?php echo validation_error('code');?> 
                    </div> 
                    <div class="form-group col-sm-6">
                        <label for="name">Nombre</label>
                        <input type="text"  class="form-control mb-2 mr-sm-5" id="name"  name="name" value="<?php echo set_value('name'); ?>"   maxlength="250" > 
                        <?php echo validation_error('name');?> 
                    </div>   
                </div>  
                <div class="row">
                    <div class="form-group col-sm-6">
                        <label for="amount">Monto</label>
                        <input type="number"  class="form-control mb-2 mr-sm-5" id="amount" min= "0"  name="amount" value="<?php echo set_value('amount'); ?>"> 
                        <?php echo validation_error('amount');?> 
                    </div>  
                    <div class="form-group col-sm-6">
                        <label for="date_load">Fecha de carga</label>
                        <input type="date"  class="form-control mb-2 mr-sm-5" id="date_load"  name="date_load" value="<?php echo set_value('date_load'); ?>"> 
                        <?php echo validation_error('date_load');?> 
                    </div>  
                </div>  
                <div class="form-group">
                    <button type="submit" class="btn btn-primary btn-form">AGREGAR</button>
                </div>
                    
            </form>
        </div>
    </div> 
</div>  
<script src="<?php echo base_url(); ?>assets/scripts/system/addService.js" type="text/javascript"></script>  