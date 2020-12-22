<?php
$id = $vehiclesInfo->id;
$tenant = $vehiclesInfo->tenant;
$licence_plate = $vehiclesInfo->licence_plate;   
$model = $vehiclesInfo->model;   
$brand = $vehiclesInfo->brand;   
$year = $vehiclesInfo->year;   
$condition_car = $vehiclesInfo->condition_car;  
$colour = $vehiclesInfo->colour; 
$document = $vehiclesInfo->document;   
$name = $vehiclesInfo->name;   
$type = $vehiclesInfo->type;   
$this->load->helper('form'); 
?>
  <script> var tenant = "<?php echo $tenant; ?>" </script>   
<div class="app-main__inner">  
    <div class="card form-card">
        <div class="card-body"> 
            <h4 class="card-title mb-4"> <i class="fa fa-users"></i> Vehiculo <br>
                <small> Agregar / <b>Editar</b></small></h4>
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
            <form class="form-inlne" id="add"  action="<?php echo base_url() ?>editando_vehiculos" method="post" role="form">
                <div class="row">
                    <?php if($rol <= ROLE_VIGILANT ){  ?>
                    <div class="form-group col-sm-4"> 
                        <label for="apartment">Buscar inquilino por documento de identidad </label>
                        <div class="input-group"> 
                            <input type="text"  class="form-control "  autocomplete="nope" autocomplete="off" value="<?php echo $document ?>"  id="document"  name="document"  maxlength="100" > 
                            <div class="input-group-prepend">
                                <a id ="search" class="btn btn-secondary"><i class="fa fa-search"></i></a>
                            </div>
                        </div> 
                        <input type="hidden" value="<?php echo $tenant ?>"  name="tenant" id="tenant" />  
                        <input type="hidden" value="<?php echo $id ?>"  name="id" id="id" />  
                        <?php echo validation_error('tenant');?> 
                    </div>  
                    <div class="form-group col-sm-4">
                        <label for="name">Nombre completo</label>
                        <input type="text"  class="form-control mb-2 mr-sm-5" value="<?php echo $name ?>" id="name" readonly>  
                    </div>  
                    <div class="form-group col-sm-4">
                        <label for="type">Tipo de inquilino</label>
                        <input type="text"  class="form-control mb-2 mr-sm-5"  value="<?php echo  checkRole($type) ?>" id="type" readonly>  
                    </div>  
                    <?php }else{ ?>
                        <div class="form-group col-sm-6">
                            <label for="name">Propietario</label>
                            <input type="text"  class="form-control mb-2 mr-sm-5" id="name" value="<?php echo $name ?>" readonly>  
                        </div>  
                        <div class="form-group col-sm-6">
                            <label for="type">Tipo de inquilino</label>
                            <input type="text"  class="form-control mb-2 mr-sm-5" id="type" value="<?php echo  checkRole($rol) ?>"  readonly>  
                        </div>  
                        <input type="hidden" value="<?php echo $tenant ?>"  name="tenant" id="tenant" />  
                        <input type="hidden" value="<?php echo $id ?>"  name="id" id="id" />  
                    <?php } ?>
                </div>  
                <div class="row">
                    <div class="form-group col-sm-3">
                        <label for="licence_plate">Placa</label>
                        <input type="text"  class="form-control mb-2 mr-sm-5" id="licence_plate"  name="licence_plate" value="<?php echo $licence_plate; ?>" maxlength="20" > 
                        <?php echo validation_error('licence_plate');?> 
                    </div>  
                    <div class="form-group col-sm-3">
                        <label for="model">Modelo</label>
                        <input type="text"  class="form-control mb-2 mr-sm-5" id="model"  name="model" value="<?php echo $model; ?>"  maxlength="20" > 
                        <?php echo validation_error('model');?> 
                    </div> 
                    <div class="form-group col-sm-3">
                        <label for="brand" >Marca</label>
                        <input type="text"  class="form-control mb-2 mr-sm-5" id="brand"  name="brand" value="<?php echo $brand; ?>"   maxlength="20" > 
                        <?php echo validation_error('brand');?> 
                    </div> 
                    <div class="form-group col-sm-3">
                        <label for="year" >Año</label>
                        <input type="number"  class="form-control mb-2 mr-sm-5" id="year"  name="year" value="<?php echo $year; ?>"   maxlength="4" > 
                        <?php echo validation_error('year');?> 
                    </div> 
                </div>   
                <div class="row">
                    <div class="form-group col-sm-3">
                        <label for="colour">Color</label>
                        <input type="text"  class="form-control mb-2 mr-sm-5" id="colour"  name="colour" value="<?php echo $colour; ?>" maxlength="200" > 
                        <?php echo validation_error('colour');?> 
                    </div>  
                    <div class="form-group col-sm-9">
                        <label for="condition_car">Condicion del vehiculo</label>
                        <input type="text"  class="form-control mb-2 mr-sm-5" id="condition_car"  name="condition_car" value="<?php echo $condition_car; ?>"> 
                        <?php echo validation_error('condition_car');?> 
                    </div>  
                </div>   
                <div class="form-group"> 
                    <button type="submit" class="btn btn-primary btn-form">ACTUALIZAR</button>
                </div> 
            </form>
        </div>
    </div> 
</div>  
<script src="<?php echo base_url(); ?>assets/scripts/system/editVehicle.js" type="text/javascript"></script>  
 