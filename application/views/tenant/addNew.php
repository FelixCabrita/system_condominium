<div class="app-main__inner">  
    <div class="card form-card">
        <div class="card-body"> 
            <h4 class="card-title mb-4"> <i class="fa fa-users"></i> Inquilinos <br>
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
            <form class="form-inlne" id="add"  action="<?php echo base_url() ?>nuevo_inquilinos" method="post" role="form">
                <div class="row">
                    <div class="form-group col-sm-6">
                        <label for="document">Documento de identidad</label>
                        <input type="text"  class="form-control mb-2 mr-sm-5"  autocomplete="nope" autocomplete="off"  id="edifice"  name="document" value="<?php echo set_value('edifice'); ?>"   maxlength="20" > 
                        <?php echo validation_error('edifice');?> 
                    </div> 
                    <div class="form-group col-sm-6">
                        <label for="name">Nombre Completo</label>
                        <input type="text"  class="form-control mb-2 mr-sm-5" id="name"  name="name" value="<?php echo set_value('name'); ?>"   maxlength="250" > 
                        <?php echo validation_error('name');?> 
                    </div>   
                </div>  
                <div class="row">
                    <div class="form-group col-sm-4">
                        <label for="mobile">Telefono movil</label>
                        <input type="tel"  class="form-control mb-2 mr-sm-5" id="mobile"  name="mobile" value="<?php echo set_value('mobile'); ?>" maxlength="50" > 
                        <?php echo validation_error('mobile');?> 
                    </div>  
                    <div class="form-group col-sm-4">
                        <label for="landline_phone">Telefono Residencia</label>
                        <input type="tel"  class="form-control mb-2 mr-sm-5" id="landline_phone"  name="landline_phone" value="<?php echo set_value('landline_phone'); ?>"   maxlength="50" > 
                        <?php echo validation_error('landline_phone');?> 
                    </div> 
                    <div class="form-group col-sm-4">
                        <label for="email">Correo electronico</label>
                        <input type="email"  class="form-control mb-2 mr-sm-5" id="email"  name="email" value="<?php echo set_value('email'); ?>" maxlength="100"> 
                        <?php echo validation_error('email');?> 
                    </div>  
                </div>  
                <div class="form-group">
                    <button type="submit" class="btn btn-primary btn-form">AGREGAR</button>
                </div>
                    
            </form>
        </div>
    </div> 
</div>  
<script src="<?php echo base_url(); ?>assets/scripts/system/addTenant.js" type="text/javascript"></script>  