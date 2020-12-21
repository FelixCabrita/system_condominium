<?php 
 
$id = $DirectiveInfo->id;
$apartment = $DirectiveInfo->apartment;
$name = $DirectiveInfo->name;
$apartment_code = $DirectiveInfo->apartment_code;   
$edifice = $DirectiveInfo->edifice;   
$edifice_code = $DirectiveInfo->edifice_code;   
$document = $DirectiveInfo->document;   
$chargue = $DirectiveInfo->chargue;   
$period = $DirectiveInfo->period;   
$this->load->helper('form');
$readonly = "readonly";
if($permissions->U==1){
    $readonly = "";
}
?>

<div class="app-main__inner">  
    <div class="card form-card">
        <div class="card-body"> 
            <h4 class="card-title mb-4"> <i class="fa fa-users"></i> Miembros de la junta directiva <br>
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
            <form class="form-inlne"  action="<?php echo base_url() ?>editando_miembro" method="post" id="edit" role="form"><?php } ?>
                
                <div class="row">
                    <div class="form-group col-sm-4">
                        <label for="document">Documento de identidad</label>
                        <input type="text"  class="form-control mb-2 mr-sm-5"  autocomplete="nope" autocomplete="off"  id="document"  name="document"  value="<?php echo $document; ?>"   value="<?php echo $readonly ; ?>"    maxlength="50" > 
                        <input type="hidden" value="<?php echo $id; ?>" name="id" id="id" />     
                        <?php echo validation_error('document');?> 
                    </div> 
                    <div class="form-group col-sm-4">
                        <label for="name">Nombre</label>
                        <input type="text"  class="form-control mb-2 mr-sm-5"  autocomplete="nope" autocomplete="off"  id="name"  name="name"   value="<?php echo $name; ?>"  value="<?php echo $readonly ; ?>"   maxlength="250" > 
                        <?php echo validation_error('name');?> 
                    </div> 
                    <div class="form-group col-sm-4"> 
                        <label for="chargue">Cargo</label>
                        <?php if(empty($readonly)){ ?>
                            <select name="chargue" id="chargue" class="form-control">
                                <option value ="">SELECCIONE UN PERIODO</option>
                                <?php  for($x=0;$x<COUNT(DIRECTIVE_CHARGUE);$x++) {  ?>
                                    <option value ="<?php echo $x ?>" <?php echo $chargue==$x?"selected":"" ?>><?php echo DIRECTIVE_CHARGUE[$x] ?></option>
                                <?php  } ?> 
                            </select>  
                        <?php }else{ ?>  
                            <input type="text"  class="form-control mb-2 mr-sm-5"  autocomplete="nope" autocomplete="off"  id="chargue"  name="chargue"   value="<?php echo DIRECTIVE_CHARGUE[$chargue] ; ?>"  value="<?php echo $readonly ; ?>"   maxlength="250" > 
                         <?php }  ?>
                       
                        <?php echo validation_error('chargue');?> 
                    </div> 
                </div> 
                <?php if(empty($readonly)){ ?>
                    <div class="row"> 
                        <div class="form-group col-sm-4">
                            <label for="edifice">Edificio</label>
                            <select name="edifice" id="edifice" class="form-control">
                                <option value ="">SELECCIONE UN EDIFICIO</option>
                                <?php  if(!empty($edificeRecords)) { foreach($edificeRecords as $record) {  ?>
                                    <option value ="<?php echo $record->id ?>" <?php echo $edifice==$record->id?"selected":"" ?>><?php echo $record->code." - ".$record->manager ?></option>
                                <?php }} ?> 
                            </select>
                            <?php echo validation_error('edifice');?> 
                        </div>  
                        <div class="form-group col-sm-4">
                            <label for="apartment">Apartamento</label>
                            <select name="apartment" id="apartment" class="form-control">
                                <option value ="">SELECCIONE UN APARTAMENTO</option>
                            </select>
                            <?php echo validation_error('apartment');?> 
                        </div>  
                        <div class="form-group col-sm-4">
                            <label for="code">Periodo</label>
                            <select name="period" id="period" class="form-control">
                                <option value ="">SELECCIONE UN PERIODO</option>
                                <?php  if(!empty($periodRecords)) { foreach($periodRecords as $record) {  ?>
                                    <option value ="<?php echo $record->id ?>" <?php echo $period==$record->id?"selected":"" ?>><?php echo $record->name ?></option>
                                <?php }} ?> 
                            </select> 
                            <?php echo validation_error('period');?> 
                        </div>  
                    </div>  
                <?php }else{   ?> 
                    <div class="row"> 
                        <div class="form-group col-sm-4">
                            <label for="edifice">Edificio</label>
                            <input type="text"   class="form-control mb-2 mr-sm-5"   name="edifice" value="<?php echo $edifice_code; ?>" <?php echo $readonly; ?> maxlength="250">
                        </div>  
                        <div class="form-group col-sm-4">
                            <label for="apartment">Apartamento</label>
                            <input type="text"   class="form-control mb-2 mr-sm-5"   name="apartment" value="<?php echo $apartment_code; ?>" <?php echo $readonly; ?> maxlength="250">
                        </div>  
                        <div class="form-group col-sm-4">
                            <label for="period">Periodo</label>
                            <input type="text"   class="form-control mb-2 mr-sm-5"  name="period" value="<?php echo $period; ?>" <?php echo $readonly; ?> maxlength="250">
                        </div>  
                    </div>  
                <?php } ?>
               
                <?php if($permissions->U==1){ ?> 
                <div class="form-group">
                    <button type="submit" class="btn btn-primary btn-form">ACTUALIZAR</button>
                </div>
                
            </form> <?php } ?> 
        </div>
    </div>
</div> 
<script>  var e= "<?php echo  $edifice; ?>",a="<?php echo  $apartment; ?>"; </script>   
<script def src="<?php echo base_url(); ?>assets/scripts/system/editDirective.js" type="text/javascript"></script>    