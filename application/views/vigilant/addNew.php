<div class="app-main__inner">  
    <div class="card form-card">
        <div class="card-body"> 
            <h4 class="card-title mb-4"> <i class="fa fa-users"></i> VISITANTE <br>
                <small> <b>Agregar visita</b> </small></h4>
                <div class="alert alert-danger alert-dismissible fade show alertNotAccess  ">
                    <button type="button" class="close h-100" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span> </button> 
                    <p id="textAlert"></p>
                </div> 
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
            <form class="form-inlne" id="add"  action="<?php echo base_url() ?>nuevo_visitante" method="post" role="form">
                <div class="row">
                    <div class="form-group col-sm-3">  
                        <label for="document">Documento de identidad</label>
                        <div class="input-group"> 
                            <input type="text"  class="form-control " value="<?php echo set_value('document'); ?>"  autocomplete="nope" autocomplete="off"  id="document"  name="document"  maxlength="100" > 
                            <div class="input-group-prepend">
                                <a id ="search" class="btn btn-secondary"><i class="fa fa-search"></i></a>
                            </div>
                        </div> 
                        <?php echo validation_error('document');?>  
                    </div>  
                    <div class="form-group col-sm-3">
                        <label for="name">Nombre Completo</label>
                        <input type="text"  class="form-control mb-2 mr-sm-5" value="<?php echo set_value('name'); ?>" autocomplete="nope" autocomplete="off"  id="name"  name="name" maxlength="250" > 
                        <?php echo validation_error('name');?> 
                    </div> 
                    <div class="form-group col-sm-2">
                        <label for="mobile">Telefono movil</label>
                        <input type="tel"  class="form-control mb-2 mr-sm-5"  value="<?php echo set_value('mobile'); ?>" autocomplete="nope" autocomplete="off"  id="mobile"  name="mobile" maxlength="100" > 
                        <?php echo validation_error('mobile');?> 
                    </div> 
                  
                    <div class="form-group col-sm-2">
                        <label for="licence_plate">Placa (opcion)</label>
                        <input type="text"  class="form-control mb-2 mr-sm-5"  value="<?php echo set_value('licence_plate'); ?>" autocomplete="nope" autocomplete="off"  id="licence_plate"  name="licence_plate" maxlength="20" > 
                        <?php echo validation_error('licence_plate');?> 
                    </div>  
                    <div class="form-group col-sm-2">
                        <label for="type">Tipo</label>
                        <input type="text"  class="form-control mb-2 mr-sm-5"  value="<?php echo set_value('type'); ?>" readonly  id="type"  name="type" maxlength="100" > 
                        <?php echo validation_error('mobile');?> 
                    </div>  
                </div>   
                <h5 for="apartment">  <small> <b>A donde se dirige o de donde viene</b> </small></h5>
                <div class="row">
                    <div class="form-group col-sm-6">
                        <label for="edifice">Edificio</label>
                        <select name="edifice" id="edifice" class="form-control">
                            <option value ="">SELECCIONE UN EDIFICIO</option>
                            <?php  if(!empty($edificeRecords)) { foreach($edificeRecords as $record) {  ?>
                                <option value ="<?php echo $record->id ?>"  ><?php echo $record->code." - ".$record->name ?></option>
                            <?php }} ?> 
                        </select> 
                        <?php echo validation_error('edifice');?> 
                    </div>  
                    <div class="form-group col-sm-6">
                        <label for="apartment">Apartamento</label>
                        <select name="apartment" id="apartment" class="form-control">
                            <option value ="">SELECCIONE UN EDIFICIO</option>
                        </select> 
                        <?php echo validation_error('apartment');?> 
                    </div>   
                </div>  
                <div class="row">
                    <div class="form-group col-sm-6">
                        <label for="observation">Observacion</label>
                        <input type="text" class="form-control mb-2 mr-sm-5"  autocomplete="nope" autocomplete="off"  id="observation"  name="observation" > 
                    </div>  
                    <div class="form-group col-sm-6">
                        <label for="status">Estado de la visita</label>
                        <select name="status" id="status" class="form-control">
                            <option value ="<?php echo VIGILANT_INPUT ?>">ENTRADA</option>
                            <option value ="<?php echo VIGILANT_EXIT ?>">SALIDA</option>
                        </select> 
                    </div>   
                </div>  
                <div class="form-group">
                    <button type="submit" class="btn btn-primary btn-form" id="send">REGISTRAR</button>
                </div>
                    
            </form>
        </div>
    </div> 
</div>  
<script src="<?php echo base_url(); ?>assets/scripts/system/addVigilant.js" type="text/javascript"></script>  