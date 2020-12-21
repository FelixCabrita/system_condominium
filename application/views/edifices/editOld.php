<?php
$id = $EdificeInfo->id;
$edifice = $EdificeInfo->edifice;
$block = $EdificeInfo->block;   
$manager = $EdificeInfo->manager;   
$code = $EdificeInfo->code;   
$observation = $EdificeInfo->observation;   
$this->load->helper('form');
$readonly = "readonly";
if($permissions->U==1){
    $readonly = "";
}
?>
<div class="app-main__inner">  
    <div class="card form-card">
        <div class="card-body"> 
            <h4 class="card-title mb-4"> <i class="fa fa-users"></i> Edificios <br>
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
            <form class="form-inlne"  action="<?php echo base_url() ?>editando_edificio" method="post" id="edit" role="form"><?php } ?>
                <div class="row">
                    <div class="form-group col-sm-4">
                        <label for="fname">Edificio</label>
                        <input type="text"  class="form-control mb-2 mr-sm-5"  name="edifice" value="<?php echo $edifice; ?>"  readonly >
                        <input type="hidden" value="<?php echo $id; ?>" name="id" id="id" />     
                    </div>
                    <div class="form-group col-sm-4">
                        <label for="block">Manzana</label>
                        <input type="text"  class="form-control mb-2 mr-sm-5"   name="block" value="<?php echo $block; ?>" readonly > 
                    </div>
                    <div class="form-group col-sm-4">
                        <label for="block">Codigo Unico(Manzana-Edificio) </label>
                        <input type="text"  class="form-control mb-2 mr-sm-5"   name="code" value="<?php echo $code; ?>" readonly > 
                    </div>
                </div>  
                <div class="row">
                    <div class="form-group col-sm-6">
                        <label for="manager">Encargado</label>
                        <input type="text"   class="form-control mb-2 mr-sm-5" id="manager"  name="manager" value="<?php echo $manager; ?>" <?php echo $readonly; ?> maxlength="250">
                        <?php echo validation_error('manager');?> 
                    </div> 
                    <div class="form-group col-sm-6">
                        <label for="observation">Observation</label>
                        <input type="text"   class="form-control mb-2 mr-sm-5" id="observation"  name="observation" value="<?php echo $observation; ?>" <?php echo $readonly; ?>> 
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
<script src="<?php echo base_url(); ?>assets/scripts/system/editEdifice.js" type="text/javascript"></script>    