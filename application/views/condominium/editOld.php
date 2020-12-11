<?php
$id = $condominiumInfo->id;
$name = $condominiumInfo->name;
$address = $condominiumInfo->address;    
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
               
                    <div class="form-group">
                        <label for="fname">Nombre</label>
                        <input type="text"  class="form-control mb-2 mr-sm-5" id="fname"  name="fname" value="<?php echo $name; ?>" <?php echo $readonly; ?> maxlength="100" >
                        <input type="hidden" value="<?php echo $id; ?>" name="id" id="id" />    
                        <?php echo validation_error('fname');?> 
                    </div> 
                    <div class="form-group">
                        <label for="address">Direccion</label>
                        <input type="text"   class="form-control mb-2 mr-sm-5" id="address"   name="address" value="<?php echo $address; ?>" <?php echo $readonly; ?>>
                        <?php echo validation_error('address');?> 
                    </div> 
                   
                <?php if($permissions->U==1){ ?>
                
                <div class="form-group">
                    <button type="submit" class="btn btn-primary btn-form">ACTUALIZAR</button>
                </div>
                
            </form> <?php } ?> 
        </div>
    </div>
</div>
<script src="<?php echo base_url(); ?>assets/scripts/jquery-3.5.1.min.js"></script>  
<script src="<?php echo base_url(); ?>assets/scripts/system/editUser.js" type="text/javascript"></script>    