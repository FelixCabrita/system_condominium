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
$position = $DirectiveInfo->position;   
$photo_1 = $DirectiveInfo->photo_1;
$this->load->helper('form');
$readonly = "readonly";
if($permissions->U==1){
    $readonly = "";
}
?>
<script>var apartment_select = "<?php echo $apartment ?>", edifice_select = "<?php echo $edifice ?>" ;</script>
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
            <form class="form-inlne"  action="<?php echo base_url() ?>editando_miembro" method="post" id="edit" role="form" enctype="multipart/form-data"><?php } ?> 
                
                <div class="row">
                    <div class="form-group col-sm-4">
                        <label for="document">Documento de identidad</label>
                        <div class="input-group"> 
                            <input type="text"  class="form-control "  value="<?php echo $document; ?>" autocomplete="nope" autocomplete="off"  id="document"  name="document"  maxlength="100" > 
                            <div class="input-group-prepend">
                                <a id ="search" class="btn btn-secondary"><i class="fa fa-search"></i></a>
                            </div>
                        </div> 
                        <input type="hidden" value="<?php echo $id; ?>" name="id" id="id" />        
                        <input type="hidden" value="<?php echo $photo_1; ?>" name="photo_1" id="photo_1" />     
                        <?php echo validation_error('document');?> 
                    </div> 
                    <div class="form-group col-sm-4">
                        <label for="name">Nombre</label>
                        <input type="text"  class="form-control mb-2 mr-sm-5"  autocomplete="nope" autocomplete="off"  id="name"  name="name"   value="<?php echo $name; ?>"  value="<?php echo $readonly ; ?>"   maxlength="250" > 
                        <?php echo validation_error('name');?> 
                    </div> 
                    <div class="form-group col-sm-4"> 
                        <label for="position">Puesto</label>
                        <?php if(empty($readonly)){ ?>
                            <select name="position" id="position" class="form-control">
                                <option value ="">SELECCIONE UN PUESTO</option>
                                <?php  for($x=0;$x<COUNT(DIRECTIVE_CHARGUE);$x++) {  ?>
                                    <option value ="<?php echo $x ?>" <?php echo $position==$x?"selected":"" ?>><?php echo DIRECTIVE_CHARGUE[$x] ?></option>
                                <?php  } ?> 
                            </select>  
                        <?php }else{ ?>  
                            <input type="text"  class="form-control mb-2 mr-sm-5"  autocomplete="nope" autocomplete="off"  id="position"  name="position"   value="<?php echo DIRECTIVE_CHARGUE[$position] ; ?>"  value="<?php echo $readonly ; ?>"   maxlength="250" > 
                         <?php }  ?>
                       
                        <?php echo validation_error('position');?> 
                    </div> 
                </div> 
                <?php if(empty($readonly)){ ?>
                    <div class="row"> 
                        <div class="form-group col-sm-4">
                            <label for="chargue">Cargo</label>
                            <input type="text"  class="form-control mb-2 mr-sm-5"  autocomplete="nope" autocomplete="off"  id="chargue"  name="chargue" value="<?php echo $chargue; ?>" maxlength="250" > 
                            <?php echo validation_error('name');?> 
                        </div> 
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
                    </div>
                    <div class="row"> 
                        <div class="form-group col-sm-6">
                            <label for="code">Periodo activo</label>  
                                <?php  if(!empty($periodRecords)) { foreach($periodRecords as $record) {  ?> 
                                    <input type="text"   class="form-control mb-2 mr-sm-5"  value="<?php echo  $record->name; ?>"  readonly  maxlength="250">
                                    <input type="hidden" value="<?php echo $record->id; ?>" name="period" id="period" />        
                                <?php }} ?> 
                         
                            <?php echo validation_error('period');?> 
                        </div> 
                        <div class="form-group col-sm-6">
                            <label for="file1" class="">Foto 1 (opcional) <a  href="<?php echo base_url()."assets/images/directive_photo/".$photo_1 ?>" target= "_blank">VER FOTO ACTUAL AQUI</a></label>
                            <input name="file1" id="file1" type="file" class="form-control-file"  accept="image/png, image/jpeg, image/jpg" aria-describedby="image">
                            <small class="form-text text-muted">Por favor sea lo mas explicito en la foto y evite cargar fotos borrosas.</small>
                        </div>    
                    </div>  
                <?php }else{   ?> 
                    <div class="row"> 
                        <div class="form-group col-sm-3">
                            <label for="chargue">Cargo</label>
                            <input type="text"  class="form-control mb-2 mr-sm-5"      <?php echo $readonly; ?> value="<?php echo $chargue; ?>" maxlength="250" > 
                        </div> 
                        <div class="form-group col-sm-3">
                            <label for="edifice">Edificio</label>
                            <input type="text"   class="form-control mb-2 mr-sm-5"   name="edifice" value="<?php echo $edifice_code; ?>" <?php echo $readonly; ?> maxlength="250">
                        </div>  
                        <div class="form-group col-sm-3">
                            <label for="apartment">Apartamento</label>
                            <input type="text"   class="form-control mb-2 mr-sm-5"   name="apartment" value="<?php echo $apartment_code; ?>" <?php echo $readonly; ?> maxlength="250">
                        </div>  
                        <div class="form-group col-sm-3">
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