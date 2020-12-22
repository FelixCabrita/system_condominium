<div class="app-main__inner">  
    <div class="card form-card">
        <div class="card-body"> 
            <h4 class="card-title mb-4"> <i class="fa fa-users"></i> Insidencias <br>
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
            <form class="form-inlne" id="add"  action="<?php echo base_url() ?>nuevo_insidencias" method="post" role="form" enctype="multipart/form-data">
                <?php if($rol>ROLE_VIGILANT){ ?> 
                    <div class="row">
                        <div class="form-group col-sm-4">
                            <label for="name">Quien la envia</label>
                            <input type="text"  class="form-control mb-2 mr-sm-5"  name="name" value="<?php echo $name ?>" readonly>  
                        </div> 
                        <div class="form-group col-sm-3">
                            <label for="type">TIPO USUARIO</label>
                            <input type="text"  class="form-control mb-2 mr-sm-5"  name="type" value="<?php echo checkRole($rol) ?>"  readonly>  
                        </div>  
                        <div class="form-group col-sm-5">
                            <label for="apartment">Apartamento donde reside (EDIFICIO - APARTAMENTO)</label>
                            <select name="apartment" id="apartment" class="form-control">  
                                <?php  if(!empty($apt_assignation)) { foreach($apt_assignation as $record) {  ?>
                                    <option value ="<?php echo $record->apartment ?>" ><?php echo $record->edifice_code." - ".$record->apartment_code ?></option>
                                <?php }} ?> 
                            </select>
                        </div>  
                    </div>  
                <?php }else{ ?>
                    <div class="row">
                        <div class="form-group col-sm-6">
                            <label for="name">Quien la envia</label>
                            <input type="text"  class="form-control mb-2 mr-sm-5"  name="name" value="<?php echo $name ?>"  readonly>  
                        </div> 
                        <div class="form-group col-sm-6">
                            <label for="type">Tipo de usuario</label>
                            <input type="text"  class="form-control mb-2 mr-sm-5"  name="type" value="<?php echo checkRole($rol) ?>"  readonly>  
                        </div>   
                    </div> 
                <?php } ?>
                <div class="row">
                    <div class="form-group col-sm-12">
                        <label for="description">Breve descripcion (Maximo 600 letras)</label>
                        <textarea maxlength ="600" name="description" id="description" class="form-control"></textarea>
                        <?php echo validation_error('description');?> 
                    </div>  
                </div>  
                <div class="row"> 
                    <div class="form-group col-sm-6">
                        <label for="file1" class="">Foto 1 (opcional)</label>
                        <input name="file1" id="file1" type="file" class="form-control-file"  accept="image/png, image/jpeg, image/jpg" aria-describedby="image">
                        <small class="form-text text-muted">Por favor sea lo mas explicito en la foto y evite cargar fotos borrosas.</small>
                    </div>  
                    <div class="form-group col-sm-6">
                        <label for="file2" class="">Foto 2 (opcional)</label>
                        <input name="file2" id="file2" type="file" class="form-control-file"  accept="image/png, image/jpeg, image/jpg" aria-describedby="image">
                        <small class="form-text text-muted">Por favor sea lo mas explicito en la foto y evite cargar fotos borrosas.</small>
                    </div>  
                </div>  
                <div class="form-group">
                    <button type="submit" class="btn btn-primary btn-form">AGREGAR</button>
                </div> 
            </form>
        </div>
    </div>
</div> 