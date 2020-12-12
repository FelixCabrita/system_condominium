<?php
$userId = $userInfo->id;
$name = $userInfo->name;
$email = $userInfo->username;   
$mobile = $userInfo->mobile;   
$type = $userInfo->type;   
$this->load->helper('form');
$readonly = "readonly";
if($permissions->U==1){
    $readonly = "";
}
?>
<div class="app-main__inner">  
    <div class="card form-card">
        <div class="card-body"> 
            <h4 class="card-title mb-4"> <i class="fa fa-users"></i> Usuarios de sistema <br>
            <?php if($permissions->U==1){ ?> <small> Agregar / <b>Editar</b></small></h4><?php } ?> 
            <?php
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
            <?php if($permissions->U==1){ ?>
            <form class="form-inlne"  action="<?php echo base_url() ?>editUser" method="post" id="editUser" role="form"><?php } ?>
                <div class="row">
                    <div class="form-group col-sm-6">
                        <label for="fname">Nombre completo</label>
                        <input type="text"  class="form-control mb-2 mr-sm-5" id="fname" placeholder="Ejemplo: Felix Cabrita" name="fname" value="<?php echo $name; ?>" <?php echo $readonly; ?> maxlength="128" >
                        <input type="hidden" value="<?php echo $userId; ?>" name="userId" id="userId" />    
                        <?php echo validation_error('fname');?> 
                    </div>
                    <div class="form-group col-sm-6">
                        <label for="type" >Tipo de usuario</label>
                        <select name="type" id="type" class="form-control">
                            <option value ="<?php echo ROLE_MANAGER ?>" <?php echo $type==ROLE_MANAGER?"selected":"" ?>>ADMINISTRADOR</option>
                            <option value ="<?php echo ROLE_VENDOR ?>"  <?php echo $type==ROLE_VENDOR?"selected":"" ?>>CAJA</option>  
                        </select>
                    </div> 
                </div>  
                <div class="row">
                    <div class="form-group col-sm-6">
                        <label for="email">Correo Electronico</label>
                        <input type="email"   class="form-control mb-2 mr-sm-5" id="email" placeholder="Ejemplo: soporte@gmail.com" name="email" value="<?php echo $email; ?>" <?php echo $readonly; ?> maxlength="100">
                        <?php echo validation_error('email');?> 
                    </div> 
                    <div class="form-group col-sm-6">
                        <label for="email">Telefono movil</label>
                        <input type="tel"   class="form-control mb-2 mr-sm-5" id="mobile" placeholder="Ejemplo: soporte@gmail.com" name="mobile" value="<?php echo $mobile; ?>" <?php echo $readonly; ?> maxlength="30">
                        <?php echo validation_error('mobile');?> 
                    </div> 
                </div> 
                <?php if($permissions->U==1){ ?>
                <div class="row">
                    <div class="form-group col-sm-6">
                        <label for="password">Contraseña</label>
                        <input type="text"  class="form-control mb-2 mr-sm-5"  id="password" placeholder="***************" name="password" maxlength="20">
                        <?php echo validation_error('password');?> 
                    </div> 
                    <div class="form-group col-sm-6">
                        <label for="password">Confirmar Contraseña</label>
                        <input type="text"  class="form-control mb-2 mr-sm-5"  id="cpassword" placeholder="***************" name="cpassword" maxlength="20">
                        <?php echo validation_error('cpassword');?> 
                    </div> 
                </div>  
                
                <div class="form-group">
                    <button type="submit" class="btn btn-primary btn-form">ACTUALIZAR</button>
                </div>
                
            </form> <?php } ?> 
        </div>
    </div>
</div> 
<script src="<?php echo base_url(); ?>assets/scripts/system/editUser.js" type="text/javascript"></script>    