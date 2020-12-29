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
    <link href="<?php echo base_url(); ?>assets/main.css" rel="stylesheet"></head>
    <link href="<?php echo base_url() ?>assets/scripts/sweetalert2/dist/sweetalert2.min.css" rel="stylesheet">
    <script src="<?php echo base_url(); ?>assets/scripts/jquery-3.5.1.min.js"></script>  
    <script> var baseURL = "<?php echo base_url(); ?>",c= "<?php echo  $condominium; ?>"; </script>   
</head>

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
                                </a>
                                
                            </li> 
                        </ul>
                    </div>
                <?php } ?>
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
                                            <a type="button" tabindex="0" class="dropdown-item">Configuraciones</a>
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
                                <div class="widget-content-right header-user-info ml-3">
                                    <button type="button" class="btn-shadow p-1 btn btn-primary btn-sm show-toastr-example">
                                        <i class="fa text-white fa-calendar pr-1 pl-1"></i>
                                    </button>
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
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary generate_report">Generar reporte</button>
            </div>
        </div>
    </div>
</div>  
<!-- Modal -->
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
<!-- Modal -->
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

<!-- Modal -->
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
                                    <i class="metismenu-icon pe-7s-graph3"></i> Dashboard
                                </a>
                            </li>
                            <?php echo $rol<ROLE_VIGILANT?'<li class="app-sidebar__heading">ADMINISTRACION</li>':"" ?> 
                            <?php  if($rol==ROLE_ADMIN){ ?>
                                <li> 
                                    <a href="<?php echo base_url(); ?>condominios">
                                        <i class="metismenu-icon pe-7s-user"></i> CONDOMINIOS 
                                    </a>
                                </li>
                            <?php } 
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
                                    <i class="metismenu-icon pe-7s-user"></i> EDIFICIOS 
                                </a>
                            </li>
                            <?php } 
                             if (checkForPermissions('apartments', PERMISSION_READ) == true) { ?> 
                            <li> 
                                <a href="<?php echo base_url(); ?>apartamentos">
                                    <i class="metismenu-icon pe-7s-user"></i> APARTAMENTOS 
                                </a>
                            </li> 
                            <?php } 
                             if (checkForPermissions('tags', PERMISSION_READ) == true) { ?> 
                            <li> 
                                <a href="<?php echo base_url(); ?>etiquetas">
                                    <i class="metismenu-icon pe-7s-user"></i> ETIQUETAS
                                </a>
                            </li> 
                            <?php } 
                             if (checkForPermissions('commonarea', PERMISSION_READ) == true) { ?> 
                            <li> 
                                <a href="<?php echo base_url(); ?>areascomunes">
                                    <i class="metismenu-icon pe-7s-user"></i> AREAS COMUNES 
                                </a>
                            </li>
                            <?php } 
                             if (checkForPermissions('tenants', PERMISSION_READ) == true) { ?> 
                            <li> 
                                <a href="<?php echo base_url(); ?>clientes">
                                    <i class="metismenu-icon pe-7s-user"></i> CLIENTES 
                                </a>
                            </li>
                            <?php } 
                             if (checkForPermissions('services', PERMISSION_READ) == true) { ?> 
                            <li> 
                                <a href="<?php echo base_url(); ?>servicios">
                                    <i class="metismenu-icon pe-7s-user"></i> SERVICIOS 
                                </a>
                            </li>
                            <?php } 
                             if (checkForPermissions('period', PERMISSION_READ) == true) { ?> 
                            <li> 
                                <a href="<?php echo base_url(); ?>periodos">
                                    <i class="metismenu-icon pe-7s-user"></i> PERIODOS 
                                </a>
                            </li>
                            <?php } 
                             if (checkForPermissions('directive', PERMISSION_READ) == true) { ?> 
                            <li> 
                                <a href="<?php echo base_url(); ?>juntadirectiva">
                                    <i class="metismenu-icon pe-7s-user"></i> JUNTA DIRECTIVA 
                                </a>
                            </li> 
                            <?php }  ?>  
                            <?php echo $rol<ROLE_VIGILANT?'<li class="app-sidebar__heading">RESERVA DE AREA COMUN</li>':"" ?>  
                             <?php if (checkForPermissions('reservations', PERMISSION_READ) == true) { ?> 
                             <li> 
                                <a href="<?php echo base_url(); ?>reservaciones">
                                    <i class="metismenu-icon pe-7s-user"></i><?php echo $rol<=ROLE_VIGILANT?'VER RESERVACIONES':"MIS RESERVACIONES" ?>  
                                </a>
                            </li> 
                            <?php } ?>  
                            <?php echo $rol<ROLE_VIGILANT?'<li class="app-sidebar__heading">INSIDENCIAS</li>':"" ?>   
                            <?php  if (checkForPermissions('incidents', PERMISSION_READ) == true) { ?>  
                             <li> 
                                <a href="<?php echo base_url(); ?>insidencias">
                                    <i class="metismenu-icon pe-7s-user"></i> <?php echo $rol<=ROLE_VIGILANT?'VER INSIDENCIAS':"MIS INSIDENCIAS" ?>    
                                </a>
                            </li> 
                            <?php } ?> 
                            <?php echo $rol<ROLE_VIGILANT?'<li class="app-sidebar__heading">VIGILANCIA</li>':"" ?>   
                            <?php if (checkForPermissions('vigilant', PERMISSION_READ) == true) { ?>  
                             <li> 
                                <a href="<?php echo base_url(); ?>casillavigilante">
                                    <i class="metismenu-icon pe-7s-user"></i> ENTRADAS Y SALIDAS
                                </a> 
                            </li>   
                            <li> 
                                <a href="<?php echo base_url(); ?>visitantes">
                                    <i class="metismenu-icon pe-7s-user"></i> VISITANTES
                                </a> 
                            </li> 
                             <li> 
                                <a href="#"  data-toggle="modal" data-target="#exampleModal">
                                    <i class="metismenu-icon pe-7s-user"></i> REPORTES
                                </a> 
                            </li>  
                            <?php } ?>
                            <?php echo $rol<ROLE_VIGILANT?' <li class="app-sidebar__heading">CLIENTES</li>':"" ?>   
                           
                            <?php   if (checkForPermissions('employees', PERMISSION_READ) == true) { ?> 
                            <li> 
                                <a href="<?php echo base_url(); ?>empleados">
                                    <i class="metismenu-icon pe-7s-user"></i> EMPLEADOS
                                </a> 
                            </li>  
                            <?php }  if (checkForPermissions('vehicles', PERMISSION_READ) == true) { ?> 
                            <li> 
                                <a href="<?php echo base_url(); ?>vehiculos">
                                    <i class="metismenu-icon pe-7s-user"></i> VEHICULOS
                                </a> 
                            </li> 
                            <?php }  ?>
                        </ul>
                    </div>
                     
                </div>
            </div>
            
            <div class="app-main__outer">
                