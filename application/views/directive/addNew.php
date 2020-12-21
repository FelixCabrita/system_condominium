<div class="app-main__inner">  
    <div class="card form-card">
        <div class="card-body"> 
            <h4 class="card-title mb-4"> <i class="fa fa-users"></i> Miembros de la junta directiva <br>
                <small> <b>Agregar</b> / Editar</small></h4>
            <?php
                $this->load->helper('form');
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
            <form class="form-inlne" id="add"  action="<?php echo base_url() ?>nuevo_miembro" method="post" role="form">
                <div class="row">
                    <div class="form-group col-sm-4">
                        <label for="document">Documento de identidad</label>
                        <input type="text"  class="form-control mb-2 mr-sm-5"  autocomplete="nope" autocomplete="off"  id="document"  name="document" value="<?php echo set_value('document'); ?>"   maxlength="50" > 
                        <?php echo validation_error('document');?> 
                    </div> 
                    <div class="form-group col-sm-4">
                        <label for="name">Nombre</label>
                        <input type="text"  class="form-control mb-2 mr-sm-5"  autocomplete="nope" autocomplete="off"  id="name"  name="name" value="<?php echo set_value('name'); ?>"   maxlength="250" > 
                        <?php echo validation_error('name');?> 
                    </div> 
                    <div class="form-group col-sm-4">
                        <label for="chargue">Cargo</label>
                        <select name="chargue" id="chargue" class="form-control">
                            <option value ="">SELECCIONE UN PERIODO</option>
                             <?php  for($x=0;$x<COUNT(DIRECTIVE_CHARGUE);$x++) {  ?>
                                <option value ="<?php echo $x ?>" <?php echo set_value('chargue')==$x?"selected":"" ?>><?php echo DIRECTIVE_CHARGUE[$x] ?></option>
                            <?php  } ?> 
                        </select> 
                        <?php echo validation_error('chargue');?> 
                    </div> 
                </div>  
                <div class="row"> 
                    <div class="form-group col-sm-4">
                        <label for="edifice">Edificio</label>
                        <select name="edifice" id="edifice" class="form-control">
                            <option value ="">SELECCIONE UN EDIFICIO</option>
                            <?php  if(!empty($edificeRecords)) { foreach($edificeRecords as $record) {  ?>
                                <option value ="<?php echo $record->id ?>" <?php echo set_value('edifice')==$record->code?"selected":"" ?>><?php echo $record->code." - ".$record->manager ?></option>
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
                                <option value ="<?php echo $record->id ?>" <?php echo set_value('period')==$record->id?"selected":"" ?>><?php echo $record->name ?></option>
                            <?php }} ?> 
                        </select> 
                        <?php echo validation_error('period');?> 
                    </div>  
                </div> 
                <div class="form-group">
                    <button type="submit" class="btn btn-primary btn-form">AGREGAR</button>
                </div>
                    
            </form>
        </div>
    </div> 
</div>  
<script src="<?php echo base_url(); ?>assets/scripts/system/addDirective.js" type="text/javascript"></script>  