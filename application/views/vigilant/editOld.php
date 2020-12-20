<?php 
$id = $ApartmentInfo->id;
$apartment = $ApartmentInfo->apartment;
$conditions = $ApartmentInfo->conditions;   
$code_edifice = $ApartmentInfo->code_edifice;   
$number_parking = $ApartmentInfo->number_parking;   
$code = $ApartmentInfo->code;   
$edifice = $ApartmentInfo->edifice;   
$this->load->helper('form');
$readonly = "readonly";
if($permissions->U==1){
    $readonly = "";
}
?>
<div class="app-main__inner">  
    <div class="card form-card">
        <div class="card-body"> 
            <h4 class="card-title mb-4"> <i class="fa fa-users"></i> Apartamentos <br>
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
            <form class="form-inlne"  action="<?php echo base_url() ?>editando_apartamento" method="post" id="edit" role="form"><?php } ?>
                <div class="row">
                    <div class="form-group col-sm-4">
                        <label for="apartment">Apartamento</label>
                        <input type="text"  class="form-control mb-2 mr-sm-5"  name="apartment" value="<?php echo $apartment; ?>"  readonly >
                        <input type="hidden" value="<?php echo $id; ?>" name="id" id="id" />     
                    </div>
                    <div class="form-group col-sm-4">
                        <label for="edifice">Edificio</label>
                        <input type="text"   class="form-control mb-2 mr-sm-5"  name="edifice" value="<?php echo $edifice; ?>" readonly>  
                    </div>
                    <div class="form-group col-sm-4">
                        <label for="code">Codigo Unico(Edificio-Manzana) </label>
                        <input type="text"  class="form-control mb-2 mr-sm-5"   name="code" value="<?php echo $code; ?>" readonly > 
                    </div>
                </div>  
                <div class="row">
                    <div class="form-group col-sm-6">
                        <label for="number_parking">Numero de parqueo</label>
                        <input type="text"   class="form-control mb-2 mr-sm-5" id="number_parking"  name="number_parking" value="<?php echo $number_parking; ?>" <?php echo $readonly; ?> maxlength="250">
                        <?php echo validation_error('manager');?> 
                    </div> 
                    <div class="form-group col-sm-6">
                        <label for="conditions">Condicions</label>
                        <?php if(empty($readonly)){ ?>
                            <select name="conditions" id="conditions" class="form-control">
                                <?php for($i = 0;$i<COUNT(CONDITIONS);$i++){ ?>
                                    <option value ="<?php echo CONDITIONS[$i] ?>" <?php echo $conditions==CONDITIONS[$i]?"selected":"" ?>><?php echo CONDITIONS[$i] ?></option>
                                <?php }?> 
                            </select>
                            <?php echo validation_error('conditions');?> 
                        <?php }else{   ?> <input type="text"   class="form-control mb-2 mr-sm-5"  name="conditions" value="<?php echo $conditions; ?>" <?php echo $readonly; ?>>  <?php } ?>
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