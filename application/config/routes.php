<?php
defined('BASEPATH') or exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|    example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|    https://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|    $route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|    $route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|    $route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples:    my-controller/index    -> my_controller/index
|        my-controller/my-method    -> my_controller/my_method
 */

$route['default_controller'] = "login";
$route['404_override'] = 'error_404';

$route['login'] = 'login/index';
$route['logUser'] = 'login/loguser';
$route['dashboard'] = 'user';
$route['logout'] = 'user/logout'; 
$route['usuarios'] = "user/userListing";
$route['addNew'] = "user/addNew";
$route['addNewUser'] = "user/addNewUser";
$route['editSelectedUser'] = "user/editOld";
$route['editSelectedUser/(:num)'] = "user/editOld/$1";
$route['permisos'] = "user/editPermissions";
$route['permisos/(:num)'] = "user/editPermissions/$1";
$route['actualizapermisos'] = "user/editUserPermissions";
$route['editUser'] = "user/editUser";
$route['deleteUser'] = "user/deleteUser";
$route['activaruser'] = "user/changeStatus";
$route['profile'] = "user/profile";
$route['profile/(:any)'] = "user/profile/$1";
$route['profileUpdate'] = "user/profileUpdate";
$route['profileUpdate/(:any)'] = "user/profileUpdate/$1";

$route['loadChangePass'] = "user/loadChangePass";
$route['changePassword'] = "user/changePassword";
$route['changePassword/(:any)'] = "user/changePassword/$1";
$route['pageNotFound'] = "user/pageNotFound";
$route['checkEmailExists'] = "user/checkEmailExists";

$route['login-history'] = "user/loginHistoy";
$route['login-history/(:num)'] = "user/loginHistoy/$1";
$route['login-history/(:num)/(:num)'] = "user/loginHistoy/$1/$2";

$route['recuperar'] = "login/forgotPassword";
$route['resetPasswordUser'] = "login/resetPasswordUser";
$route['resetearcontraconfir'] = "login/resetPasswordConfirmUser";
$route['resetearcontraconfir/(:any)'] = "login/resetPasswordConfirmUser/$1";
$route['resetearcontraconfir/(:any)/(:any)'] = "login/resetPasswordConfirmUser/$1/$2";
$route['resetearcontraconfir/(:any)/(:any)/(:any)'] = "login/resetPasswordConfirmUser/$1/$2/$3";
$route['createPasswordUser'] = "login/createPasswordUser";
 

/*CONDOMINIUM*/
$route['condominios'] = 'condominium/listing';
$route['agregarcondominio'] = 'condominium/addNew';
$route['nuevo_condominio'] = 'condominium/addNewData';
$route['editarcondominio'] = "condominium/editOld";
$route['editarcondominio/(:num)'] = "condominium/editOld/$1";
$route['editando_condominio'] = 'condominium/edit';
$route['eliminar_condominio'] = 'condominium/delete';
$route['cambio_estado'] = 'condominium/changeStatus';
$route['cambio_condominio'] = 'condominium/changeCondominium';

/*EDIFICE*/
$route['edificios'] = 'edifice/listing';
$route['agregaredificio'] = 'edifice/addNew';
$route['chequeo_edificio'] = 'edifice/checkExists';
$route['nuevo_edificio'] = 'edifice/addNewData';
$route['editaredificio'] = "edifice/editOld";
$route['editaredificio/(:num)'] = "edifice/editOld/$1";
$route['editando_edificio'] = 'edifice/edit'; 
$route['eliminar_edificio'] = 'edifice/delete';
$route['cambio_estado_edificio'] = 'edifice/changeStatus'; 
 
/*PERIODS*/
$route['periodos'] = 'period/listing';
$route['agregareperiodos'] = 'period/addNew'; 
$route['nuevo_periodo'] = 'period/addNewData';
$route['editarperiodos'] = "period/editOld";
$route['editarperiodos/(:num)'] = "period/editOld/$1";
$route['editando_periodos'] = 'period/edit'; 
$route['eliminar_periodos'] = 'period/delete'; 
$route['cambio_estado_periodo'] = 'period/changeStatus'; 

/*APARTMENTS*/
$route['apartamentos'] = 'apartment/listing';
$route['agregareapartamento'] = 'apartment/addNew'; 
$route['chequeo_apartamento'] = 'apartment/checkExists';
$route['nuevo_apartamento'] = 'apartment/addNewData'; 
$route['editarapartamento'] = "apartment/editOld";
$route['editarapartamento/(:num)'] = "apartment/editOld/$1";
$route['editando_apartamento'] = 'apartment/edit'; 
$route['eliminar_apartamento'] = 'apartment/delete'; 
$route['cambio_estado_apartamento'] = 'apartment/changeStatus'; 
$route['obtener_apartemantos/(:num)'] = 'apartment/getDataApartments/$1'; 

/*DIRECTIVE*/
$route['juntadirectiva'] = 'directive/listing';
$route['agregarmiembro'] = 'directive/addNew'; 
$route['chequeo_miembro'] = 'directive/checkExists';
$route['nuevo_miembro'] = 'directive/addNewData';  
$route['editarmiembro'] = "directive/editOld";
$route['editarmiembro/(:num)'] = "directive/editOld/$1";
$route['editando_miembro'] = 'directive/edit'; 
$route['eliminar_miembro'] = 'directive/delete'; 
$route['cambio_estado_miembro'] = 'directive/changeStatus';  

/*SERVICE*/
$route['servicios'] = 'service/listing';
$route['agregarservicio'] = 'service/addNew'; 
$route['chequeo_servicio'] = 'service/checkExists';
$route['nuevo_servicio'] = 'service/addNewData'; 
$route['editarservicio'] = "service/editOld";
$route['editarservicio/(:num)'] = "service/editOld/$1";
$route['editando_servicio'] = 'service/edit'; 
$route['eliminar_servicio'] = 'service/delete'; 
$route['cambio_estado_servicio'] = 'service/changeStatus';  

/*COMMONAREA*/
$route['areascomunes'] = 'commonarea/listing';
$route['agregarareascomunes'] = 'commonarea/addNew'; 
$route['chequeo_areascomunes'] = 'commonarea/checkExists';
$route['nuevo_areascomunes'] = 'commonarea/addNewData'; 
$route['editarareascomunes'] = "commonarea/editOld";
$route['editarareascomunes/(:num)'] = "commonarea/editOld/$1";
$route['editando_areascomunes'] = 'commonarea/edit'; 
$route['eliminar_areascomunes'] = 'commonarea/delete'; 
$route['cambio_estado_areascomunes'] = 'commonarea/changeStatus';  

/*TENANT*/
$route['inquilinos'] = 'tenant/listing'; 
$route['agregarinquilinos'] = 'tenant/addNew'; 
$route['chequeo_inquilinos'] = 'tenant/checkExists';
$route['nuevo_inquilinos'] = 'tenant/addNewData'; 
$route['editarinquilinos'] = "tenant/editOld";
$route['editarinquilinos/(:num)'] = "tenant/editOld/$1";
$route['editando_inquilinos'] = 'tenant/edit'; 
$route['eliminar_inquilinos'] = 'tenant/delete'; 
$route['cambio_estado_inquilinos'] = 'tenant/changeStatus';  
$route['asignaciones'] = 'tenant/assignments';
$route['asignaciones/(:num)'] = 'tenant/assignments/$1';
$route['asignar_apartamento'] = 'tenant/set_asignation'; 
$route['desvincular_apartamento'] = 'tenant/delete_asignation'; 

/*TAG*/
$route['etiquetas'] = 'tag/listing';
$route['agregaretiquetas'] = 'tag/addNew'; 
$route['chequeo_etiquetas'] = 'tag/checkExists';
$route['nuevo_etiquetas'] = 'tag/addNewData'; 
$route['editaretiquetas'] = "tag/editOld";
$route['editaretiquetas/(:num)'] = "tag/editOld/$1";
$route['editando_etiquetas'] = 'tag/edit'; 
$route['eliminar_etiquetas'] = 'tag/delete'; 
$route['cambio_estado_etiquetas'] = 'tag/changeStatus';  

/*RESERVATIONS*/
$route['reservaciones'] = 'reservation/listing';
$route['agregarreservaciones'] = 'reservation/addNew';  
$route['reservar_areacomun'] = 'reservation/addNewData'; 
$route['editarreservaciones'] = "reservation/editOld";
$route['editarreservaciones/(:num)'] = "reservation/editOld/$1";
$route['editando_reservaciones'] = 'reservation/edit'; 
$route['eliminar_reservaciones'] = 'reservation/delete'; 
$route['cambio_estado_reservaciones'] = 'reservation/changeStatus';  

/*INCIDENTS*/
$route['insidencias'] = 'incidents/listing';
$route['agregarinsidencias'] = 'incidents/addNew';  
$route['nuevo_insidencias'] = 'incidents/addNewData'; 
$route['editarinsidencias'] = "incidents/editOld";
$route['editarinsidencias/(:num)'] = "incidents/editOld/$1";
$route['editando_insidencias'] = 'incidents/edit'; 
$route['eliminar_insidencias'] = 'incidents/delete'; 
$route['ver_informacion_insidencias'] = 'incidents/viewInformation';  
$route['ver_informacion_insidencias/(:num)'] = "incidents/viewInformation/$1";
$route['cambio_estado_insidencias'] = 'incidents/changeStatus';  

/* End of file routes.php */
/* Location: ./application/config/routes.php */
