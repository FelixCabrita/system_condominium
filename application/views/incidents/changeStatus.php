<?php

/*
i.id, i.user,(CASE WHEN i.user_type < 6 THEN (SELECT u.name FROM users as u  WHERE u.id = i.user) 
                ELSE (SELECT CONCAT('(',t.document,') ',t.name) FROM tenant as t WHERE t.id = i.user)  END) as 'name', 
        i.user_type, i.description, i.photo_1, i.photo_2, i.status,apartment, i.observations, i.created_at, i.updated_at */
$id = $incidentsInfo->id;
$name = $incidentsInfo->name;
$apartment = $incidentsInfo->apartment;
$rol = $incidentsInfo->user_type;
$photo_1 = $incidentsInfo->photo_1;
$photo_2 = $incidentsInfo->photo_2;
$status = $incidentsInfo->status;
$description = $incidentsInfo->description;
$observations = $incidentsInfo->observations;
$created_at = $incidentsInfo->created_at;$updated_at = $incidentsInfo->updated_at;
$status_tag = "";
if($status == INCIDENTS_PENDING){
    $status_tag = "PENDIENTE";
}
if($status == INCIDENTS_IN_REVIEW){
    $status_tag = "EN REVISION";
} 

if($status == INCIDENTS_SOLVED){
    $status_tag = "SOLUCIONADO";
} 

$this->load->helper('form');  
?>
<div class="app-main__inner">  
    <div class="card form-card">
        <div class="card-body"> 
            <h4 class="card-title mb-4"> <i class="fa fa-users"></i> Insidencias <br>
            <small>   <b>Fecha de emision:  <?php echo $created_at ?></b> </small><br>
            <?php if($updated_at != $created_at) { ?>
            <small>   <b>Ultima actualizacion:  <?php echo $updated_at ?></b> </small><br> 
            <?php } ?>
            <small>  <b>Estado actual: <?php echo $status_tag ?></b></small></h4>   
            <?php if($rol>ROLE_VIGILANT)  { ?> 
                    <div class="row">
                        <div class="form-group col-sm-4">
                            <label for="name">Quien la envia</label>
                            <input type="text"  class="form-control mb-2 mr-sm-5"  name="name" value="<?php echo $name ?>" readonly>  
                        </div> 
                        <div class="form-group col-sm-4">
                            <label for="type">TIPO USUARIO</label>
                            <input type="text"  class="form-control mb-2 mr-sm-5"  name="type" value="<?php echo checkRole($rol) ?>"  readonly>  
                        </div>  
                        <div class="form-group col-sm-4">
                            <label for="apartment">Apartamento donde reside (EDIFICIO - APARTAMENTO)</label> 
                            <?php  if(!empty($apt_assignation)) { foreach($apt_assignation as $record) { 
                                if($apartment==$record->apartment){
                                ?>
                                <input type="text"  class="form-control mb-2 mr-sm-5"  name="type" value="<?php echo $record->edifice_code." - ".$record->apartment_code ?>"  readonly>
                            <?php }}} ?>  
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
                        <label for="description">Breve descripcion</label>
                        <textarea  readonly name="description" id="description" class="form-control"><?php echo $description ?></textarea>
                   
                    </div>  
                </div>  
                <label for="description">Fotos</label>
                <div id="carouselExampleControls2" class=" form-group carousel slide carousel-fade" data-ride="carousel">
                    <div class="carousel-inner">
                        <div class="carousel-item active">
                            <img class="d-block w-100" style ="height: 400px;width: 100%;" src="<?php echo base_url()."assets/images/incidents/".$photo_1 ?>"  alt="First slide">
                            <div class="carousel-caption  ">
                                
                                <p><a class="btn btn-primary btn-form" href="<?php echo base_url()."assets/images/incidents/".$photo_1 ?>" target= "_blank"><i class="fa fa-eye"></i> AMPLIAR IMAGEN</a></p>
                            </div>
                        </div>
                        <div class="carousel-item">
                            <img class="d-block w-100" style ="height: 400px;width: 100%;" src="<?php echo base_url()."assets/images/incidents/".$photo_2 ?>" alt="Second slide">
                            <div class="carousel-caption   "> 
                                <p><a class="btn btn-primary btn-form" href="<?php echo base_url()."assets/images/incidents/".$photo_2 ?>" target= "_blank"> <i class="fa fa-eye"></i> AMPLIAR IMAGEN</a></p>
                            </div>
                        </div> 
                    </div>
                    <a class="carousel-control-prev" href="#carouselExampleControls2" role="button" data-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="sr-only">ANTERIOR</span>
                    </a>
                    <a class="carousel-control-next" href="#carouselExampleControls2" role="button" data-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="sr-only">SIGUIENTE</span>
                    </a>
                </div> 
             
                <?php  if(!empty($observations)) { ?>
                    <div class="form-group">
                    <label for="apartment">Observaciones</label> 
                        <?php 
                        $observation = explode("<br>",$observations);
                        for($x=0;$x<COUNT($observation);$x++) {   ?>
                            <input type="text"  class="form-control mb-2 mr-sm-5"  value="<?php echo $observation[$x] ?>"  readonly>
                        <?php } ?> 
                    </div>  
                <?php } ?>  
               
               <hr>
             <?php if($status < INCIDENTS_SOLVED) {?>  
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
            <form class="form-inlne" id="add"  action="<?php echo base_url() ?>cambio_estado_insidencias" method="post" role="form">
                <div class="row">
                    <div class="form-group col-sm-6">
                        <label for="observations">Agregar observacion</label>
                        <input type="text"  class="form-control mb-2 mr-sm-5" id="observations"  name="observations" value="<?php echo set_value('observations'); ?>" > 
                        <input type="hidden" value="<?php echo $id; ?>" name="id" id="id" />      
                        <?php echo validation_error('observations');?> 
                    </div> 
                    <div class="form-group col-sm-6">
                        <label for="status" >Cambiar estado de la insidencia</label>
                        <select name="status" id="status" class="form-control"> 
                            <option value ="<?php echo INCIDENTS_IN_REVIEW ?>" <?php echo $status==INCIDENTS_IN_REVIEW?"selected":"" ?>>EN REVISION</option>
                            <option value ="<?php echo INCIDENTS_SOLVED ?>"  <?php echo $status==INCIDENTS_SOLVED?"selected":"" ?>>SOLVENTADO</option>   
                        </select>
                    </div> 
                </div> 
                <div class="form-group">
                    <button type="submit" class="btn btn-primary btn-form">ACTUALIZAR</button>
                </div> 
            </form>
            <?php } ?>  
        </div>
    </div>
</div>  

<script def src="<?php echo base_url(); ?>assets/scripts/system/ChangeStatusIncidents.js" type="text/javascript"></script>    