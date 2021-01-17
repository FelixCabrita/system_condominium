<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8"> 
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta http-equiv="Content-Language" content="en">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title> <?php echo NAME_SYSTEM ?> | CONDOMINIO</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no, shrink-to-fit=no" />
    <meta name="description" content="Sistema de condominio">
    <meta name="msapplication-tap-highlight" content="no">
    <link href="<?php echo base_url(); ?>assets/main.css" rel="stylesheet"></head>
</head>
 
<body>
    <div class="app-container app-theme-white body-tabs-shadow fixed-sidebar fixed-header center-elem" style="background-image: url('<?php echo base_url(); ?>assets/images/background/login.jpg');background-size: 100%;">
        <div class="app-main app-main_login center-elem" >
            <div class="main-card  card" >
                <div class="card-body text-center" style="padding: 27px;">
                    <img class ="img-login" src ="<?php echo base_url(); ?>assets/images/background/logo_admicondor.png" style="width:600px;padding:30px"/>
                    <h5 class="card-title">COLOQUE EL CODIGO DEL CONDOMINIO</h5> 
                    <form class="needs-validation" action="<?php echo base_url(); ?>/logcondominium" method="post" novalidate>
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
                                
                                <input type="text" class="form-control" id="code_condominium" name = "code_condominium" placeholder="Codigo del condominio" aria-describedby="inputGroupPrepend" required>
                                <div class="invalid-feedback">
                                    Por favor colocar el codigo.
                                </div>
                            </div>
                        </div>
                       
                        <button class="btn btn-primary" type="submit">VALIDAR</button> 
                    </form>

                  
                        </div>
                    </div>
                </div> 
                <a style="margin-bottom:15px" href="<?php echo base_url().'entrar_propietario'  ?>">ENTRAR COMO PROPIETARIO CONDOMINIO</a>
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