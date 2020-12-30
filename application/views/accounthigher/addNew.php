<div class="app-main__inner">  
    <div class="card form-card">
        <div class="card-body"> 
            <h4 class="card-title mb-4"> <i class="fa fa-users"></i> Cuenta Mayor <br>
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
            <form class="form-inlne" id="add"  action="<?php echo base_url() ?>nuevo_cuenta_mayor" method="post" role="form">
               
                <div class="row">
                    <div class="form-group col-sm-4">
                        <label for="accountcontrol">Cuenta Control</label>
                        <select name="accountcontrol" id="accountcontrol" class="form-control">  
                            <?php  if(!empty($accountcontrolRecords)) { foreach($accountcontrolRecords as $record) {  ?>
                                <option value ="<?php echo $record->id ?>" ><?php echo $record->account." - ".$record->name ?></option>
                            <?php }} ?> 
                        </select>
                    </div>  
                    <div class="form-group col-sm-4">
                        <label for="code">Codigo</label>
                        <input type="text"  class="form-control mb-2 mr-sm-5" id="code"  name="code" value="<?php echo set_value('code'); ?>"   maxlength="10" > 
                        <?php echo validation_error('code');?> 
                    </div>   
                    <div class="form-group col-sm-4">
                        <label for="name">Nombre</label>
                        <input type="text"  class="form-control mb-2 mr-sm-5" id="name"  name="name" value="<?php echo set_value('name'); ?>"   maxlength="200" > 
                        <?php echo validation_error('name');?> 
                    </div>   
                </div>  
                <div class="form-group">
                    <button type="submit" class="btn btn-primary btn-form">AGREGAR</button>
                </div> 
            </form>
        </div>
    </div> 
</div>  
<script src="<?php echo base_url(); ?>assets/scripts/system/addAccountHigher.js" type="text/javascript"></script>  