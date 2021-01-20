<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta http-equiv="Content-Language" content="en">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title><?php echo $pageTitle; ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no, shrink-to-fit=no" />
    <meta name="description" content="Sistema de condominio">
    <meta name="msapplication-tap-highlight" content="no">
    <link rel="icon" type="image/png" sizes="16x16" href="<?php echo base_url(); ?>assets/favicon.png">
    <link href="<?php echo base_url(); ?>assets/main.css" rel="stylesheet"></head>
    <link href="<?php echo base_url() ?>assets/scripts/sweetalert2/dist/sweetalert2.min.css" rel="stylesheet">
    <script src="<?php echo base_url(); ?>assets/scripts/jquery-3.5.1.min.js"></script>  
    <script> var baseURL = "<?php echo base_url(); ?>",c= "<?php echo  $condominium; ?>"; </script>   
</head>
<style>
.metismenu-icon{
    color:#000 !important;
    opacity: 1 !important;
}</style>
<body>
    <div class="app-container app-theme-white body-tabs-shadow fixed-sidebar fixed-header">
        <div class="app-header header-shadow"> 
            <div class="app-header__logo">
                <div class="logo-src"></div>
                <div class="header__pane ml-auto">
                    <div>
                        <button type="button" class="hamburger close-sidebar-btn hamburger--elastic" data-class="closed-sidebar">
                            <span class="hamburger-box">
                                <span class="hamburger-inner"></span>
                            </span>
                        </button>
                    </div>
                </div>
            </div>
            <div class="app-header__mobile-menu">
                <div>
                    <button type="button" class="hamburger hamburger--elastic mobile-toggle-nav">
                        <span class="hamburger-box">
                            <span class="hamburger-inner"></span>
                        </span>
                    </button>
                </div>
            </div>
            <div class="app-header__menu">
                <span>
                    <button type="button" class="btn-icon btn-icon-only btn btn-primary btn-sm mobile-toggle-header-nav">
                        <span class="btn-icon-wrapper">
                            <i class="fa fa-ellipsis-v fa-w-6"></i>
                        </span>
                    </button>
                </span>
            </div>
            <div class="app-header__content">
                <?php  if($rol==ROLE_ADMIN) { ?>
                    <div class="app-header-left">
                
                        <ul class="header-menu nav">
                            <li class="nav-item">
                                <a href="javascript:void(0);" class="nav-link">
                                    <i class="nav-link-icon fa fa-database"> </i> Condominio&nbsp;&nbsp; 
                                    <select  name="selectCondominium" id="selectCondominium" class="form-control">
                                        <?php   if(!empty($condominiumRecords))  {  foreach($condominiumRecords as $record)  { ?>
                                        <option value ="<?php echo $record->id ?>" <?php echo $record->id==$condominium?"selected":"" ?>><?php echo $record->name ?></option>
                                        <?php  }  }  ?>
                                    </select> 
                                    <script> var condominios = "<?php echo COUNT($condominiumRecords) ?>";</script>
                                </a>
                                
                            </li> 
                        </ul>
                    </div>
                <?php }else{?>
                    <div class="app-header-left">
                
                        <ul class="header-menu nav">
                            <li class="nav-item">
                    <a class="btn btn-sm btn-success" target="_blank" title="IMPRIMIR CODIGO" href="<?php echo base_url().'imprimir_condominio/'.$condominium; ?>" ><i class="fa fa-print"></i> <?php echo $condominium_text ?></a> </a>
                    </li> 
                        </ul>
                    </div>
                    <script> var condominios = 1;</script>
                <?php  } ?>
                <div class="app-header-right">
                    <div class="header-btn-lg pr-0">
                        <div class="widget-content p-0">
                            <div class="widget-content-wrapper">
                                <div class="widget-content-left">
                                    <div class="btn-group">
                                        <a data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="p-0 btn">
                                            <img width="42" class="rounded-circle" src="<?php echo base_url(); ?>assets/images/avatars/profile.png" alt="">
                                            <i class="fa fa-angle-down ml-2 opacity-8"></i>
                                        </a>
                                        <div tabindex="-1" role="menu" aria-hidden="true" class="dropdown-menu dropdown-menu-right">
                                            <a type="button"  href="<?php echo base_url() . 'profile' ?>"  tabindex="0" class="dropdown-item">Perfil</a>
                                            <div tabindex="-1" class="dropdown-divider"></div>
                                            <a type="button"  href="<?php echo base_url() . 'logout' ?>" tabindex="0" class="dropdown-item">Cerrar sesion</a>
                                        </div>
                                    </div>
                                </div>
                                <div class="widget-content-left  ml-3 header-user-info">
                                    <div class="widget-heading">
                                        <?php echo $name ?>
                                    </div>
                                    <div class="widget-subheading">
                                    <?php echo checkRole($rol) ?>
                                    </div>
                                </div> 
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="ui-theme-settings "> 
            <div class="theme-settings__inner">
                <div class="scrollbar-container ps ps--active-y">
                   
                    <div class="ps__rail-x" style="left: 0px; bottom: 0px;">
                        <div class="ps__thumb-x" tabindex="0" style="left: 0px; width: 0px;"></div>
                    </div>
                    <div class="ps__rail-y" style="top: 0px; height: 665px; right: 0px;">
                        <div class="ps__thumb-y" tabindex="0" style="top: 0px; height: 410px;"></div>
                    </div>
                </div>
            </div>
        </div>


<!-- REPORT --> 
 
<div class="modal fade" id="report_bv" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">REPORTE DE BALANCE DE COMPROBACION</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">  
                <div class="row form-group ">
                    <div class="form-group col-sm-6">
                        <label for="start_time">Desde</label>
                        <input type="date"  class="form-control mb-2 mr-sm-5" id="start_date"  name="start_date" >
                    </div>
                    <div class="form-group col-sm-6">
                        <label for="ending_time">Hasta</label>
                        <input type="date"  class="form-control mb-2 mr-sm-5" id="ending_date"  name="ending_date" >
                    </div>
                </div>    
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-primary report_balance_verification">Generar reporte</button>
            </div>
        </div>
    </div>
</div>   


<div class="modal fade" id="report_ra" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">REPORTE DE RESULTADOS ACUMULADOS</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">  
                <div class="row form-group ">
                    <div class="form-group col-sm-6">
                        <label for="start_time">Desde</label>
                        <input type="date"  class="form-control mb-2 mr-sm-5" id="start_date_ra"  name="start_date_ra" >
                    </div>
                    <div class="form-group col-sm-6">
                        <label for="ending_time">Hasta</label>
                        <input type="date"  class="form-control mb-2 mr-sm-5" id="ending_date_ra"  name="ending_date_ra" >
                    </div>
                </div>   
                <h3>Selecciones cuentas a comparar</h3>                 
                 <div class="row form-group ">
                    <div class="form-group col-sm-6"> 
                        <select name="heading_1_ra" id="heading_1_ra" class="form-control"> 
                        </select>
                    </div>
                    <div class="form-group col-sm-6">
                        <select name="heading_2_ra" id="heading_2_ra" class="form-control"> 
                        </select>
                    </div>
                </div>    
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-primary report_result_accumulated">Generar reporte</button>
            </div>
        </div>
    </div>
</div>  

<!-- Modal --> 
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">REPORTE DE VISITANTES</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body"> 
                <form class="form-inlne" id="generateReport" action="<?php echo base_url() ?>generando_reporte" method="post" role="form">
                    <div class="row form-group ">
                        <div class="form-group col-sm-6">
                            <label for="start_time">Desde</label>
                            <input type="date"  class="form-control mb-2 mr-sm-5" id="start_date"  name="start_date" >
                        </div>
                        <div class="form-group col-sm-6">
                            <label for="ending_time">Hasta</label>
                            <input type="date"  class="form-control mb-2 mr-sm-5" id="ending_date"  name="ending_date" >
                        </div>
                    </div>                       
                    <div class="row form-group ">
                        <div class="form-group col-sm-6">
                            <label for="type_visitor">tipo de visitante</label>
                            <select name="type_visitor" id="type_visitor" class="form-control">
                                <option value ="" >AMBOS</option>
                                <option value ="<?php echo VISITOR_DEFAULT ?>" >NORMAL</option>
                                <option value ="<?php echo VISITOR_EMPLOYEES ?>">EMPLEADOS</option>   
                            </select>
                        </div>
                        <div class="form-group col-sm-6">
                            <label for="type_input">tipo de entrada</label>
                            <select name="type_input" id="type_input" class="form-control">
                                <option value ="" >AMBOS</option>
                                <option value ="<?php echo VIGILANT_INPUT ?>" >ENTRADAS</option>
                                <option value ="<?php echo VIGILANT_EXIT ?>">SALIDAS</option>   
                            </select>
                        </div>
                    </div> 
                </form>      
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-primary generate_report">Generar reporte</button>
            </div>
        </div>
    </div>
</div>   

<div class="modal fade" id="importEdifices" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">IMPORTAR DATOS DE EDIFICIOS</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
         <form  id="importEdifice" action="<?php echo base_url()  ?>importacion_edificios" method="post" role="form"  enctype="multipart/form-data">
            <div class="form-group">
                <label for="userfile">Selecciona tu excel (xlsx|xls)</label>
                <input type="file" class="form-control-file" id="userfile" name="userfile" accept=".xlsx,.xls">
            </div>
        </form>
      </div>
      <div class="modal-footer"> 
        <a href="<?php echo base_url()  ?>/assets/format/edifice.xlsx" target="_blank" class="btn btn-success" >Descargar formato</a>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
        <button type="button" class="btn btn-primary" onclick="importEdifice()">Procesar</button>
      </div>
    </div>
  </div>
</div>
 
<div class="modal fade" id="importApartmentModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">IMPORTAR DATOS DE APARTAMENTOS</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
         <form  id="importApartment" action="<?php echo base_url()  ?>importacion_apartemantos" method="post" role="form"  enctype="multipart/form-data">
            <div class="form-group">
                <label for="userfile">Selecciona tu excel (xlsx|xls)</label>
                <input type="file" class="form-control-file" id="userfile" name="userfile" accept=".xlsx,.xls">
            </div>
            <label for="userfile">NOTA: Se comprobara la existencia del edificio antes de agregar el apartamento, esto nos asegurara de mantener informacion correcta y enlazada</label>
        </form>
      </div>
      <div class="modal-footer">
        <a href="<?php echo base_url()  ?>/assets/format/apartment.xlsx" target="_blank" class="btn btn-success" >Descargar formato</a>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
        <button type="button" class="btn btn-primary" onclick="importApartment()">Procesar</button>
      </div>
    </div>
  </div>
</div> 
 
<div class="modal fade" id="importClientss" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">IMPORTAR DATOS DE CLIENTES</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
         <form  id="importClients" action="<?php echo base_url()  ?>importacion_clientes" method="post" role="form"  enctype="multipart/form-data">
            <div class="form-group">
                <label for="userfile">Selecciona tu excel (xlsx|xls)</label>
                <input type="file" class="form-control-file" id="userfile" name="userfile" accept=".xlsx,.xls">
            </div>
        </form>
      </div>
      <div class="modal-footer">
      <a href="<?php echo base_url()  ?>/assets/format/clients.xlsx" target="_blank" class="btn btn-success" >Descargar formato</a>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
        <button type="button" class="btn btn-primary" onclick="importClients()">Procesar</button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="importModules" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">IMPORTAR DATOS DE MODULOS</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
         <form  id="importModule" action="<?php echo base_url()  ?>importacion_modulos" method="post" role="form"  enctype="multipart/form-data">
            <div class="form-group">
                <label for="userfile">Selecciona tu excel (xlsx|xls)</label>
                <input type="file" class="form-control-file" id="userfile" name="userfile" accept=".xlsx,.xls">
            </div>
        </form>
      </div>
      <div class="modal-footer">
      <a href="<?php echo base_url()  ?>/assets/format/module.xlsx" target="_blank" class="btn btn-success" >Descargar formato</a>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
        <button type="button" class="btn btn-primary" onclick="importModule()">Procesar</button>
      </div>
    </div>
  </div>
</div>
 
<div class="modal fade" id="importheadings" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">IMPORTAR DATOS DE RUBROS</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
         <form  id="importheading" action="<?php echo base_url()  ?>importacion_rubro" method="post" role="form"  enctype="multipart/form-data">
            <div class="form-group">
                <label for="userfile">Selecciona tu excel (xlsx|xls)</label>
                <input type="file" class="form-control-file" id="userfile" name="userfile" accept=".xlsx,.xls">
            </div>
        </form>
      </div>
      <div class="modal-footer">
      <a href="<?php echo base_url()  ?>/assets/format/rubros.xlsx" target="_blank" class="btn btn-success" >Descargar formato</a>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
        <button type="button" class="btn btn-primary" onclick="importheading()">Procesar</button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="importDocuments" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">IMPORTAR DATOS DE TIPOS DE DOCUMENTOS</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
         <form  id="importDocument" action="<?php echo base_url()  ?>importacion_tipodocumento" method="post" role="form"  enctype="multipart/form-data">
            <div class="form-group">
                <label for="userfile">Selecciona tu excel (xlsx|xls)</label>
                <input type="file" class="form-control-file" id="userfile" name="userfile" accept=".xlsx,.xls">
            </div>
        </form>
      </div>
      <div class="modal-footer">
      <a href="<?php echo base_url()  ?>/assets/format/DocumentType.xlsx" target="_blank" class="btn btn-success" >Descargar formato</a>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
        <button type="button" class="btn btn-primary" onclick="importDocument()">Procesar</button>
      </div>
    </div>
  </div>
</div> 

<div class="modal fade" id="importaccounts" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">IMPORTAR DATOS DE CUENTAS CONTROL</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
         <form  id="importaccount" action="<?php echo base_url()  ?>importacion_cuenta_control" method="post" role="form"  enctype="multipart/form-data">
            <div class="form-group">
                <label for="userfile">Selecciona tu excel (xlsx|xls)</label>
                <input type="file" class="form-control-file" id="userfile" name="userfile" accept=".xlsx,.xls">
            </div>
        </form>
      </div>
      <div class="modal-footer">
      <a href="<?php echo base_url()  ?>/assets/format/account_control.xlsx" target="_blank" class="btn btn-success" >Descargar formato</a>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
        <button type="button" class="btn btn-primary" onclick="importaccount()">Procesar</button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="importaccounthighers" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">IMPORTAR DATOS DE CUENTAS MAYOR</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
         <form  id="importaccounthigher" action="<?php echo base_url()  ?>importacion_cuenta_mayor" method="post" role="form"  enctype="multipart/form-data">
            <div class="form-group">
                <label for="userfile">Selecciona tu excel (xlsx|xls)</label>
                <input type="file" class="form-control-file" id="userfile" name="userfile" accept=".xlsx,.xls">
            </div>
        </form>
      </div>
      <div class="modal-footer">
      <a href="<?php echo base_url()  ?>/assets/format/cuenta_mayor.xlsx" target="_blank" class="btn btn-success" >Descargar formato</a>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
        <button type="button" class="btn btn-primary" onclick="importaccounthigher()">Procesar</button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="importaccountdetails" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">IMPORTAR DATOS DE CUENTAS DETALLE</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
         <form  id="importaccountdetail" action="<?php echo base_url()  ?>importacion_cuenta_detalle" method="post" role="form"  enctype="multipart/form-data">
            <div class="form-group">
                <label for="userfile">Selecciona tu excel (xlsx|xls)</label>
                <input type="file" class="form-control-file" id="userfile" name="userfile" accept=".xlsx,.xls">
            </div>
        </form>
      </div>
      <div class="modal-footer">
      <a href="<?php echo base_url()  ?>/assets/format/cuenta_detalle.xlsx" target="_blank" class="btn btn-success" >Descargar formato</a>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
        <button type="button" class="btn btn-primary" onclick="importaccountdetail()">Procesar</button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="load_bills" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">CARGA DE FACTURAS</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
         <form  id="load_bills_" action="<?php echo base_url()  ?>cargar_facturas" method="post" role="form"  enctype="multipart/form-data">
                    
            <div class="form-group">
                <label for="userfile">Selecciona tu excel (xlsx|xls)</label>
                <input type="file" class="form-control-file" id="userfile" name="userfile" accept=".xlsx,.xls">
            </div>
        </form>
        <p><b>NOTA: SE COMPROBARA LA EXISTENCIA DEL SERVICIO Y DEL APARTAMENTO ANTES DE AGREGAR LA FACTURA.</b></p>                 
      </div>
      <div class="modal-footer">
      <a href="<?php echo base_url()  ?>/assets/format/load_bills.xlsx" target="_blank" class="btn btn-success" >Descargar formato</a>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
        <button type="button" class="btn btn-primary" onclick="load_bills_()">Procesar</button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="payment_type" tabindex="-1" role="dialog" aria-labelledby="payment_type" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="payment_type">TIPOS DE PAGO</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
         <div class="row">
            <div class="col-sm-6" style="margin-bottom:2px"> 
                <a href="#" class ="btn btn-info card payment_bills" data-dismiss="modal" data-payment="3"> <img style="width: 100%;" src = "<?php echo base_url(); ?>/assets/images/ICON/icon_card_payment.png"> <spam style="color:black"><b>TARJETA</b></spam></a>
            </div>
            <div class="col-sm-6" style="margin-bottom:2px">
                <a href="#" class ="btn btn-info  card payment_bills" data-dismiss="modal" data-payment="2"> <img style="width: 100%;" src = "<?php echo base_url(); ?>/assets/images/ICON/icon_check_payment.jpg"><spam style="color:black"><b>CHEQUE</b></spam></a>
            </div>
            
            <div class="col-sm-6" style="margin-bottom:2px"> 
                <a href="#" class ="btn btn-info card payment_bills" data-dismiss="modal" data-payment="1"> <img style="width: 100%;" src = "<?php echo base_url(); ?>/assets/images/ICON/icon_transfer_payment.png"> <spam style="color:black"><b>TRANFERENCIA</b></spam></a>
            </div>
            <div class="col-sm-6" style="margin-bottom:2px">
                <a href="#" class ="btn btn-info card payment_bills " data-dismiss="modal"  data-payment="0"> <img style="width: 100%;" src = "<?php echo base_url(); ?>/assets/images/ICON/icon_cash_payment.png"><spam style="color:black"><b>EFECTIVO</b></spam></a>
            </div>
         </div>
      </div>
      <div class="modal-footer"> 
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>

 
        <div class="app-main">
            <div class="app-sidebar sidebar-shadow">
                <div class="app-header__logo">
                    <div class="logo-src"></div>
                    <div class="header__pane ml-auto">
                        <div>
                            <button type="button" class="hamburger close-sidebar-btn hamburger--elastic" data-class="closed-sidebar">
                                    <span class="hamburger-box">
                                        <span class="hamburger-inner"></span>
                                    </span>
                                </button>
                        </div>
                    </div>
                </div>
                <div class="app-header__mobile-menu">
                    <div>
                        <button type="button" class="hamburger hamburger--elastic mobile-toggle-nav">
                                <span class="hamburger-box">
                                    <span class="hamburger-inner"></span>
                                </span>
                            </button>
                    </div>
                </div>
                <div class="app-header__menu">
                    <span>
                        <button type="button" class="btn-icon btn-icon-only btn btn-primary btn-sm mobile-toggle-header-nav">
                            <span class="btn-icon-wrapper">
                                <i class="fa fa-ellipsis-v fa-w-6"></i>
                            </span>
                        </button>
                    </span>
                </div>
                <div class="scrollbar-sidebar  ps ps--active-y">
                    <div class="app-sidebar__inner">
                        <ul class="vertical-nav-menu metismenu ">
                            <li class="app-sidebar__heading">INICIO</li>
                            <li><!--class="mm-active"-->
                                <a href="<?php echo base_url(); ?>" >
                                    <i class="metismenu-icon pe-7s-graph3"></i> TABLERO
                                </a>
                            </li>
                            <?php  if($rol==ROLE_ADMIN){ ?>
                                <li> 
                                    <a href="<?php echo base_url(); ?>condominios">
                                        <i class="metismenu-icon pe-7s-keypad" style="color:#000 ;opacity:1;"></i> CONDOMINIOS 
                                    </a>
                                </li>
                            <?php } 
                            
                               if($condominium!=0){
                            if($rol<ROLE_VIGILANT){
                            
                            echo $rol<ROLE_VENDOR?'<li class="app-sidebar__heading">ADMINISTRACION</li>':""; 
                           
                         
                             if (checkForPermissions('users', PERMISSION_READ) == true) { ?> 
                            <li> 
                                <a href="<?php echo base_url(); ?>usuarios">
                                    <i class="metismenu-icon pe-7s-user"></i> USUARIOS 
                                </a> 
                            </li> 
                            <?php } 
                             if (checkForPermissions('edifices', PERMISSION_READ) == true) { ?> 
                            <li> 
                                <a href="<?php echo base_url(); ?>edificios">
                                    <i class="metismenu-icon pe-7s-culture"></i> EDIFICIOS 
                                </a>
                            </li>
                            <?php } 
                             if (checkForPermissions('apartments', PERMISSION_READ) == true) { ?> 
                            <li> 
                                <a href="<?php echo base_url(); ?>apartamentos">
                                    <i class="metismenu-icon pe-7s-culture"></i> APARTAMENTOS 
                                </a>
                            </li> 
                            <?php } 
                             if (checkForPermissions('tags', PERMISSION_READ) == true) { ?> 
                            <li> 
                                <a href="<?php echo base_url(); ?>etiquetas">
                                    <i class="metismenu-icon pe-7s-ticket"></i> ETIQUETAS
                                </a>
                            </li> 
                            <?php } 
                             if (checkForPermissions('commonarea', PERMISSION_READ) == true) { ?> 
                            <li> 
                                <a href="<?php echo base_url(); ?>areascomunes">
                                    <i class="metismenu-icon pe-7s-share"></i> AREAS COMUNES 
                                </a>
                            </li>
                            <?php } 
                             if (checkForPermissions('tenants', PERMISSION_READ) == true) { ?> 
                            <li> 
                                <a href="#">
                                    <i class="metismenu-icon pe-7s-users"></i> CLIENTES 
                                </a>
                                <ul >
                                    <li> 
                                        <a href="<?php echo base_url(); ?>clientes">
                                            <i class="metismenu-icon pe-7s-users"></i> LISTADO 
                                        </a>
                                    </li>
                                    <?php if (checkForPermissions('Payments', PERMISSION_READ) == true) { 
                                   
                                    ?>   
                                        <li>  <a href="<?php echo base_url(); ?>reportesdepago">
                                                <i class="metismenu-icon pe-7s-user"></i>REPORTE DE TRANSFERENCIA
                                            </a>
                                        </li> 
                                
                                    <?php }  ?>  
                                </ul >
                            </li>
                            <?php } 
                             if (checkForPermissions('services', PERMISSION_READ) == true) { ?> 
                            <li> 
                                <a href="<?php echo base_url(); ?>servicios">
                                    <i class="metismenu-icon pe-7s-paint-bucket"></i> SERVICIOS 
                                </a>
                            </li>
                            <?php } 
                             if (checkForPermissions('period', PERMISSION_READ) == true) { ?> 
                            <li> 
                                <a href="<?php echo base_url(); ?>periodos">
                                    <i class="metismenu-icon pe-7s-refresh-2"></i> PERIODOS 
                                </a>
                            </li>
                            <?php } 
                             if (checkForPermissions('directive', PERMISSION_READ) == true) { ?> 
                            <li> 
                                <a href="<?php echo base_url(); ?>juntadirectiva">
                                    <i class="metismenu-icon pe-7s-network"></i> JUNTA DIRECTIVA 
                                </a>
                            </li> 
                            <?php }  ?>   
                            <?php if (checkForPermissions('bills', PERMISSION_READ) == true && ($rol != ROLE_VENDOR)) { ?>  
                                <li class="app-sidebar__heading">CONTABILIDAD</li> 
                                <li>  <a href="<?php echo base_url(); ?>modulos">
                                        <i class="metismenu-icon pe-7s-portfolio"></i> MODULO 
                                    </a>
                                </li>
                                 <li  > 
                                    <a href="<?php echo base_url(); ?>tipodocumento">
                                        <i class="metismenu-icon pe-7s-photo-gallery"></i> TIPO DE DOCUMENTOS 
                                    </a>
                                </li>   
                                <li>
                                    <a href="#">
                                        <i class="metismenu-icon pe-7s-wallet"></i> MANTENIMIENTO DE CUENTAS
                                        <i class="metismenu-state-icon pe-7s-angle-down caret-left"></i>
                                    </a>
                                    <ul >
                                        <li> 
                                            <a href="<?php echo base_url(); ?>rubros">
                                                <i class="metismenu-icon pe-7s-user"></i> RUBROS 
                                            </a>
                                        </li>
                                        <li> 
                                            <a href="<?php echo base_url(); ?>cuenta_control">
                                                <i class="metismenu-icon pe-7s-user"></i> CUENTA CONTROL 
                                            </a>
                                        </li>
                                        <li> 
                                            <a href="<?php echo base_url(); ?>cuenta_mayor">
                                                <i class="metismenu-icon pe-7s-user"></i> CUENTA MAYOR 
                                            </a>
                                        </li>
                                        <li> 
                                            <a href="<?php echo base_url(); ?>cuenta_detalle">
                                                <i class="metismenu-icon pe-7s-user"></i> CUENTA DETALLE 
                                            </a>
                                        </li>
                                        <li> 
                                            <a href="<?php echo base_url(); ?>catalogo_cuentas" target="_blank">
                                                <i class="metismenu-icon pe-7s-user"></i> CATALOGO DE CUENTA
                                            </a>
                                        </li>
                                    </ul> 
                                <li> 
                                <li> 
                                    <a href="<?php echo base_url(); ?>establcer_parametros">
                                        <i class="metismenu-icon pe-7s-tools"></i>ESTABLECER PARAMETROS
                                    </a>
                                </li>  
                                <li> 
                                    <a href="<?php echo base_url(); ?>entradas_de_diario">
                                        <i class="metismenu-icon pe-7s-notebook"></i>ENTRADA DE DIARIO
                                    </a>
                                </li> 
                                <li>
                                    <a href="#">
                                        <i class="metismenu-icon pe-7s-note2"></i> REPORTES
                                        <i class="metismenu-state-icon pe-7s-angle-down caret-left"></i>
                                    </a>
                                    <ul >
                                        <li> 
                                            <a   href="#" data-toggle="modal" data-target="#report_bv">
                                                <i class="metismenu-icon pe-7s-user"></i> BALANZA DE COMPROBACION
                                            </a>
                                        </li> 
                                        <li> 
                                            <a   href="#" data-toggle="modal" data-target="#report_ra">
                                                <i class="metismenu-icon pe-7s-user"></i> RESULTADO ACOMULADO
                                            </a>
                                        </li> 
                                        <li> 
                                            <a   href="<?php echo base_url(); ?>auxiliar" target="_blank">
                                                <i class="metismenu-icon pe-7s-user"></i> AUXILIAR
                                            </a>
                                        </li>
                                        <li> 
                                            <a   href="<?php echo base_url(); ?>historial_auxiliar">
                                                <i class="metismenu-icon pe-7s-user"></i> HISTORIAL DE AUXILIAR
                                            </a>
                                        </li>
                                    </ul> 
                                <li> 
                            <?php } ?> 
                            <?php if (checkForPermissions('pay', PERMISSION_READ) == true) { ?>  
                                <li class="app-sidebar__heading">CAJA</li> 
                                <li>  <a href="<?php echo base_url(); ?>caja">
                                        <i class="metismenu-icon pe-7s-cash"></i> PAGAR 
                                    </a>
                                </li>  
                                <li>  <a href="<?php echo base_url(); ?>facturas">
                                        <i class="metismenu-icon pe-7s-news-paper"></i> FACTURAS 
                                    </a>
                                </li> 
                                <?php if($generates_report==1){ ?>
                                <li  > 
                                    <a href="<?php echo base_url(); ?>cierresdecaja">
                                        <i class="metismenu-icon pe-7s-download"></i>CIERRE DE CAJA
                                    </a>
                                </li>  
                            <?php }  } }?>   
                            <?php echo $rol<ROLE_VENDOR?'<li class="app-sidebar__heading">RESERVA DE AREA COMUN</li>':"" ?>  
                             <?php if (checkForPermissions('reservations', PERMISSION_READ)) { ?> 
                             <li> 
                                <a href="<?php echo base_url(); ?>reservaciones">
                                    <i class="metismenu-icon pe-7s-pin"></i><?php echo $rol<=ROLE_VIGILANT?'VER RESERVACIONES':"MIS RESERVACIONES" ?>  
                                </a>
                            </li> 
                            <?php } ?>  
                            <?php echo $rol<ROLE_VENDOR?'<li class="app-sidebar__heading">INSIDENCIAS</li>':"" ?>   
                            <?php  if (checkForPermissions('incidents', PERMISSION_READ) == true) { ?>  
                             <li> 
                                <a href="<?php echo base_url(); ?>insidencias">
                                    <i class="metismenu-icon pe-7s-attention"></i> <?php echo $rol<=ROLE_VIGILANT?'VER INSIDENCIAS':"MIS INSIDENCIAS" ?>    
                                </a>
                            </li> 
                            <?php } ?> 
                            <?php echo $rol<ROLE_VENDOR?'<li class="app-sidebar__heading">VIGILANCIA</li>':"" ?>   
                            <?php if (checkForPermissions('vigilant', PERMISSION_READ) == true) { ?>  
                             <li> 
                                <a href="<?php echo base_url(); ?>casillavigilante">
                                    <i class="metismenu-icon pe-7s-back-2"></i> ENTRADAS Y SALIDAS
                                </a> 
                            </li>   
                            <li> 
                                <a href="<?php echo base_url(); ?>visitantes">
                                    <i class="metismenu-icon pe-7s-smile "></i> VISITANTES
                                </a> 
                            </li> 
                            <?php if($generates_report==1){ ?>
                             <li> 
                                <a href="#"  data-toggle="modal" data-target="#exampleModal">
                                    <i class="metismenu-icon pe-7s-note2"></i> REPORTES
                                </a> 
                            </li>  
                            <?php }} ?>
                            <?php echo $rol<ROLE_VENDOR?' <li class="app-sidebar__heading">CLIENTES</li>':"" ?>   
                            <?php if ($rol>ROLE_VIGILANT) {  
                                ?>   
                                    <li>  <a href="<?php echo base_url(); ?>reportesdepago">
                                            <i class="metismenu-icon pe-7s-user"></i>REPORTE DE TRANSFERENCIA
                                        </a>
                                    </li> 
                                    <li>  <a href="<?php echo base_url(); ?>facturas">
                                        <i class="metismenu-icon pe-7s-user"></i> FACTURAS 
                                    </a>
                                </li> 
                                <?php }  ?>  
                            <?php   if (checkForPermissions('employees', PERMISSION_READ) == true) { ?> 
                            <li> 
                                <a href="<?php echo base_url(); ?>empleados">
                                    <i class="metismenu-icon pe-7s-id"></i> EMPLEADOS
                                </a> 
                            </li>  
                            <?php }  if (checkForPermissions('vehicles', PERMISSION_READ) == true) { ?> 
                            <li> 
                                <a href="<?php echo base_url(); ?>vehiculos">
                                    <i class="metismenu-icon pe-7s-car"></i> VEHICULOS
                                </a> 
                            </li> 
                            <?php } 
                        } ?>




                        </ul>
                    </div>
                     
                </div>
            </div>
            
            <div class="app-main__outer">
                