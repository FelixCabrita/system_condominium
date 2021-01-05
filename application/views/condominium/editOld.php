<?php
$id = $condominiumInfo->id;
$name = $condominiumInfo->name;
$address = $condominiumInfo->address;   
$mobile = $condominiumInfo->mobile;
$rcn = $condominiumInfo->rcn;    
$email = $condominiumInfo->email;   
 
$masive = $condominiumInfo->masive;   

$this->load->helper('form');
$readonly = "readonly";
if($permissions->U==1){
    $readonly = "";
}
?>
<div class="app-main__inner">  
    <div class="card form-card">
        <div class="card-body"> 
            <h4 class="card-title mb-4"> <i class="fa fa-users"></i> Condominios <br>
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
            <form class="form-inlne"  action="<?php echo base_url() ?>editando_condominio" method="post" id="editando_condominio" role="form"><?php } ?>
                <div class="row">
                    <div class="form-group col-sm-4">
                        <label for="fname">Nombre</label>
                        <input type="text"  class="form-control mb-2 mr-sm-5" id="fname"  name="fname" value="<?php echo $name; ?>" <?php echo $readonly; ?> maxlength="100" >
                        <input type="hidden" value="<?php echo $id; ?>" name="id" id="id" />    
                        <?php echo validation_error('fname');?> 
                    </div> 
                    <div class="form-group col-sm-4">
                        <label for="address">Direccion</label>
                        <input type="text"   class="form-control mb-2 mr-sm-5" id="address"   name="address" value="<?php echo $address; ?>" <?php echo $readonly; ?>>
                        <?php echo validation_error('address');?> 
                    </div> 

                    <div class="form-group col-sm-4">
                        <label for="masive">Notificar mensualmente por correo deuda de clientes</label> 
                        <select name="masive" id="masive" class="form-control">
                            <option value ="0" <?php echo  $masive==0?"SELECTED":"" ?>>NO</option> 
                            <option value ="1" <?php echo  $masive==1?"SELECTED":"" ?>>SI</option> 
                        </select> 
                        <?php echo validation_error('masive');?>  
                    </div> 
                </div> 
                <div class="row">
                    <div class="form-group col-sm-4">
                        <label for="rcn">RCN</label>
                        <input type="text"  class="form-control mb-2 mr-sm-5" id="rcn"  name="rcn" value="<?php echo $rcn; ?>" <?php echo $readonly; ?>   maxlength="100" > 
                        <?php echo validation_error('rcn');?> 
                    </div> 
                        
                    <div class="form-group col-sm-4">
                        <label for="mobile">Telefono</label>
                        <input type="tel"   class="form-control mb-2 mr-sm-5" id="mobile"  value="<?php echo $mobile; ?>" <?php echo $readonly; ?> name="mobile" maxlength="100" > 
                        <?php echo validation_error('mobile');?> 
                    </div> 
                    <div class="form-group col-sm-4">
                        <label for="email">Correo electronico</label>
                        <input type="email"   class="form-control mb-2 mr-sm-5" id="email"  value="<?php echo $email; ?>" <?php echo $readonly; ?> name="email" maxlength="100" > 
                        <?php echo validation_error('email');?> 
                    </div> 
                </div> 

             
                <?php if($permissions->U==1){ ?>
                
                <div class="form-group">
                    <button type="submit" class="btn btn-primary btn-form">ACTUALIZAR</button>
                </div>
                
            </form> <?php } ?> 
        </div>
    </div>
</div> 