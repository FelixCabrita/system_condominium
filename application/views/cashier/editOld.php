<?php
$id = $moduleInfo->id;
$name = $moduleInfo->name;
$code = $moduleInfo->code;  
$this->load->helper('form');
$readonly = "readonly";
if($permissions->U==1){
    $readonly = "";
}
?>
<div class="app-main__inner">  
    <div class="card form-card">
        <div class="card-body"> 
            <h4 class="card-title mb-4"> <i class="fa fa-users"></i> Modulo <br>
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
                if($success) { ?> 
                    <div class="alert alert-success alert-dismissible fade show">
                        <button type="button" class="close h-100" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span> </button> 
                        <?php echo $success; ?>              
                    </div>  
            <?php } ?>
            <?php if($permissions->U==1){ ?>
            <form class="form-inlne"  action="<?php echo base_url() ?>editando_modulos" method="post" id="edit" role="form"><?php } ?>
                <div class="row">
                    <div class="form-group col-sm-6">
                        <label for="code">Codigo</label>
                        <input type="text"  class="form-control mb-2 mr-sm-5" id="code"  name="code" value="<?php echo $code; ?>" <?php echo $readonly; ?> maxlength="10" > 
                        <input type="hidden" value="<?php echo $id; ?>" name="id" id="id" />    
                        <?php echo validation_error('name');?> 
                    </div>   
                    <div class="form-group col-sm-6">
                        <label for="name">Nombre</label>
                        <input type="text"  class="form-control mb-2 mr-sm-5" id="name"  name="name" value="<?php echo $name; ?>" <?php echo $readonly; ?> maxlength="200" > 
                        <input type="hidden" value="<?php echo $id; ?>" name="id" id="id" />    
                        <?php echo validation_error('name');?> 
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
<script src="<?php echo base_url(); ?>assets/scripts/system/editModule.js" type="text/javascript"></script>    