<?php
//`username`, `password`, `permissions`, `name`, `status`,
$id = $userInfo->id;
$username = $userInfo->username; 
$permissions = $userInfo->permissions;  
$name = $userInfo->name; 
 

$this->load->helper('form');
?>
 
<div class="app-main__inner">   
    <div class="card">
        <div class="card-body">
            <div class="mb-5">
                <h4 class="card-title card-intro-title">Mi perfil</h4>
                <p>Si no desea cambiar la contraseña dejelo vacio</p>
            </div>
            
            <!-- Nav tabs -->
            <div class="default-tab">
                <ul class="nav nav-tabs mb-4" role="tablist">
                    <li class="nav-item "><a class="nav-link <?php echo ($active == "details")? "active show" : "" ?>" href="#details" data-toggle="tab">Detalle</a></li>
                    <li class="nav-item  "><a class="nav-link <?php echo ($active == "changepass")? "active show" : "" ?> "href="#changepass" data-toggle="tab">Cambio de Clave</a></li>
                </ul>
                <div class="tab-content tab-content-default"> 
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

                    <div class="tab-pane fade <?php echo ($active == "details")? "active show" : "" ?>" id="details" role="tabpanel">
                        <form  class="user-info-setting-form"  action="<?php echo base_url() ?>profileUpdate" method="post" id="editProfile" role="form">
                            <div class="form-group">
                                <label class="text-label">Nombre completo</label>
                                <input type="text" class="form-control" id="name" name="name" placeholder="<?php echo $name; ?>" value="<?php echo set_value('name', $name); ?>" maxlength="128"   >
                                <input type="hidden" value="<?php echo $id; ?>" name="id" id="id" />    
                                <?php echo validation_error('name');?> 
                            </div>
                            <div class="form-group">
                                <label class="text-label">Correo electronico</label>
                                <input type="email" class="form-control"  id="username" name="username" placeholder="<?php echo $username; ?>" value="<?php echo set_value('username', $username); ?>">
                                <?php echo validation_error('username');?> 
                            </div>  
                            <div class="submit-buttons mb-5">
                                <button type="submit" class="btn btn-card btn-success">Actualizar</button>
                                <button type="submit" class="btn btn-card btn-primary">Cancelar</button>
                            </div>
                        </form>
                    </div>
                    <div class="tab-pane fade <?php echo ($active == "changepass")? "active show" : "" ?>" id="changepass">
                        <div>
                            <form  class="user-info-setting-form"  action="<?php echo base_url() ?>changePassword" method="post" id="editProfile" role="form">
                                    
                                <div class="form-group">
                                    <label class="text-label">Contraseña anterior</label>
                                    <input type="password" class="form-control" placeholder="******"  id="inputOldPassword"  name="oldPassword" maxlength="20" required>
                                    <?php echo validation_error('oldPassword');?> 
                                </div>
                                <div class="form-group">
                                    <label class="text-label">Nueva contraseña</label>
                                    <input type="password" class="form-control" placeholder="******"  id="inputPassword1"   name="newPassword" maxlength="20" required>
                                    <?php echo validation_error('newPassword');?> 
                                </div>
                                <div class="form-group">
                                    <label class="text-label">Confirmar contraseña</label>
                                    <input type="password" class="form-control" placeholder="******" id="inputPassword2"  name="cNewPassword" maxlength="20" required>
                                    <?php echo validation_error('cNewPassword');?> 
                                </div> 
                                <div class="submit-buttons mb-5">
                                    <button type="submit" class="btn btn-card btn-success">Actualizar</button>
                                    <button type="submit" class="btn btn-card btn-primary">Cancelar</button>
                                </div>
                            </form>
                        </div>
                    </div> 
                </div>
            </div>
        </div>
    </div> 
</div> 
  
<script src="<?php echo base_url(); ?>assets/scripts/system/editUser.js" type="text/javascript"></script>   