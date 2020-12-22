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
?> 
<div class="app-main__inner">  
    <div class="card form-card">
        <div class="card-body"> 
            <h4 class="card-title mb-4"> <i class="fa fa-users"></i> Informacion del Vehiculo <br> </h4>  
                <div class="row"> 
                    <div class="form-group col-sm-4"> 
                        <label for="apartment">Buscar inquilino por documento de identidad </label>
                        <div class="input-group"> 
                            <input type="text"  class="form-control " readonly value="<?php echo $document ?>"   >  
                        </div>   
                    </div>  
                    <div class="form-group col-sm-4">
                        <label for="name">Nombre completo</label>
                        <input type="text"  class="form-control mb-2 mr-sm-5" value="<?php echo $name ?>" id="name" readonly>  
                    </div>  
                    <div class="form-group col-sm-4">
                        <label for="type">Tipo de inquilino</label>
                        <input type="text"  class="form-control mb-2 mr-sm-5"  value="<?php echo  checkRole($type) ?>" id="type" readonly>  
                    </div>  
                    
                </div>  
                <div class="row">
                    <div class="form-group col-sm-3">
                        <label for="licence_plate">Placa</label>
                        <input type="text"  class="form-control mb-2 mr-sm-5" readonly value="<?php echo $licence_plate; ?>" maxlength="20" > 
                        
                    </div>  
                    <div class="form-group col-sm-3">
                        <label for="model">Modelo</label>
                        <input type="text"  class="form-control mb-2 mr-sm-5"readonly value="<?php echo $model; ?>"  maxlength="20" > 
                       
                    </div> 
                    <div class="form-group col-sm-3">
                        <label for="brand" >Marca</label>
                        <input type="text"  class="form-control mb-2 mr-sm-5" readonly value="<?php echo $brand; ?>"   maxlength="20" > 
                   
                    </div> 
                    <div class="form-group col-sm-3">
                        <label for="year" >Año</label>
                        <input type="number"  class="form-control mb-2 mr-sm-5" readonly value="<?php echo $year; ?>"   maxlength="4" > 
                        
                    </div> 
                </div>   
                <div class="row">
                    <div class="form-group col-sm-3">
                        <label for="colour">Color</label>
                        <input type="text"  class="form-control mb-2 mr-sm-5" readonly value="<?php echo $colour; ?>" maxlength="200" > 
                         
                    </div>  
                    <div class="form-group col-sm-9">
                        <label for="condition_car">Condicion del vehiculo</label>
                        <input type="text"  class="form-control mb-2 mr-sm-5" readonly value="<?php echo $condition_car; ?>"> 
                       
                    </div>  
                </div>   
                <div class="form-group"> 
                    <a  class="btn btn-primary btn-form" href="<?php echo base_url(); ?>etiquetas">VOLVER</a>
                </div> 
            </form>
        </div>
    </div> 
</div>  
 
 