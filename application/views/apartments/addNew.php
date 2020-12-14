<div class="app-main__inner">  
    <div class="card form-card">
        <div class="card-body"> 
            <h4 class="card-title mb-4"> <i class="fa fa-users"></i> Apartamentos <br>
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
            <form class="form-inlne" id="add"  action="<?php echo base_url() ?>nuevo_apartamento" method="post" role="form">
            <div class="row">
                    <div class="form-group col-sm-4">
                        <label for="apartment">Codigo Apartamento</label>
                        <input type="text"  class="form-control mb-2 mr-sm-5"  autocomplete="nope" autocomplete="off"  id="apartment"  name="apartment" value="<?php echo set_value('apartment'); ?>"   maxlength="10" > 
                        <?php echo validation_error('apartment');?> 
                    </div> 
                    <div class="form-group col-sm-4">
                        <label for="edifice">Edificio</label>
                        <select name="edifice" id="edifice" class="form-control">
                            <option value ="">SELECCIONE UN EDIFICIO</option>
                            <?php  if(!empty($edificeRecords)) { foreach($edificeRecords as $record) {  ?>
                                <option value ="<?php echo $record->code ?>" <?php echo set_value('edifice')==$record->code?"selected":"" ?>><?php echo $record->code." - ".$record->manager ?></option>
                            <?php }} ?> 
                        </select>
                        <?php echo validation_error('edifice');?> 
                    </div>  
                    <div class="form-group col-sm-4">
                        <label for="code">Codigo Unico (Edificio-Codigo Apartamento)</label>
                        <input type="text"  class="form-control mb-2 mr-sm-5"  readonly  id="code"  name="code" value="<?php echo set_value('code'); ?>"   maxlength="30" > 
                        <?php echo validation_error('code');?> 
                    </div>  
                </div>  
                <div class="row">
                    <div class="form-group col-sm-6">
                        <label for="number_parking">Numero de parqueo</label>
                        <input type="text"  class="form-control mb-2 mr-sm-5" id="number_parking"  name="number_parking" value="<?php echo set_value('number_parking'); ?>"   maxlength="10" > 
                        <?php echo validation_error('number_parking');?> 
                    </div> 
                    <div class="form-group col-sm-6">
                        <label for="conditions">Condicion</label>
                        <select name="conditions" id="conditions" class="form-control">
                            <?php for($i = 0;$i<COUNT(CONDITIONS);$i++){ ?>
                                <option value ="<?php echo CONDITIONS[$i] ?>" <?php echo set_value('conditions')==CONDITIONS[$i]?"selected":"" ?>><?php echo CONDITIONS[$i] ?></option>
                            <?php }?> 
                        </select>
                        <?php echo validation_error('conditions');?> 
                    </div>  
                </div>  
                <div class="form-group">
                    <button type="submit" class="btn btn-primary btn-form">AGREGAR</button>
                </div>
                    
            </form>
        </div>
    </div> 
</div>  
<script src="<?php echo base_url(); ?>assets/scripts/system/addApartment.js" type="text/javascript"></script>  