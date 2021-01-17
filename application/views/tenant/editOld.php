<?php
$id = $TenantInfo->id;
$document = $TenantInfo->document;
$name = $TenantInfo->name;   
$mobile = $TenantInfo->mobile;   
$landline_phone = $TenantInfo->landline_phone;   
$email = $TenantInfo->email;   
$type = $TenantInfo->type; 
$user = empty(!$TenantInfo->password); 
$this->load->helper('form');
$readonly = "readonly";
if($permissions->U==1){
    $readonly = "";
}
?>
<div class="app-main__inner">  
    <div class="card form-card">
        <div class="card-body"> 
            <h4 class="card-title mb-4"> <i class="fa fa-users"></i> clientes <br>
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
            <form class="form-inlne"  action="<?php echo base_url() ?>editando_clientes" method="post" id="edit" role="form"><?php } ?>
                <div class="row">
                    <div class="form-group col-sm-4">
                        <label for="document">Documento de identidad</label>
                        <input type="text"  class="form-control mb-2 mr-sm-5"  autocomplete="nope" autocomplete="off"  id="document"  name="document" value="<?php echo $document; ?>" <?php echo $readonly; ?>  maxlength="50" > 
                        <input type="hidden" value="<?php echo $id; ?>" name="id" id="id" />  
                        <?php echo validation_error('edifice');?> 
                    </div> 
                    <div class="form-group col-sm-4">
                        <label for="name">Nombre Completo</label>
                        <input type="text"  class="form-control mb-2 mr-sm-5" id="name"  name="name"   value="<?php echo $name; ?>" <?php echo $readonly; ?>   maxlength="250" > 
                        <?php echo validation_error('name');?> 
                    </div>   
                    <div class="form-group col-sm-4">
                        <label for="type" >Tipo de usuario</label>
                        <select name="type" id="type" class="form-control">
                            <option value ="<?php echo ROLE_OWNER ?>" <?php echo $type==ROLE_OWNER?"selected":"" ?>>PROPIETARIO</option>
                            <option value ="<?php echo ROLE_TENANT ?>"  <?php echo $type==ROLE_TENANT?"selected":"" ?>>INQUILINO</option>   
                        </select>
                    </div> 
                </div>  
                <div class="row">
                    <div class="form-group col-sm-4">
                        <label for="mobile">Telefono movil</label>
                        <input type="tel"  class="form-control mb-2 mr-sm-5" id="mobile"  name="mobile"  value="<?php echo $mobile; ?>" <?php echo $readonly; ?>  maxlength="50" > 
                        <?php echo validation_error('mobile');?> 
                    </div>  
                    <div class="form-group col-sm-4">
                        <label for="landline_phone">Telefono Residencia</label>
                        <input type="tel"  class="form-control mb-2 mr-sm-5" id="landline_phone"  name="landline_phone"  value="<?php echo $landline_phone; ?>" <?php echo $readonly; ?>   maxlength="50" > 
                        <?php echo validation_error('landline_phone');?> 
                    </div> 
                    <div class="form-group col-sm-4">
                        <label for="email">Correo electronico</label>
                        <input type="email"  class="form-control mb-2 mr-sm-5" id="email"  name="email"   value="<?php echo $email; ?>" <?php echo $readonly; ?>  maxlength="100"> 
                        <?php echo validation_error('email');?> 
                    </div>  
                </div>
                <div class="form-group">
                <?php if($parameter<$max_user_tenant || $user ){ ?>
              <div class="position-relative form-check"><label class="form-check-label"><input type="checkbox" <?php echo $user?'checked':'' ?> <?php echo $readonly; ?>   id="user"  name="user" class="form-check-input"> Generarle usuario para el sistema de clientes (Si lo deshabilita el usuario ya no tendra acceso) </label></div><?php } ?> 
          </div>  
          <label><b>NOTA: Para el acceso del sistema del inquilinios sera: Documento de identidad y una clave generada enviada al correo electronico.</b></label>
                <?php if($permissions->U==1){ ?> 
                <div class="form-group">
                    <button type="submit" class="btn btn-primary btn-form">ACTUALIZAR</button>
                </div>
                
            </form> <?php } ?> 
        </div>
    </div>
</div> 
<script src="<?php echo base_url(); ?>assets/scripts/system/editTenant.js" type="text/javascript"></script>    