
<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8">
    <title>Pedidos Argentina | ERROR</title>
    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
    <link href="<?php echo base_url(); ?>assets/bower_components/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo base_url(); ?>assets/bower_components/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo base_url(); ?>assets/dist/css/AdminLTE.min.css" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/dist/css/util.css">
        
    <link rel="shortcut icon" href="<?= base_url('assets/images/ic_launcher.png')?>" type="image/x-icon"> 
  	<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/dist/css/main.css">
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    <!-- Google Font -->
  </head>
  <body class="hold-transition login-page">
    <div class="limiter">

              <div class="container-login100" style="background-image: url('<?php echo base_url(); ?>assets/images/imagen.jpg') ;">
                <div class="wrap-login100">
        <?php $this->load->helper('form'); ?>
        <div class="row">
            <div class="col-md-12">
                <?php echo validation_errors('<div class="alert alert-danger alert-dismissable">', ' <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button></div>'); ?>
            </div>
        </div>
        <div>
                     <span class="login100-form-title p-t-20 p-b-45 logo-logo">
                        <img style="   width: 165px; height: 165px;" src = "<?= base_url('assets/images/Pedidos Argentina_11.png')?>">
                    </span>
                  
                    <span class="login100-form-title">
                       ACCESO INCORRECTO, POR FAVOR SI EL ERROR PERSISTE COMUNIQUESE CON NOSTROS.
                    </span>
                  
                </div>
             
          </div>
      </div>
   
    </div>
	
    <script src="<?php echo base_url(); ?>assets/bower_components/jquery/dist/jquery.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/bower_components/bootstrap/dist/js/bootstrap.min.js"></script> 
  </body>
</html>