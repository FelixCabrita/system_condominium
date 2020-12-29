<div class="app-main__inner">  
    <div class="card form-card">
        <div class="card-body"> 
            <h4 class="card-title mb-4"> <i class="fa fa-users"></i> Empleados <br>
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
            <form class="form-inlne" id="add"  action="<?php echo base_url() ?>nuevo_empleados" method="post" role="form">
                <div class="row">
                    <div class="form-group col-sm-4"> 
                        <label for="apartment">Apartamento donde trabajara</label>
                        <?php if($rol <= ROLE_VIGILANT){ ?>
                            <select name="apartment" id="apartment" class="form-control">
                             <option value ="">SELECCIONE UN APARTAMENTO</option>
                             <?php  if(!empty($apartmentRecord)) { foreach($apartmentRecord as $record) {  ?>
                                 <option value ="<?php echo $record->id ?>"  ><?php echo $record->code
                                 ?></option>
                             <?php }} ?> 
                             </select> 
                         <?php }else{ ?>
                        <?php if($rol == ROLE_TENANT){   ?>
                            <?php  if(!empty($apartmentRecord)) { foreach($apartmentRecord as $record) {  ?>
                                <input type="text"  class="form-control mb-2 mr-sm-5" id="apartment"  name="apartment" value="<?php echo $record->edifice_code.$record->apartment_code ?>" readonly > 
                            <?php }} ?> 
                        <?php }else{ ?>
                             <select name="apartment" id="apartment" class="form-control">
                             <option value ="">SELECCIONE UN APARTAMENTO</option>
                             <?php  if(!empty($apartmentRecord)) { foreach($apartmentRecord as $record) {  ?>
                                 <option value ="<?php echo $record->apartment ?>"  ><?php echo $record->edifice_code.$record->apartment_code
                                 ?></option>
                             <?php }} ?> 
                             </select> 
                        <?php }} ?> 
                        <?php echo validation_error('apartment');?> 
                    </div>  
                    <div class="form-group col-sm-4">
                        <label for="document">Documento de identidad</label>
                        <input type="text"  class="form-control mb-2 mr-sm-5"  autocomplete="nope" autocomplete="off"  id="document"  name="document" value="<?php echo set_value('document'); ?>"   maxlength="20" > 
                        <?php echo validation_error('document');?> 
                    </div> 
                    <div class="form-group col-sm-4">
                        <label for="name">Nombre Completo</label>
                        <input type="text"  class="form-control mb-2 mr-sm-5" id="name"  name="name" value="<?php echo set_value('name'); ?>"   maxlength="250" > 
                        <?php echo validation_error('name');?> 
                    </div>  
                   
                </div>  
                <div class="row">
                    <div class="form-group col-sm-4">
                        <label for="mobile">Telefono movil</label>
                        <input type="tel"  class="form-control mb-2 mr-sm-5" id="mobile"  name="mobile" value="<?php echo set_value('mobile'); ?>" maxlength="50" > 
                        <?php echo validation_error('mobile');?> 
                    </div>  
                    <div class="form-group col-sm-4">
                        <label for="started_work">Inicio a trabajar</label>
                        <input type="date"  class="form-control mb-2 mr-sm-5" id="started_work"  name="started_work" value="<?php echo set_value('started_work'); ?>"> 
                        <?php echo validation_error('started_work');?> 
                    </div> 
                    <div class="form-group col-sm-4">
                        <label for="work_type" >Tipo de labor (maximo 20 letras)</label>
                        <input type="text"  class="form-control mb-2 mr-sm-5" id="work_type"  name="work_type" value="<?php echo set_value('work_type'); ?>"   maxlength="20" > 
                        <?php echo validation_error('work_type');?> 
                    </div> 
                </div>   
                <div class="form-group"> 
                    <button type="submit" class="btn btn-primary btn-form">AGREGAR</button>
                </div>
                    
            </form>
        </div>
    </div> 
</div>  
<script src="<?php echo base_url(); ?>assets/scripts/system/addEmployees.js" type="text/javascript"></script>  