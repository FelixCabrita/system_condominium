<div class="app-main__inner">  
    <div class="card form-card">
        <div class="card-body"> 
            <h4 class="card-title mb-4"> <i class="fa fa-users"></i> Condominios <br>
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
            <form class="form-inlne" id="addCondominium" action="<?php echo base_url() ?>nuevo_condominio" method="post" role="form">
                
                <div class="form-group ">
                    <label for="fname">Nombre</label>
                    <input type="text"  class="form-control mb-2 mr-sm-5" id="fname" placeholder="Ejemplo: Felix Cabrita" name="fname" value="<?php echo set_value('fname'); ?>"   maxlength="100" > 
                    <?php echo validation_error('fname');?> 
                </div> 
                    
                <div class="form-group ">
                    <label for="address">Direccion</label>
                    <input type="text"   class="form-control mb-2 mr-sm-5" id="address"  value="<?php echo set_value('address'); ?>" name="address">
                    <?php echo validation_error('address');?> 
                </div> 
                   
                <div class="form-group">
                    <button type="submit" class="btn btn-primary btn-form">AGREGAR</button>
                </div>
                    
            </form>
        </div>
    </div> 
</div>  