<?php
$clients = $permissions->{"clients"};
$vendors = $permissions->{"vendors"};
$routes = $permissions->{"routes"};
$products = $permissions->{"products"};
$brands = $permissions->{"brands"};
$groups = $permissions->{"groups"};
$users = $permissions->{"users"};
$visits = $permissions->{"visits"};
$payments = $permissions->{"payments"};
$orders_headers = $permissions->{"orders_headers"};

?>
<div class="app-main__inner"> 
    <div class="card form-card">
        <div class="card-body"> 
            <h4 class="card-title mb-4"> <i class="fa fa-users"></i> Permisos<br>
                <small> <b>Editar</b></small></h4>
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
            <form style="padding:15px" class="form-inlne" id="addUser" action="<?php echo base_url() ?>actualizapermisos" method="post" role="form">
                <div class="form-group">
                    <label for="fname">VENDEDOR</label>
                    <input type="hidden" value="<?php echo $id; ?>" name="id" id="id" />    
                    <div class="row form-group">
                        <div class="form-group  col-sm-3"> 
                            <input type="checkbox" id="VEC" name="VEC" class="form-check-input filled-in styled-checkbox" <?php echo $vendors->C==1?'checked':'' ?> >
                            
                            <label for="VEC" class="form-check-label check-black">CREAR</label>  
                        </div>  
                        <div class="form-group  col-sm-3"> 
                            <input type="checkbox" id="VER" name="VER" class="form-check-input filled-in styled-checkbox" <?php echo $vendors->R==1?'checked':'' ?> >
                            <label for="VER" class="form-check-label check-black">LISTAR</label>  
                        </div> 
                        <div class="form-group  col-sm-3"> 
                            <input type="checkbox" id="VEU" name="VEU" class="form-check-input filled-in styled-checkbox" <?php echo $vendors->U==1?'checked':'' ?> >
                            <label for="VEU" class="form-check-label check-black">ACTUALIZAR</label>  
                        </div> 
                        <div class="form-group  col-sm-3"> 
                            <input type="checkbox" id="VED" name="VED" class="form-check-input filled-in styled-checkbox" <?php echo $vendors->D==1?'checked':'' ?> >
                            <label for="VED" class="form-check-label check-black">ELIMINAR</label>  
                        </div>  
                    </div>  
                </div>
                    <hr> 
                <div class="form-group">
                    <label for="fname">CLIENTE</label>
                    <input type="hidden" value="<?php echo $id; ?>" name="id" id="id" />    
                    <div class="row form-group">
                        <div class="form-group  col-sm-3"> 
                            <input type="checkbox" id="CC" name="CC" class="form-check-input filled-in styled-checkbox" <?php echo $clients->C==1?'checked':'' ?> >
                            
                            <label for="CC" class="form-check-label check-black">CREAR</label>  
                        </div>  
                        <div class="form-group  col-sm-3"> 
                            <input type="checkbox" id="CR" name="CR" class="form-check-input filled-in styled-checkbox" <?php echo $clients->R==1?'checked':'' ?> >
                            <label for="CR" class="form-check-label check-black">LISTAR</label>  
                        </div> 
                        <div class="form-group  col-sm-3"> 
                            <input type="checkbox" id="CU" name="CU" class="form-check-input filled-in styled-checkbox" <?php echo $clients->U==1?'checked':'' ?> >
                            <label for="CU" class="form-check-label check-black">ACTUALIZAR</label>  
                        </div> 
                        <div class="form-group  col-sm-3"> 
                            <input type="checkbox" id="CD" name="CD" class="form-check-input filled-in styled-checkbox" <?php echo $clients->D==1?'checked':'' ?> >
                            <label for="CD" class="form-check-label check-black">ELIMINAR</label>  
                        </div>  
                    </div>  
                </div>
                    <hr> 
                <div class="form-group">
                    <label for="fname">RUTAS</label>
                    <div class="row form-group">
                        <div class="form-group  col-sm-3"> 
                            <input type="checkbox" id="RC" name="RC" class="form-check-input filled-in styled-checkbox" <?php echo $routes->C==1?'checked':'' ?> >
                            <label for="RC" class="form-check-label check-black">CREAR</label>  
                        </div>  
                        <div class="form-group  col-sm-3"> 
                            <input type="checkbox" id="RR" name="RR" class="form-check-input filled-in styled-checkbox" <?php echo $routes->R==1?'checked':'' ?> >
                            <label for="RR" class="form-check-label check-black">LISTAR</label>  
                        </div> 
                        <div class="form-group  col-sm-3"> 
                            <input type="checkbox" id="RU" name="RU" class="form-check-input filled-in styled-checkbox" <?php echo $routes->U==1?'checked':'' ?> >
                            <label for="RU" class="form-check-label check-black">ACTUALIZAR</label>  
                        </div> 
                        <div class="form-group  col-sm-3"> 
                            <input type="checkbox" id="RD" name="RD" class="form-check-input filled-in styled-checkbox" <?php echo $routes->D==1?'checked':'' ?> >
                            <label for="RD" class="form-check-label check-black">ELIMINAR</label>  
                        </div>  
                    </div>  
                </div>
                <hr> 
                <div class="form-group">
                    <label for="fname">PRODUCTO</label>
                    <div class="row form-group">
                        <div class="form-group  col-sm-3"> 
                            <input type="checkbox" id="PC" name="PC" class="form-check-input filled-in styled-checkbox" <?php echo $products->C==1?'checked':'' ?> >
                            <label for="PC" class="form-check-label check-black">CREAR</label>  
                        </div>  
                        <div class="form-group  col-sm-3"> 
                            <input type="checkbox" id="PR" name="PR" class="form-check-input filled-in styled-checkbox" <?php echo $products->R==1?'checked':'' ?> >
                            <label for="PR" class="form-check-label check-black">LISTAR</label>  
                        </div> 
                        <div class="form-group  col-sm-3"> 
                            <input type="checkbox" id="PU" name="PU" class="form-check-input filled-in styled-checkbox" <?php echo $products->U==1?'checked':'' ?> >
                            <label for="PU" class="form-check-label check-black">ACTUALIZAR</label>  
                        </div> 
                        <div class="form-group  col-sm-3"> 
                            <input type="checkbox" id="PD" name="PD" class="form-check-input filled-in styled-checkbox" <?php echo $products->D==1?'checked':'' ?> >
                            <label for="PD" class="form-check-label check-black">ELIMINAR</label>  
                        </div>  
                    </div>  
                </div>
                <hr> 
                <div class="form-group">
                    <label for="fname">MARCA</label>
                    <div class="row form-group">
                        <div class="form-group  col-sm-3"> 
                            <input type="checkbox" id="BC" name="BC" class="form-check-input filled-in styled-checkbox" <?php echo $brands->C==1?'checked':'' ?> >
                            <label for="BC" class="form-check-label check-black">CREAR</label>  
                        </div>  
                        <div class="form-group  col-sm-3"> 
                            <input type="checkbox" id="BR" name="BR" class="form-check-input filled-in styled-checkbox" <?php echo $brands->R==1?'checked':'' ?> >
                            <label for="BR" class="form-check-label check-black">LISTAR</label>  
                        </div> 
                        <div class="form-group  col-sm-3"> 
                            <input type="checkbox" id="BU" name="BU" class="form-check-input filled-in styled-checkbox" <?php echo $brands->U==1?'checked':'' ?> >
                            <label for="BU" class="form-check-label check-black">ACTUALIZAR</label>  
                        </div> 
                        <div class="form-group  col-sm-3"> 
                            <input type="checkbox" id="BD" name="BD" class="form-check-input filled-in styled-checkbox" <?php echo $brands->D==1?'checked':'' ?> >
                            <label for="BD" class="form-check-label check-black">ELIMINAR</label>  
                        </div>  
                    </div>  
                </div>
                <hr> 
                <div class="form-group">
                    <label for="fname">GRUPO</label>
                    <div class="row form-group">
                        <div class="form-group  col-sm-3"> 
                            <input type="checkbox" id="GC" name="GC" class="form-check-input filled-in styled-checkbox" <?php echo $groups->C==1?'checked':'' ?> >
                            <label for="GC" class="form-check-label check-black">CREAR</label>  
                        </div>  
                        <div class="form-group  col-sm-3"> 
                            <input type="checkbox" id="GR" name="GR" class="form-check-input filled-in styled-checkbox" <?php echo $groups->R==1?'checked':'' ?> >
                            <label for="GR" class="form-check-label check-black">LISTAR</label>  
                        </div> 
                        <div class="form-group  col-sm-3"> 
                            <input type="checkbox" id="GU" name="GU" class="form-check-input filled-in styled-checkbox" <?php echo $groups->U==1?'checked':'' ?> >
                            <label for="GU" class="form-check-label check-black">ACTUALIZAR</label>  
                        </div> 
                        <div class="form-group  col-sm-3"> 
                            <input type="checkbox" id="GD" name="GD" class="form-check-input filled-in styled-checkbox" <?php echo $groups->D==1?'checked':'' ?> >
                            <label for="GD" class="form-check-label check-black">ELIMINAR</label>  
                        </div>  
                    </div>  
                </div>
                <hr> 
                <div class="form-group">
                    <label for="fname">USUARIO</label>
                    <div class="row form-group">
                        <div class="form-group  col-sm-3"> 
                            <input type="checkbox" id="UC" name="UC" class="form-check-input filled-in styled-checkbox" <?php echo $users->C==1?'checked':'' ?> >
                            <label for="UC" class="form-check-label check-black">CREAR</label>  
                        </div>  
                        <div class="form-group  col-sm-3"> 
                            <input type="checkbox" id="UR" name="UR" class="form-check-input filled-in styled-checkbox" <?php echo $users->R==1?'checked':'' ?> >
                            <label for="UR" class="form-check-label check-black">LISTAR</label>  
                        </div> 
                        <div class="form-group  col-sm-3"> 
                            <input type="checkbox" id="UU" name="UU" class="form-check-input filled-in styled-checkbox" <?php echo $users->U==1?'checked':'' ?> >
                            <label for="UU" class="form-check-label check-black">ACTUALIZAR</label>  
                        </div> 
                        <div class="form-group  col-sm-3"> 
                            <input type="checkbox" id="UD" name="UD" class="form-check-input filled-in styled-checkbox" <?php echo $users->D==1?'checked':'' ?> >
                            <label for="UD" class="form-check-label check-black">ELIMINAR</label>  
                        </div>  
                    </div>  
                </div> 
                <hr> 
                <div class="form-group">
                    <label for="fname">VISITA</label>
                    <div class="row form-group">
                        <div class="form-group  col-sm-3"> 
                            <input type="checkbox" id="VC" name="VC" class="form-check-input filled-in styled-checkbox" <?php echo $visits->C==1?'checked':'' ?> >
                            <label for="VC" class="form-check-label check-black">CREAR</label>  
                        </div>  
                        <div class="form-group  col-sm-3"> 
                            <input type="checkbox" id="VR" name="VR" class="form-check-input filled-in styled-checkbox" <?php echo $visits->R==1?'checked':'' ?> >
                            <label for="VR" class="form-check-label check-black">LISTAR</label>  
                        </div> 
                        <div class="form-group  col-sm-3"> 
                            <input type="checkbox" id="VU" name="VU" class="form-check-input filled-in styled-checkbox" <?php echo $visits->U==1?'checked':'' ?> >
                            <label for="VU" class="form-check-label check-black">ACTUALIZAR</label>  
                        </div> 
                        <div class="form-group  col-sm-3"> 
                            <input type="checkbox" id="VD" name="VD" class="form-check-input filled-in styled-checkbox" <?php echo $visits->D==1?'checked':'' ?> >
                            <label for="VD" class="form-check-label check-black">ELIMINAR</label>  
                        </div>  
                    </div>  
                </div>
                <hr> 
                <div class="form-group">
                    <label for="fname">PAGO</label>
                    <div class="row form-group">
                        <div class="form-group  col-sm-3"> 
                            <input type="checkbox" id="PAC" name="PAC" class="form-check-input filled-in styled-checkbox" <?php echo $payments->C==1?'checked':'' ?> >
                            <label for="PAC" class="form-check-label check-black">CREAR</label>  
                        </div>  
                        <div class="form-group  col-sm-3"> 
                            <input type="checkbox" id="PAR" name="PAR" class="form-check-input filled-in styled-checkbox" <?php echo $payments->R==1?'checked':'' ?> >
                            <label for="PAR" class="form-check-label check-black">LISTAR</label>  
                        </div> 
                        <div class="form-group  col-sm-3"> 
                            <input type="checkbox" id="PAU" name="PAU" class="form-check-input filled-in styled-checkbox" <?php echo $payments->U==1?'checked':'' ?> >
                            <label for="PAU" class="form-check-label check-black">ACTUALIZAR</label>  
                        </div> 
                        <div class="form-group  col-sm-3"> 
                            <input type="checkbox" id="PAD" name="PAD" class="form-check-input filled-in styled-checkbox" <?php echo $payments->D==1?'checked':'' ?> >
                            <label for="PAD" class="form-check-label check-black">ELIMINAR</label>  
                        </div>  
                    </div>  
                </div> 
                <hr> 
                <div class="form-group">
                    <label for="fname">PEDIDO</label>
                    <div class="row form-group">
                        <div class="form-group  col-sm-3"> 
                            <input type="checkbox" id="OC" name="OC" class="form-check-input filled-in styled-checkbox" <?php echo $orders_headers->C==1?'checked':'' ?> >
                            <label for="OC" class="form-check-label check-black">CREAR</label>  
                        </div>  
                        <div class="form-group  col-sm-3"> 
                            <input type="checkbox" id="OR" name="OR" class="form-check-input filled-in styled-checkbox" <?php echo $orders_headers->R==1?'checked':'' ?> >
                            <label for="OR" class="form-check-label check-black">LISTAR</label>  
                        </div> 
                        <div class="form-group  col-sm-3"> 
                            <input type="checkbox" id="OU" name="OU" class="form-check-input filled-in styled-checkbox" <?php echo $orders_headers->U==1?'checked':'' ?> >
                            <label for="OU" class="form-check-label check-black">ACTUALIZAR</label>  
                        </div> 
                        <div class="form-group  col-sm-3"> 
                            <input type="checkbox" id="OD" name="OD" class="form-check-input filled-in styled-checkbox" <?php echo $orders_headers->D==1?'checked':'' ?> >
                            <label for="OD" class="form-check-label check-black">ELIMINAR</label>  
                        </div>  
                    </div>  
                </div>
                <div class="form-group">
                    <button type="submit" class="btn btn-primary btn-form">ACTUALIZAR</button>
                </div>
                    
            </form>
        </div>
    </div> 
</div> 
<script src="<?php echo base_url(); ?>assets/js/jquery-3.5.1.min.js"></script>  
<script src="<?php echo base_url(); ?>assets/js/system/addUser.js" type="text/javascript"></script>  