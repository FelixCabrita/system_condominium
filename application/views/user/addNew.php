<div class="app-main__inner">  
    <div class="card form-card">
        <div class="card-body"> 
            <h4 class="card-title mb-4"> <i class="fa fa-users"></i> Usuarios de sistema <br>
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
            <form class="form-inlne" id="addUser" action="<?php echo base_url() ?>addNewUser" method="post" role="form">
                <div class="row">
                    <div class="form-group col-sm-6">
                        <label for="fname">Nombre completo</label>
                        <input type="text"  class="form-control mb-2 mr-sm-5" id="fname"  name="fname" value="<?php echo set_value('fname'); ?>"   maxlength="128" > 
                        <?php echo validation_error('fname');?> 
                    </div> 
                    <div class="form-group col-sm-6">
                        <label for="type" >Tipo de usuario</label>
                        <select name="type" id="type" class="form-control">
                            <option value ="<?php echo ROLE_MANAGER ?>" <?php echo set_value('type')==ROLE_MANAGER?"selected":"" ?>>ADMINISTRADOR</option>
                            <option value ="<?php echo ROLE_VENDOR ?>"  <?php echo set_value('type')==ROLE_VENDOR?"selected":"" ?>>CAJA</option>  
                            <option value ="<?php echo ROLE_ACCOUNTING ?>" <?php echo set_value('type')==ROLE_ACCOUNTING?"selected":"" ?>>CONTABILIDAD</option>
                            <option value ="<?php echo ROLE_VIGILANT ?>"  <?php echo set_value('type')==ROLE_VIGILANT?"selected":"" ?>>VIGILANTE</option>  
                        </select>
                    </div> 
                </div>  
                <div class="row">
                    <div class="form-group col-sm-6">
                        <label for="email">Correo Electronico</label>
                        <input type="email"   class="form-control mb-2 mr-sm-5" id="email"  value="<?php echo set_value('email'); ?>"  name="email"   maxlength="100">
                        <?php echo validation_error('email');?> 
                    </div> 
                    <div class="form-group col-sm-6">
                        <label for="email">Telefono movil</label>
                        <input type="tel"   class="form-control mb-2 mr-sm-5" id="mobile" value="<?php echo set_value('mobile'); ?>"  name="mobile"   maxlength="30">
                        <?php echo validation_error('mobile');?> 
                    </div> 
                </div>  
                <div class="row">
                    <div class="form-group col-sm-6">
                        <label for="password">Contraseña</label>
                        <input type="password"  class="form-control mb-2 mr-sm-5"  id="password" placeholder="***************" name="password" maxlength="20">
                        <?php echo validation_error('password');?> 
                    </div> 
                    <div class="form-group col-sm-6">
                        <label   for="password">Confirmar Contraseña</label>
                        <input type="password"  class="form-control mb-2 mr-sm-5"  id="cpassword" placeholder= "***************" name="cpassword" maxlength="20">
                        <?php echo validation_error('cpassword');?> 
                    </div> 
                </div>  
                <div class="form-group">
                    <button type="submit" class="btn btn-primary btn-form">AGREGAR</button>
                </div>
                    
            </form>
        </div>
    </div> 
</div>  
<script src="<?php echo base_url(); ?>assets/scripts/system/addUser.js" type="text/javascript"></script>  