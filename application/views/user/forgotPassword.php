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
                                <input type="text" class="form-control" id="login_email" name = "login_email" placeholder="Ingrese correo electronico" aria-describedby="inputGroupPrepend" required>
                                <div class="invalid-feedback">
                                    Por favor colocar el correo electronico.
                                </div>
                               
                            </div> 
                            <?php   if($_COOKIE['workstation_fscv']!='OWNER'){ ?>
                            <div class="form-group  ">
                                <input type="checkbox" id="isclient" name= "isclient" class="custom-control-input">
                                 <label class="custom-control-label" for="isclient">Es cliente?</label>
                            </div> 
                            <?php } ?>
                        </div> 
                        <button class="btn btn-primary" type="submit">RECUPERAR</button>
                        <a class="btn btn-primary" href="<?php echo base_url()  ?>">VOLVER</a>
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