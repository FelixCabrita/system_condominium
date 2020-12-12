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
$route['editaredificio/(:num)'] = "edifice/editOld/$1";
$route['editando_edificio'] = 'edifice/edit'; 
$route['eliminar_edificio'] = 'edifice/delete';
$route['cambio_estado_edificio'] = 'edifice/changeStatus'; 
 
/*PERIODS*/
$route['periodos'] = 'period/listing';
$route['agregareperiodos'] = 'period/addNew'; 
$route['nuevo_periodo'] = 'period/addNewData';
$route['editarperiodos/(:num)'] = "period/editOld/$1";
$route['editando_periodos'] = 'period/edit'; 
$route['eliminar_periodos'] = 'period/delete'; 
$route['cambio_estado_periodo'] = 'period/changeStatus'; 
/* End of file routes.php */
/* Location: ./application/config/routes.php */
