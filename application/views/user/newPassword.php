<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8"> 
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta http-equiv="Content-Language" content="en">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title> <?php echo NAME_SYSTEM ?> | Recuperar contraseña</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no, shrink-to-fit=no" />
    <meta name="description" content="Sistema de condominio">
    <meta name="msapplication-tap-highlight" content="no">
    <link href="<?php echo base_url(); ?>assets/main.css" rel="stylesheet"></head>
</head>

<body>
<div class="app-container app-theme-white body-tabs-shadow fixed-sidebar fixed-header center-elem"  style="background-image: url('<?php echo base_url(); ?>assets/images/background/login.jpg');background-size: 100%;">
        <div class="app-main center-elem">
            <div class="main-card  card">
                <div class="card-body text-center" >
                <img class ="img-login" src ="<?php echo base_url(); ?>assets/images/avatars/ic_launcher.png"/>
                    <h5 class="card-title">RECUPERAR CONTRASEÑA</h5> 
                    <form class="needs-validation" action="<?php echo base_url()."resetPasswordUser"?>" method="post" novalidate>
                         <?php $this->load->helper('form'); 
                            $error = $this->session->flashdata('error');   
                            if($error) { ?>
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    <button type="button" class="close" aria-label="Close"><span aria-hidden="true">×</span></button>
                                    <?php echo $error; ?>      
                                </div>
                               
                            <?php }
                            $success = $this->session->flashdata('success');
                            if($success)  { ?> 
                                <div class="alert alert-primary alert-dismissible fade show">
                                    <button type="button" class="close" aria-label="Close"><span aria-hidden="true">×</span></button>
                                    <?php echo $success; ?>              
                                </div>  
                        <?php } ?>
                        <div class="mb-3"> 
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="inputGroupPrepend">@</span>
                                </div>
                                <input type="text" class="form-control" id="email" name = "email" readonly placeholder="Ingrese correo electronico" value = "<?php echo $email; ?>"aria-describedby="inputGroupPrepend" required>
                                <div class="invalid-feedback">
                                    Por favor colocar el correo electronico.
                                </div>
                            </div>
                        </div> 
                        <div class="mb-3"> 
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="inputGroupPrepend">*</span>
                                </div>
                                <input type="text" class="form-control" id="password" name = "password" placeholder="Nueva contraseña" aria-describedby="inputGroupPrepend" required>
                                <div class="invalid-feedback">
                                    Por favor colocar el correo electronico.
                                </div>
                            </div>
                        </div> 
                        <div class="mb-3"> 
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="inputGroupPrepend">*</span>
                                </div>
                                <input type="text" class="form-control" id="cpassword" name = "cpassword" placeholder="Repetir nueva contraseña" aria-describedby="inputGroupPrepend" required>
                                <div class="invalid-feedback">
                                    Por favor colocar el correo electronico.
                                </div>
                            </div>
                        </div> 
                        <button class="btn btn-primary" type="submit">ACTUALIZAR</button>
                        <a class="btn btn-primary" href="<?php echo base_url()  ?>">INICIO DE SESION</a>
                    </form>

                  
                        </div>
                    </div>
                </div>
        </div>
    </div>
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/scripts/main.js"></script>
    <script>      
        (function() {
            'use strict';
            window.addEventListener('load', function() {
                // Fetch all the forms we want to apply custom Bootstrap validation styles to
                var forms = document.getElementsByClassName('needs-validation');
                // Loop over them and prevent submission
                var validation = Array.prototype.filter.call(forms, function(form) {
                    form.addEventListener('submit', function(event) {
                        if (form.checkValidity() === false) {
                            event.preventDefault();
                            event.stopPropagation();
                        }
                        form.classList.add('was-validated');
                    }, false);
                });
            }, false);
                })();
    </script>
</body>
</html>  
 
 <!--<!DOCTYPE html>
<html lang="en" class="h-100" id="login-page1">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title> Pedidos Argentina | Restablecer contraseña </title>
    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
 Favicon icon  
    <link rel="icon" type="image/png" sizes="16x16" href="<?php echo base_url(); ?>assets/assets/images/favicon.png">
 
    <link href="<?php echo base_url(); ?>assets/css/style.css" rel="stylesheet"> 
</head>
<body class="h-100">
    <div id="preloader">
        <div class="loader">
            <svg class="circular" viewBox="25 25 50 50">
                <circle class="path" cx="50" cy="50" r="20" fill="none" stroke-width="3" stroke-miterlimit="10" />
            </svg>
        </div>
    </div>
    <div class="login-bg h-100">
        <div class="container h-100">
            <div class="row justify-content-center h-100">
                <div class="col-xl-6">
                    <div class="form-input-content">
                        <div class="card">
                            <div class="card-body">
                                <div class="logo text-center">
                                    <a href="<?php echo base_url(); ?>">
                                        Pedidos Argentina
                                       
                                    </a>
                                </div>
                                <h4 class="text-center mt-5">Restablecer su contraseña</h4>
                                <?php $this->load->helper('form'); ?>
                                <?php echo validation_errors('<div class="alert alert-primary alert-dismissible fade show">', ' <button type="button" class="close h-100" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> </div>'); ?>
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
                                  <div class="alert alert-primary alert-dismissible fade show">
                                      <button type="button" class="close h-100" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span> </button> 
                                      <?php echo $success; ?>              
                                  </div>  
                                <?php } ?>
                                <p class="text-center">Ingrese su nueva contraseña!</p>
                                <form class="mt-5 mb-5"  action="<?php echo base_url()."resetPasswordUser"?>" method="post">
                                    <div class="form-group">
                                        <input type="email" class="form-control" type="email" placeholder="Correo electronico" name="email" value="<?php echo $email; ?>" readonly required>
                                    </div> 
                                    <div class="form-group">
                                        <input   class="form-control"  type="password" name="password" placeholder="Contraseña" name="password" required  autocomplete="off">
                                    </div> 
                                    <div class="form-group">
                                        <input class="form-control" type="password" name="cpassword"  placeholder="Confirma contraseña" name="cpassword" required >
                                    </div> 
                                    <div class="text-center mb-4 mt-5">
                                        <button type="submit" class="btn btn-primary">Enviar solicitud</button>
                                    </div>
                                    <div class="form-row"> 
                                        <div class="form-group col-md-12 text-right">
                                          <a href="<?php echo base_url()  ?>">Volver a inicio de sesion</a>
                                        </div>
                                    </div>
                                </form>
                                
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
     
    <script src="<?php echo base_url(); ?>assets/assets/plugins/common/common.min.js"></script> 
    <script src="<?php echo base_url(); ?>assets/js/custom.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/js/settings.js"></script>
    <script src="<?php echo base_url(); ?>assets/js/gleek.js"></script>
</body> 
</html>--> 