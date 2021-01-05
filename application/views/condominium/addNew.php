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
                <div class="row">
                    <div class="form-group col-sm-4">
                        <label for="fname">Nombre</label>
                        <input type="text"  class="form-control mb-2 mr-sm-5" id="fname"  name="fname" value="<?php echo set_value('fname'); ?>"   maxlength="100" > 
                        <?php echo validation_error('fname');?> 
                    </div> 
                        
                    <div class="form-group col-sm-4">
                        <label for="address">Direccion</label>
                        <input type="text"   class="form-control mb-2 mr-sm-5" id="address"  value="<?php echo set_value('address'); ?>" name="address">
                        <?php echo validation_error('address');?> 
                    </div> 
                    <div class="form-group col-sm-4">
                        <label for="masive">Notificar mensualmente por correo deuda de clientes</label> 
                        <select name="masive" id="masive" class="form-control">
                            <option value ="0">NO</option> 
                            <option value ="1">SI</option> 
                        </select> 
                        <?php echo validation_error('masive');?>  
                    </div> 
                </div> 
                <div class="row">
                    <div class="form-group col-sm-4">
                        <label for="rcn">RCN</label>
                        <input type="text"  class="form-control mb-2 mr-sm-5" id="rcn"  name="rcn" value="<?php echo set_value('rcn'); ?>"   maxlength="100" > 
                        <?php echo validation_error('rcn');?> 
                    </div> 
                        
                    <div class="form-group col-sm-4">
                        <label for="mobile">Telefono</label>
                        <input type="tel"   class="form-control mb-2 mr-sm-5" id="mobile"  value="<?php echo set_value('mobile'); ?>" name="mobile" maxlength="100" > 
                        <?php echo validation_error('mobile');?> 
                    </div> 
                    <div class="form-group col-sm-4">
                        <label for="email">Correo electronico</label>
                        <input type="email"   class="form-control mb-2 mr-sm-5" id="email"  value="<?php echo set_value('email'); ?>" name="email" maxlength="100" > 
                        <?php echo validation_error('email');?> 
                    </div> 
                </div> 
                <div class="form-group">
                    <button type="submit" class="btn btn-primary btn-form">AGREGAR</button>
                </div>
                    
            </form>
        </div>
    </div> 
</div>  